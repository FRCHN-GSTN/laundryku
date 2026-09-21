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

        foreach ($postSettings as $key => $value) {
            if ($key !== 'csrf_token' && $key !== 'csrf_hash') {
                $this->settingsModel->setValue($key, $value);
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan');
    }
}
