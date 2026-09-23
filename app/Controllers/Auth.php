<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if (session()->get('user_id')) {
            $role = session()->get('user_role');
            if (in_array($role, ['admin', 'staff'], true)) {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/customer/dashboard');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password harus diisi');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah');
        }

        if (isset($user['is_active']) && ! $user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
        }

        session()->regenerate();

        $sessionData = [
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'logged_in'  => true,
            'login_time' => time(),
        ];
        session()->set($sessionData);

        log_message('info', 'Login success: ' . $email . ' (role: ' . $user['role'] . ')');

        if (in_array($user['role'], ['admin', 'staff'], true)) {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/customer/dashboard');
    }

    public function register()
    {
        if (! $this->request->is('post')) {
            return view('auth/register');
        }

        $rules = [
            'name'             => 'required|min_length[3]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'matches[password]',
            'phone'            => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
            'role'      => 'customer',
            'is_active' => 1,
        ];

        if ($this->userModel->insert($userData)) {
            return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mendaftar. Silakan coba lagi.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout');
    }
}
