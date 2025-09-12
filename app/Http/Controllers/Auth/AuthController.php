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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class AuthController
 *
 * Handles authentication processes such as login, registration, and logout.
 */
class AuthController extends Controller
{
    /**
     * Display the login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login', [
            'titleShop' => '🔐 Masuk Akun - RAVAZKA | Login Seragam Sekolah Online',
            'title' => '🔐 Masuk Akun - RAVAZKA | Login Seragam Sekolah Online',
            'metaDescription' => '🚪 Masuk ke akun RAVAZKA Anda untuk berbelanja seragam sekolah dengan mudah. Akses keranjang tersimpan, riwayat pesanan, dan checkout yang lebih cepat.',
            'metaKeywords' => 'login RAVAZKA, masuk akun seragam, belanja seragam online, akun pelanggan RAVAZKA'
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
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
            
            $sessionId = Session::getId();
            Cart::mergeSessionToUser($user->id, $sessionId);
            
            $intendedUrl = $request->session()->get('url.intended', '/');
            
            $request->session()->forget('url.intended');
            
            if ($user->isAdmin()) {
                if (str_contains($intendedUrl, '/dashboard') || str_contains($intendedUrl, '/inventory')) {
                    return redirect($intendedUrl)->with('success', 'Login successful! Welcome Admin, ' . $user->name . '!');
                } else {
                    return redirect('/dashboard')->with('success', 'Login successful! Welcome Admin, ' . $user->name . '!');
                }
            }
            
            if (str_contains($intendedUrl, '/dashboard') || str_contains($intendedUrl, '/inventory')) {
                $intendedUrl = '/';
            }
            
            return redirect($intendedUrl)->with('success', 'Login successful! Welcome, ' . $user->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Incorrect email or password.'])
            ->withInput();
    }

    /**
     * Display the registration form.
     *
     * @return View
     */
    public function showRegisterForm(): View
    {
        return view('auth.register', [
            'titleShop' => '📝 Daftar Akun Baru - RAVAZKA | Registrasi Seragam Sekolah',
            'title' => '📝 Daftar Akun Baru - RAVAZKA | Registrasi Seragam Sekolah',
            'metaDescription' => '✨ Buat akun RAVAZKA gratis untuk berbelanja seragam sekolah dengan mudah. Dapatkan akses ke keranjang tersimpan, riwayat pesanan, dan penawaran khusus.',
            'metaKeywords' => 'daftar RAVAZKA, registrasi seragam sekolah, buat akun baru, member RAVAZKA'
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
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
            'role' => 'user',
        ]);

        Auth::login($user);

        $sessionId = Session::getId();
        Cart::mergeSessionToUser($user->id, $sessionId);

        return redirect('/')->with('success', 'Registration successful! Welcome, ' . $user->name . '!');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout successful!');
    }
}
