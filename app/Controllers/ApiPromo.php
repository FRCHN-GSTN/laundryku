<?php

namespace App\Controllers;

use App\Models\PromotionsModel;

class ApiPromo extends BaseController
{
    public function validate()
    {
        $code = trim($this->request->getPost('promo_code'));
        $orderTotal = (float) $this->request->getPost('order_total');

        if (empty($code)) {
            return $this->response->setJSON([
                'valid' => false,
                'message' => 'Masukkan kode promo',
            ]);
        }

        $promoModel = new PromotionsModel();
        $result = $promoModel->validateCode($code, $orderTotal);

        return $this->response->setJSON($result);
    }
}
