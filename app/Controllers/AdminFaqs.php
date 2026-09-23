<?php

namespace App\Controllers;

use App\Models\FaqsModel;

class AdminFaqs extends BaseController
{
    protected $faqModel;

    public function __construct()
    {
        $this->faqModel = new FaqsModel();
    }

    public function index()
    {
        $data = [
            'faqs' => $this->faqModel->orderBy('sort_order', 'ASC')->findAll(),
            'pageTitle' => 'Kelola FAQ',
        ];

        return view('admin/faqs', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $rules = [
                'question' => 'required|max_length[255]',
                'answer' => 'required',
                'category' => 'required|max_length[50]',
                'sort_order' => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $faqData = [
                'question' => $this->request->getPost('question'),
                'answer' => $this->request->getPost('answer'),
                'category' => $this->request->getPost('category'),
                'sort_order' => $this->request->getPost('sort_order'),
                'is_active' => true,
            ];

            $this->faqModel->insert($faqData);

            return redirect()->to('/admin/faqs')->with('success', 'FAQ berhasil ditambahkan');
        }

        $data = ['pageTitle' => 'Tambah FAQ'];
        return view('admin/faq_form', $data);
    }

    public function edit($faqId)
    {
        $faq = $this->faqModel->find($faqId);

        if (!$faq) {
            return redirect()->to('/admin/faqs')->with('error', 'FAQ tidak ditemukan');
        }

        if ($this->request->is('post')) {
            $rules = [
                'question' => 'required|max_length[255]',
                'answer' => 'required',
                'category' => 'required|max_length[50]',
                'sort_order' => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $faqData = [
                'question' => $this->request->getPost('question'),
                'answer' => $this->request->getPost('answer'),
                'category' => $this->request->getPost('category'),
                'sort_order' => $this->request->getPost('sort_order'),
                'is_active' => $this->request->getPost('is_active') === '1',
            ];

            $this->faqModel->update($faqId, $faqData);

            return redirect()->to('/admin/faqs')->with('success', 'FAQ berhasil diupdate');
        }

        $data = [
            'faq' => $faq,
            'pageTitle' => 'Edit FAQ',
        ];

        return view('admin/faq_form', $data);
    }

    public function delete($faqId)
    {
        $this->faqModel->delete($faqId);
        return redirect()->to('/admin/faqs')->with('success', 'FAQ berhasil dihapus');
    }
}
