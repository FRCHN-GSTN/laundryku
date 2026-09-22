<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\RatingModel;

class CustomerRating extends BaseController
{
    public function rate($orderId)
    {
        $userId = session()->get('user_id');
        $orderModel = new OrderModel();
        $order = $orderModel->where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order || $order['status'] !== 'completed') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa diberi rating');
        }

        $ratingModel = new RatingModel();
        $existing = $ratingModel->getOrderRating($orderId);

        if ($this->request->getMethod() === 'post') {
            $rating = (int) $this->request->getPost('rating');
            $review = $this->request->getPost('review');

            if ($rating < 1 || $rating > 5) {
                return redirect()->back()->with('error', 'Rating harus antara 1-5');
            }

            $data = [
                'order_id' => $orderId,
                'user_id' => $userId,
                'rating' => $rating,
                'review' => $review,
            ];

            if ($existing) {
                $ratingModel->update($existing['id'], $data);
            } else {
                $ratingModel->insert($data);
            }

            return redirect()->to('/customer/orders/' . $orderId)->with('success', 'Terima kasih atas penilaian Anda!');
        }

        $data = [
            'order' => $order,
            'rating' => $existing,
            'pageTitle' => 'Beri Penilaian',
        ];

        return view('customer/rating_form', $data);
    }
}
