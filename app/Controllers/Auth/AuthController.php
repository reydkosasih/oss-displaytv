<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login Admin — TV Display'
        ]);
    }

    /**
     * Proses pengiriman form login
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar atau akun nonaktif.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        // Set Data Session
        $sessionData = [
            'user_id'    => (int) $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ];
        session()->set($sessionData);

        // Update Last Login Timestamp
        $this->userModel->updateLastLogin((int) $user['id']);

        return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang kembali, ' . esc($user['name']));
    }

    /**
     * Logout pengguna
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
