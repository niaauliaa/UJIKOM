<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $request->only(['email', 'password']);

        if (Auth::attempt($user)) {
            session()->flash('success', 'Login berhasil!');
   
            $role = Auth::user()->role;

            if ($role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role == 'petugas') {
                return redirect()->route('petugas.dashboard');
            }

            return redirect()->route('home'); 
        } else {
            return redirect()->back()->with('failed', 'Proses login gagal, pastikan Email dan Passoword benar!');
        }
    }

    public function logout(Request $request)
        {
            Auth::logout();  
            
            Session::flash('logout', 'Berhasil Logout!.');

            return redirect()->route('login');  
        }
}
