<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Securely stream private KTP / SIM document to authorized admin or owner
     */
    public function show(Request $request, $id, $type)
    {
        $trx = Transaksi::findOrFail($id);

        // Security Authorization Check: Only Admin or Owner can view
        $user = auth()->user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'admin' && $trx->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke berkas dokumen ini.');
        }

        if (!in_array($type, ['ktp', 'sim'])) {
            abort(400, 'Tipe dokumen tidak valid.');
        }

        // Check if document was purged per UU PDP No. 27/2022
        if ($trx->dokumen_purged_at) {
            return response()->json([
                'error' => 'Dokumen Telah Dihapus',
                'message' => 'Dokumen KTP/SIM untuk booking ini telah dihapus permanen otomatis pada ' . $trx->dokumen_purged_at->format('d M Y H:i') . ' sesuai kepatuhan UU Perlindungan Data Pribadi No. 27/2022.',
                'purged_at' => $trx->dokumen_purged_at,
            ], 410); // 410 Gone
        }

        $filePath = $type === 'ktp' ? $trx->ktp_path : $trx->sim_path;

        if (!$filePath) {
            abort(404, 'Dokumen belum diunggah.');
        }

        // Search both private disk and storage_path
        $fullPath = storage_path('app/' . $filePath);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/private/' . $filePath);
        }

        if (!file_exists($fullPath)) {
            abort(404, 'File dokumen fisik tidak ditemukan pada server.');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Admin Dispute Freeze Toggle: Freeze / Unfreeze auto-purge for police/dispute investigation
     */
    public function toggleDispute(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang berwenang membekukan dokumen.');
        }

        $trx = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'is_disputed' => 'required|boolean',
            'dispute_reason' => 'nullable|string|max:500',
        ]);

        $trx->is_disputed = $validated['is_disputed'];
        $trx->dispute_reason = $validated['dispute_reason'] ?? null;
        $trx->save();

        $statusText = $trx->is_disputed ? 'DIBEKUKAN (Freeze Purge Aktif)' : 'NORMAL (Siap Purge Sesuai Jadwal)';
        AuditLog::record(
            'DISPUTE_STATUS_TOGGLE',
            "Status sengketa booking #{$trx->nomor_booking} diubah menjadi: {$statusText}. Alasan: " . ($trx->dispute_reason ?: '-'),
            'Transaksi',
            $trx->id
        );

        return back()->with('success', "Status sengketa berhasil diubah: {$statusText}");
    }
}
