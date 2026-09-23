<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminUsers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search') ?? '';
        $role   = $this->request->getGet('role') ?? '';

        $data = [
            'users'       => $this->userModel->getAllUsers($search, $role),
            'roles'       => UserModel::ROLES,
            'search'      => $search,
            'currentRole' => $role,
            'pageTitle'   => 'Manajemen User',
        ];

        return view('admin/users', $data);
    }

    public function update($userId)
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/admin/users');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        $currentUserId = (int) session()->get('user_id');
        $currentRole = (string) session()->get('role', '');
        $role = $this->request->getPost('role');
        $isActive = $this->request->getPost('is_active');

        if (! array_key_exists($role, UserModel::ROLES)) {
            return redirect()->back()->with('error', 'Role tidak valid');
        }

        // Hanya admin yang boleh kelola user & role
        if ($currentRole !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang boleh mengubah user & role');
        }

        // Staff tidak boleh membuat/mempertahankan dirinya jadi admin
        if ($currentRole !== 'admin' && $role === 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang boleh menetapkan role admin');
        }

        if ((int) $userId === $currentUserId) {
            if ($role !== 'admin') {
                return redirect()->back()->with('error', 'Tidak bisa mengubah role sendiri');
            }
            if ($isActive !== '1') {
                return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
            }
        }

        $updateData = [
            'role'      => $role,
            'is_active' => $isActive === '1' ? 1 : 0,
        ];

        if ($this->userModel->update($userId, $updateData)) {
            log_message('info', 'User role updated: #' . $userId . ' -> ' . $role . ' (by ' . $currentUserId . ')');
            return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate user');
    }

    public function toggle($userId)
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/admin/users');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan');
        }

        if ((int) $userId === (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }

        if ((string) session()->get('role', '') !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang boleh mengubah status user');
        }

        $newStatus = empty($user['is_active']) ? 1 : 0;

        $this->userModel->update($userId, ['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', 'User berhasil ' . $statusText);
    }
}
