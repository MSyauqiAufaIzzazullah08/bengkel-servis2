<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, arahkan ke dashboard sesuai role.
        if (session()->has('user_id')) {
            return $this->redirectByRole((int) session('role_id'));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        if (strtolower((string) $user->status) !== 'aktif') {
            return back()->with('error', 'Akun tidak aktif.');
        }

        session([
            'user_id' => $user->id_user,
            'user_nama' => $user->nama,
            'user_email' => $user->email,
            'role_id' => (int) $user->role_id,
        ]);

        $request->session()->regenerate();

        return $this->redirectByRole((int) $user->role_id);  
    }

    
public function logout(Request $request)
{
    $request->session()->flush();
    $request->session()->regenerateToken();

    return redirect('/login');
}


    

private function redirectByRole(int $roleId)
{
    if ($roleId === 1) {
        return redirect('/dashboard-super-admin');
    }

    if ($roleId === 2) {
        return redirect('/dashboard-admin-cabang');
    }

    if ($roleId === 3) {
        return redirect('/dashboard-petugas-service');
    }

    if ($roleId === 6) {
        return redirect('/dashboard-pelanggan');
    }

    // Dashboard staf untuk role lainnya tetap digunakan.
    return redirect('/dashboard');
}


}
