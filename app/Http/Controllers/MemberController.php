<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Tampilkan halaman register/login (form sama).
     */
    public function showPage()
    { 
        return view('register');
    }

    /**
     * Proses registrasi member baru.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'owner_name' => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:members',
            'phone'      => 'required|regex:/^[0-9]+$/|max:15|unique:members',
            'address'    => 'nullable|string|max:255',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $member = Member::create([
            'name'    => $data['owner_name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'],
            'address' => $data['address'],
            'password'=> Hash::make($data['password']),
            'role'    => 'member', // default setiap register jadi member
        ]);

        // langsung login setelah register
        Auth::guard('member')->login($member);
        $request->session()->regenerate();

        // Redirect to pet registration page
        return redirect()->route('register.pets')
                         ->with('success', 'Account created! Now let\'s add your pets.');
    }

    /**
     * Proses login (pakai email + password).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('member')->attempt($credentials)) {
            $request->session()->regenerate();

            $member = Auth::guard('member')->user();

            // ✅ cek role
            if ($member->role === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Welcome back, Admin!');
            }

            return redirect()->route('profile')
                             ->with('success', 'Login successful! Welcome back to Pawtopia.');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
