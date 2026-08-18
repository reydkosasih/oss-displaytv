<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Libraries\AuditLogger;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Tampilkan halaman utama Manajemen Kategori
     */
    public function index()
    {
        return view('admin/category/index', [
            'title' => 'Manajemen Kategori'
        ]);
    }

    /**
     * Endpoint AJAX: Ambil daftar semua kategori (JSON) + count konten
     */
    public function list()
    {
        $db = \Config\Database::connect();
        
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        // Count assigned contents per category
        foreach ($categories as &$cat) {
            $cat['contents_count'] = $db->table('contents')
                                        ->where('category_id', $cat['id'])
                                        ->where('deleted_at', null)
                                        ->countAllResults();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    /**
     * Endpoint AJAX: Simpan kategori baru
     */
    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'color' => 'permit_empty|regex_match[/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $name  = trim($this->request->getPost('name'));
        $color = $this->request->getPost('color') ?: '#6366f1'; // Default Indigo

        $slug = $this->categoryModel->generateSlug($name);

        $data = [
            'name'  => $name,
            'slug'  => $slug,
            'color' => $color,
        ];

        $this->categoryModel->insert($data);

        // Audit Log: Buat kategori baru
        AuditLogger::log('create', 'category', "Membuat kategori baru: {$name}", [
            'entity_name' => $name,
            'new_values'  => $data,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kategori baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Ambil detail kategori untuk edit
     */
    public function getJson($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $category
        ]);
    }

    /**
     * Endpoint AJAX: Update kategori
     */
    public function update($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ]);
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'color' => 'permit_empty|regex_match[/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $name  = trim($this->request->getPost('name'));
        $color = $this->request->getPost('color') ?: '#6366f1';

        $slug = ($name !== $category['name']) 
            ? $this->categoryModel->generateSlug($name, (int) $id) 
            : $category['slug'];

        $data = [
            'name'  => $name,
            'slug'  => $slug,
            'color' => $color,
        ];

        // Audit Log: Update kategori
        AuditLogger::log('update', 'category', "Mengubah kategori: {$category['name']} => {$name}", [
            'entity_id'   => $id,
            'entity_name' => $category['name'],
            'old_values'  => ['name' => $category['name'], 'slug' => $category['slug'], 'color' => $category['color']],
            'new_values'  => $data,
        ]);

        $this->categoryModel->update($id, $data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data kategori berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Soft delete kategori
     */
    public function delete($id = null)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Kategori tidak ditemukan.'
            ]);
        }

        // Audit Log: Hapus kategori
        AuditLogger::log('delete', 'category', "Menghapus kategori: {$category['name']}", [
            'entity_id'   => $id,
            'entity_name' => $category['name'],
            'old_values'  => ['name' => $category['name'], 'slug' => $category['slug'], 'color' => $category['color']],
        ]);

        $this->categoryModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kategori berhasil dihapus.'
        ]);
    }
}
