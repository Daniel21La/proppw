<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    /**
     * Send WhatsApp OTP
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'no_hp' => 'required|string|min:9|max:16',
        ]);

        $formatted = WhatsAppService::formatNumber($request->no_hp);

        // Anti-spam rate limit: Check if OTP was generated in the last 45 seconds
        $recent = OtpVerification::where('phone_number', $formatted)
            ->where('created_at', '>', now()->subSeconds(45))
            ->first();

        if ($recent) {
            $waitSeconds = 45 - now()->diffInSeconds($recent->created_at);
            return response()->json([
                'success' => false,
                'message' => "Mohon tunggu {$waitSeconds} detik sebelum meminta kode OTP baru.",
            ], 429);
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));

        OtpVerification::create([
            'phone_number' => $formatted,
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_verified' => false,
            'attempts' => 0,
        ]);

        $dispatch = WhatsAppService::sendOtp($formatted, $otp);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP 6 digit telah dikirim ke nomor WhatsApp Anda.',
            'debug_otp' => $dispatch['debug_otp'] ?? null,
        ]);
    }

    /**
     * Verify WhatsApp OTP
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'no_hp' => 'required|string',
            'otp_code' => 'required|string|size:6',
        ]);

        $formatted = WhatsAppService::formatNumber($request->no_hp);

        $record = OtpVerification::where('phone_number', $formatted)
            ->where('is_verified', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan OTP tidak ditemukan. Silakan klik kirim ulang.',
            ], 404);
        }

        if (now()->greaterThan($record->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kadaluarsa. Silakan minta kode baru.',
            ], 422);
        }

        if ($record->attempts >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan salah. Silakan minta kode OTP baru.',
            ], 429);
        }

        if ($record->otp_code !== trim($request->otp_code)) {
            $record->increment('attempts');
            $remaining = 5 - $record->attempts;
            return response()->json([
                'success' => false,
                'message' => "Kode OTP tidak cocok. Sisa percobaan: {$remaining}",
            ], 422);
        }

        // Verified successfully
        $record->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        session(['verified_phone' => $formatted]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor WhatsApp berhasil diverifikasi!',
            'verified_phone' => $formatted,
        ]);
    }
}
