<?php

namespace App\Controllers;

use App\Models\PromotionsModel;

class AdminPromotions extends BaseController
{
    protected $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromotionsModel();
    }

    public function index()
    {
        $data = [
            'promotions' => $this->promoModel->orderBy('created_at', 'DESC')->findAll(),
            'pageTitle' => 'Kelola Promo',
        ];

        return view('admin/promotions', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $rules = [
                'title' => 'required|max_length[150]',
                'discount_type' => 'required|in_list[percentage,fixed]',
                'discount_value' => 'required|numeric',
                'start_date' => 'required|valid_date',
                'end_date' => 'required|valid_date',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $promoData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'discount_type' => $this->request->getPost('discount_type'),
                'discount_value' => $this->request->getPost('discount_value'),
                'min_order' => $this->request->getPost('min_order') ?: null,
                'max_discount' => $this->request->getPost('max_discount') ?: null,
                'usage_limit' => $this->request->getPost('usage_limit') !== null && $this->request->getPost('usage_limit') !== ''
                    ? (int) $this->request->getPost('usage_limit')
                    : null,
                'promo_code' => $this->request->getPost('promo_code') ?: null,
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'is_active' => true,
            ];

            $this->promoModel->insert($promoData);

            return redirect()->to('/admin/promotions')->with('success', 'Promo berhasil ditambahkan');
        }

        $data = ['pageTitle' => 'Tambah Promo'];
        return view('admin/promotion_form', $data);
    }

    public function edit($promoId)
    {
        $promo = $this->promoModel->find($promoId);

        if (!$promo) {
            return redirect()->to('/admin/promotions')->with('error', 'Promo tidak ditemukan');
        }

        if ($this->request->is('post')) {
            $rules = [
                'title' => 'required|max_length[150]',
                'discount_type' => 'required|in_list[percentage,fixed]',
                'discount_value' => 'required|numeric',
                'start_date' => 'required|valid_date',
                'end_date' => 'required|valid_date',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $promoData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'discount_type' => $this->request->getPost('discount_type'),
                'discount_value' => $this->request->getPost('discount_value'),
                'min_order' => $this->request->getPost('min_order') ?: null,
                'max_discount' => $this->request->getPost('max_discount') ?: null,
                'usage_limit' => $this->request->getPost('usage_limit') !== null && $this->request->getPost('usage_limit') !== ''
                    ? (int) $this->request->getPost('usage_limit')
                    : null,
                'promo_code' => $this->request->getPost('promo_code') ?: null,
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'is_active' => $this->request->getPost('is_active') === '1',
            ];

            $this->promoModel->update($promoId, $promoData);

            return redirect()->to('/admin/promotions')->with('success', 'Promo berhasil diupdate');
        }

        $data = [
            'promotion' => $promo,
            'pageTitle' => 'Edit Promo',
        ];

        return view('admin/promotion_form', $data);
    }

    public function delete($promoId)
    {
        $this->promoModel->delete($promoId);
        return redirect()->to('/admin/promotions')->with('success', 'Promo berhasil dihapus');
    }
}
