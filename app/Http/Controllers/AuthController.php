<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $email = strtolower($credentials['email']);
            $isSeller = ($email === 'adminbakery@gmail.com');
            session(['is_seller' => $isSeller]);

            if ($isSeller) {
                return redirect()->route('seller.dashboard')
                    ->with('info', 'Welcome, Seller!');
            }

            return redirect()->intended(route('home'))
                ->with('info', 'Welcome back!');
        }

        return back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->onlyInput('email');
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:191', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $isSeller = (strtolower($user->email) === 'adminbakery@gmail.com');
        session(['is_seller' => $isSeller]);

        return $isSeller
            ? redirect()->route('seller.dashboard')->with('info', 'Seller account created. Welcome!')
            : redirect()->route('home')->with('info', 'Account created. Welcome!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget('is_seller');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been logged out.');
    }
}