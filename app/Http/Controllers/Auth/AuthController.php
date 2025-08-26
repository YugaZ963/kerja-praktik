<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login', [
            'titleShop' => '🔐 Masuk Akun - RAVAZKA | Login Seragam Sekolah Online',
            'title' => '🔐 Masuk Akun - RAVAZKA | Login Seragam Sekolah Online',
            'metaDescription' => '🚪 Masuk ke akun RAVAZKA Anda untuk berbelanja seragam sekolah dengan mudah. Akses keranjang tersimpan, riwayat pesanan, dan checkout yang lebih cepat.',
            'metaKeywords' => 'login RAVAZKA, masuk akun seragam, belanja seragam online, akun pelanggan RAVAZKA'
        ]);
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Merge cart session ke user cart setelah login
            $sessionId = Session::getId();
            Cart::mergeSessionToUser($user->id, $sessionId);
            
            // Ambil intended URL dari session
            $intendedUrl = $request->session()->get('url.intended', '/');
            
            // Hapus intended URL dari session setelah diambil
            $request->session()->forget('url.intended');
            
            // Jika user adalah admin
            if ($user->isAdmin()) {
                // Jika intended URL adalah halaman admin, gunakan itu, jika tidak ke dashboard
                if (str_contains($intendedUrl, '/dashboard') || str_contains($intendedUrl, '/inventory')) {
                    return redirect($intendedUrl)->with('success', 'Login berhasil! Selamat datang Admin, ' . $user->name . '!');
                } else {
                    return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang Admin, ' . $user->name . '!');
                }
            }
            
            // Jika user biasa, pastikan tidak redirect ke halaman admin
            if (str_contains($intendedUrl, '/dashboard') || str_contains($intendedUrl, '/inventory')) {
                $intendedUrl = '/';
            }
            
            return redirect($intendedUrl)->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('auth.register', [
            'titleShop' => '📝 Daftar Akun Baru - RAVAZKA | Registrasi Seragam Sekolah',
            'title' => '📝 Daftar Akun Baru - RAVAZKA | Registrasi Seragam Sekolah',
            'metaDescription' => '✨ Buat akun RAVAZKA gratis untuk berbelanja seragam sekolah dengan mudah. Dapatkan akses ke keranjang tersimpan, riwayat pesanan, dan penawaran khusus.',
            'metaKeywords' => 'daftar RAVAZKA, registrasi seragam sekolah, buat akun baru, member RAVAZKA'
        ]);
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Semua registrasi baru otomatis menjadi user/customer
        ]);

        Auth::login($user);

        // Merge cart session ke user cart setelah register
        $sessionId = Session::getId();
        Cart::mergeSessionToUser($user->id, $sessionId);

        // Semua user baru diarahkan ke halaman utama sebagai customer
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
    }

    /**
     * Show admin register form
     */
    public function showAdminRegisterForm()
    {
        return view('auth.admin-register', [
            'titleShop' => '👨‍💼 Registrasi Admin - RAVAZKA | Daftar Administrator Baru',
            'title' => '👨‍💼 Registrasi Admin - RAVAZKA | Daftar Administrator Baru',
            'metaDescription' => '🔧 Form registrasi khusus administrator RAVAZKA. Buat akun admin baru untuk mengelola sistem, inventaris, dan pesanan seragam sekolah.',
            'metaKeywords' => 'registrasi admin RAVAZKA, daftar administrator, akun admin baru, manajemen sistem'
        ]);
    }

    /**
     * Handle admin register request
     */
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Registrasi khusus admin
        ]);

        return redirect()->route('dashboard')->with('success', 'Admin baru berhasil didaftarkan: ' . $user->name . '!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}
