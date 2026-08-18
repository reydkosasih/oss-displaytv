<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\UserCategoryModel;
use App\Libraries\AuditLogger;

class UserController extends BaseController
{
    protected $userModel;
    protected $categoryModel;
    protected $userCategoryModel;

    public function __construct()
    {
        $this->userModel         = new UserModel();
        $this->categoryModel     = new CategoryModel();
        $this->userCategoryModel = new UserCategoryModel();
    }

    /**
     * Tampilkan halaman utama Manajemen User
     */
    public function index()
    {
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        return view('admin/user/index', [
            'title'      => 'Manajemen User',
            'categories' => $categories,
        ]);
    }

    /**
     * Endpoint AJAX: Ambil daftar semua user (JSON)
     */
    public function list()
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($users as &$user) {
            $user['categories']   = $this->userCategoryModel->getCategoriesByUser((int) $user['id']);
            $user['category_ids'] = array_column($user['categories'], 'id');
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $users
        ]);
    }

    /**
     * Endpoint AJAX: Simpan user baru
     */
    public function store()
    {
        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[6]',
            'role'       => 'required|in_list[superadmin,admin]',
            'department' => 'permit_empty|max_length[100]',
            'is_active'  => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $role       = $this->request->getPost('role');
        $department = trim((string) $this->request->getPost('department'));
        $isActive   = (int) $this->request->getPost('is_active');

        $data = [
            'name'       => trim($this->request->getPost('name')),
            'email'      => trim($this->request->getPost('email')),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'       => $role,
            'department' => $department !== '' ? $department : null,
            'is_active'  => $isActive === 1 ? 1 : 0,
        ];

        $userId = $this->userModel->insert($data);

        // Sync category access jika role admin
        if ($role === 'admin') {
            $categoryIds = $this->request->getPost('category_ids') ?? [];
            $this->userCategoryModel->syncCategories((int) $userId, (array) $categoryIds);
        } else {
            $this->userCategoryModel->syncCategories((int) $userId, []);
        }

        // Audit Log: Buat user baru
        AuditLogger::log('create', 'user', "Membuat user baru: {$data['name']} ({$data['email']}) sebagai {$role}", [
            'entity_id'   => $userId,
            'entity_name' => $data['name'],
            'new_values'  => array_diff_key($data, ['password' => '']),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'User baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Ambil detail user untuk modal edit
     */
    public function getJson($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.'
            ]);
        }

        unset($user['password']); // Hapus password dari response JSON

        $user['category_ids'] = $this->userCategoryModel->getCategoryIdsByUser((int) $id);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $user
        ]);
    }

    /**
     * Endpoint AJAX: Update data user
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.'
            ]);
        }

        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'       => 'required|in_list[superadmin,admin]',
            'department' => 'permit_empty|max_length[100]',
            'is_active'  => 'required|in_list[0,1]',
        ];

        // Password opsional saat update
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $role       = $this->request->getPost('role');
        $department = trim((string) $this->request->getPost('department'));
        $isActive   = (int) $this->request->getPost('is_active');

        $data = [
            'name'       => trim($this->request->getPost('name')),
            'email'      => trim($this->request->getPost('email')),
            'role'       => $role,
            'department' => $department !== '' ? $department : null,
            'is_active'  => $isActive === 1 ? 1 : 0,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Cegah pengguna menonaktifkan dirinya sendiri
        if ((int) $id === (int) session()->get('user_id') && $data['is_active'] === 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang digunakan.'
            ]);
        }

        $this->userModel->update($id, $data);

        // Sync category access jika role admin
        if ($role === 'admin') {
            $categoryIds = $this->request->getPost('category_ids') ?? [];
            $this->userCategoryModel->syncCategories((int) $id, (array) $categoryIds);
        } else {
            $this->userCategoryModel->syncCategories((int) $id, []);
        }

        // Audit Log: Update user
        $oldSnap = array_diff_key($user, ['password' => '']);
        $newSnap = array_diff_key($data, ['password' => '']);
        AuditLogger::log('update', 'user', "Memperbarui data user: {$user['name']} (ID:{$id})", [
            'entity_id'   => $id,
            'entity_name' => $user['name'],
            'old_values'  => $oldSnap,
            'new_values'  => $newSnap,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data user berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Soft delete user
     */
    public function delete($id = null)
    {
        $currentUserId = (int) session()->get('user_id');

        if ((int) $id === $currentUserId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.'
            ]);
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.'
            ]);
        }

        // Audit Log: Hapus user
        AuditLogger::log('delete', 'user', "Menghapus user: {$user['name']} ({$user['email']})", [
            'entity_id'   => $id,
            'entity_name' => $user['name'],
            'old_values'  => ['name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']],
        ]);

        $this->userModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'User berhasil dihapus.'
        ]);
    }

    /**
     * Endpoint AJAX: Toggle status aktif/nonaktif
     */
    public function toggleStatus($id = null)
    {
        $currentUserId = (int) session()->get('user_id');

        if ((int) $id === $currentUserId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak dapat mengubah status akun Anda sendiri.'
            ]);
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.'
            ]);
        }

        $newStatus = $user['is_active'] == 1 ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        // Audit Log: Toggle status user
        $statusLabel = $newStatus === 1 ? 'Aktif' : 'Nonaktif';
        AuditLogger::log('toggle', 'user', "Mengubah status user: {$user['name']} menjadi {$statusLabel}", [
            'entity_id'   => $id,
            'entity_name' => $user['name'],
            'old_values'  => ['is_active' => $user['is_active']],
            'new_values'  => ['is_active' => $newStatus],
        ]);

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Status user berhasil diperbarui.',
            'new_status' => $newStatus
        ]);
    }
}

