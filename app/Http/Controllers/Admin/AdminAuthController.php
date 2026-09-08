<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function create()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.transaksi.index');
        }

        return Inertia::render('Admin/Auth/Login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial login admin tidak sesuai.',
            ]);
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akses ditolak. Akun Anda tidak memiliki hak akses Administrator.',
            ]);
        }

        $request->session()->regenerate();

        AuditLog::record('ADMIN_LOGIN', "Administrator {$user->name} ({$user->email}) berhasil masuk ke sistem.", 'User', $user->id);

        return redirect()->intended(route('admin.transaksi.index'));
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditLog::record('ADMIN_LOGOUT', "Administrator {$user->name} keluar dari sistem.", 'User', $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
