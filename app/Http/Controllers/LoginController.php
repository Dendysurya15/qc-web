<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.index');
    }

    public function authenticate(Request $request)
    {
        $user = DB::table('pengguna')
            ->get();

        $email = $request->input('email');
        $password = $request->input('password');
        $this->validate($request, [
            'email_or_nama_lengkap' => 'required',
            'password' => 'required'
        ]);


        $user = DB::table('pengguna')->where('email', $request->email_or_nama_lengkap)
            ->orWhere('nama_lengkap', $request->email_or_nama_lengkap)
            ->first();
        if (!$user) {

            return back()->with('error', 'Email atau Password Salah');
            // dd($user);
        }

        // cek password yang diterima dengan password yang tersimpan di database
        if ($request->password != $user->password) {
            return back()->with('error', 'Email atau Password Salah');
        }
        // $request->session()->put('nama_lengkap', $user->name);
        session(['user_id' => $user->user_id, 'user_name' => $user->nama_lengkap]);
        // jika email dan password cocok, login user
        // Auth::login($user);



        return redirect()->intended('dashboard_gudang');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
