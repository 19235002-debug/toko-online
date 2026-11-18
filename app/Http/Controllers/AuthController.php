<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'superadmin'])) {
                Alert::success('Login Berhasil!', 'Selamat datang kembali di Dashboard Admin, ' . $user->nama);
                return redirect()->intended('/dashboard');
            } else {
                Alert::success('Login Berhasil!', 'Halo ' . $user->nama . ', selamat datang kembali di Kelompok 3 Fashion!');
                return redirect()->intended('/');
            }
        } else {
            Alert::error('Login Gagal!', 'Email atau kata sandi yang Anda masukkan salah.');
            return back()->withInput($request->only('email'));
        }
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Daftar Akun Baru',
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string|min:10',
            'password' => 'required|min:6',
            'passwordConfirm' => 'required|same:password',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'email.email' => 'Format email aktif tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung login.',
            'no_hp.required' => 'Nomor WhatsApp / HP aktif wajib diisi.',
            'no_hp.regex' => 'Nomor WhatsApp / HP harus berupa angka (10 - 15 digit, contoh: 081234567890).',
            'alamat.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'alamat.min' => 'Alamat pengiriman terlalu singkat (minimal 10 karakter).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'passwordConfirm.required' => 'Konfirmasi password wajib diisi.',
            'passwordConfirm.same' => 'Konfirmasi password tidak cocok dengan password.',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Selalu role user untuk pendaftaran publik
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
        ]);

        // Otomatis login setelah pendaftaran berhasil agar alur mulus
        Auth::login($user);
        $request->session()->regenerate();

        Alert::success('Pendaftaran Berhasil!', 'Selamat bergabung di Kelompok 3 Fashion, ' . $user->nama . '! Silakan mulai berbelanja.');
        return redirect('/');
    }

    public function logout(Request $request)
    {
        $isAdmin = auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Berhasil Keluar', 'Anda telah berhasil logout.');
        return $isAdmin ? redirect('/login') : redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password',
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tersebut tidak terdaftar di sistem kami.',
        ]);

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        $emailSent = false;
        try {
            Mail::raw(
                "Halo,\n\nKami menerima permintaan reset password untuk akun Anda.\nKlik tautan berikut untuk membuat password baru (berlaku 60 menit):\n{$resetUrl}\n\nAbaikan pesan ini jika Anda tidak merasa meminta reset password.",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Reset Password - Fashion Store');
                }
            );
            $emailSent = true;
        } catch (\Throwable $e) {
            $emailSent = false;
        }

        $statusMsg = $emailSent
            ? 'Tautan reset password telah dikirim ke email Anda.'
            : 'Tautan reset password berhasil dibuat (mode lokal/tanpa SMTP).';

        return back()
            ->with('status', $statusMsg)
            ->with('reset_link_demo', $resetUrl);
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah digunakan.'])->withInput();
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password telah kedaluwarsa. Silakan minta tautan baru.'])->withInput();
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Alert::success('Berhasil', 'Password Anda telah diperbarui. Silakan login dengan password baru.');
        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
    }
}
