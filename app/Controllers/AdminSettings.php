<?php

namespace App\Controllers;

use App\Models\SettingsModel;

class AdminSettings extends BaseController
{
    protected $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        $data = [
            'settings' => $this->settingsModel->getAll(),
            'pageTitle' => 'Pengaturan',
        ];

        return view('admin/settings', $data);
    }

    public function update()
    {
        $postSettings = $this->request->getPost();

        // Handle QRIS image upload
        $qrisFile = $this->request->getFile('qris_image_file');
        $qrisDelete = $this->request->getPost('qris_image_delete');

        // Delete QRIS image if requested
        if ($qrisDelete && !empty($this->settingsModel->getValue('qris_image'))) {
            $oldImage = $this->settingsModel->getValue('qris_image');
            $imagePath = FCPATH . ltrim($oldImage, '/');
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $this->settingsModel->setValue('qris_image', '');
        }

        // Upload new QRIS image
        if ($qrisFile && $qrisFile->isValid() && !$qrisFile->hasMoved()) {
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
            if (!in_array($qrisFile->getMimeType(), $allowedTypes)) {
                return redirect()->back()->with('error', 'Format gambar tidak valid. Gunakan JPG, PNG, atau SVG.');
            }

            if ($qrisFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran gambar maksimal 2MB.');
            }

            // Create directory if not exists
            $uploadPath = FCPATH . 'uploads/qris';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old image if exists
            $oldImage = $this->settingsModel->getValue('qris_image');
            if (!empty($oldImage)) {
                $oldPath = FCPATH . ltrim($oldImage, '/');
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Save new file
            $newName = 'qris_' . time() . '.' . $qrisFile->getExtension();
            $qrisFile->move($uploadPath, $newName);
            $this->settingsModel->setValue('qris_image', '/uploads/qris/' . $newName);
        }

        // Save other settings
        foreach ($postSettings as $key => $value) {
            if (!in_array($key, ['csrf_token', 'csrf_hash', 'qris_image_file', 'qris_image_delete'])) {
                $this->settingsModel->setValue($key, $value);
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan');
    }
}
