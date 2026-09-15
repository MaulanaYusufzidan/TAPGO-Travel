<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * PRD section 7 Information Architecture: Authentication -> Forgot Password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Selalu kembalikan pesan generik yang sama baik email
        // terdaftar maupun tidak, supaya tidak bisa dipakai untuk
        // menebak email mana yang punya akun (user enumeration).
        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'Kalau email tersebut terdaftar, link reset password sudah dikirim.');
    }
}
