<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\AuditLogger;

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

        // Audit Log: Login berhasil
        AuditLogger::log('login', 'auth', "Login berhasil: {$user['name']} ({$user['email']}) sebagai {$user['role']}", [
            'user_id'   => (int) $user['id'],
            'user_name' => $user['name'],
            'user_role' => $user['role'],
            'entity_name' => $user['email'],
        ]);

        return redirect()->to('/admin/dashboard')->with('success', 'Selamat datang kembali, ' . esc($user['name']));
    }

    /**
     * Logout pengguna
     */
    public function logout()
    {
        $reason = $this->request->getGet('reason');

        // Audit Log: Logout (sebelum session dihancurkan)
        $userId   = (int) session()->get('user_id');
        $userName = (string) session()->get('name');
        $userRole = (string) session()->get('role');
        $logoutDesc = $reason === 'timeout'
            ? "Logout otomatis (session timeout) — {$userName}"
            : "Logout manual — {$userName}";
        AuditLogger::log('logout', 'auth', $logoutDesc, [
            'user_id'   => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
        ]);

        session()->destroy();

        if ($reason === 'timeout') {
            session()->setFlashdata('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 15 menit. Silakan login kembali.');
        } else {
            session()->setFlashdata('success', 'Anda telah berhasil logout.');
        }

        return redirect()->to('/login');
    }

    /**
     * Keep-alive ping untuk memperpanjang session admin
     */
    public function keepAlive()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthenticated'
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Session refreshed',
            'timestamp' => time()
        ]);
    }
}
