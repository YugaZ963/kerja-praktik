<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
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
            
            // Jika user adalah admin, redirect ke dashboard
            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard')->with('success', 'Login berhasil! Selamat datang Admin, ' . $user->name . '!');
            }
            
            // Jika user biasa, redirect ke halaman sebelumnya atau beranda
            $intendedUrl = $request->session()->get('url.intended', '/');
            
            // Pastikan tidak redirect ke dashboard untuk user biasa
            if (str_contains($intendedUrl, '/dashboard')) {
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
        return view('auth.register');
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
            'role' => 'sometimes|in:admin,user',
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
            'role' => $request->role ?? 'user', // Default to 'user' if not specified
        ]);

        Auth::login($user);

        // Redirect berdasarkan role user
        if ($user->isAdmin()) {
            return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang Admin, ' . $user->name . '!');
        }
        
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
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
