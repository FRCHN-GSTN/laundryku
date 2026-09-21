<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ServiceModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $serviceModel;
    protected $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->serviceModel = new ServiceModel();
        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        $data = [
            'stats' => $this->orderModel->getStats(),
            'todayOrders' => $this->orderModel->getTodayOrders(),
            'todayRevenue' => $this->orderModel->getTodayRevenue(),
            'pendingOrders' => $this->orderModel->where('status', 'pending')->orderBy('created_at', 'ASC')->findAll(),
        ];

        return view('admin/dashboard', $data);
    }

    public function orders()
    {
        $status = $this->request->getGet('status');

        if ($status) {
            $orders = $this->orderModel->where('status', $status)->getOrdersWithUser();
        } else {
            $orders = $this->orderModel->getOrdersWithUser();
        }

        $data = [
            'orders' => $orders,
            'currentStatus' => $status,
        ];

        return view('admin/orders', $data);
    }

    public function orderDetail($orderId)
    {
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'order' => $order,
            'items' => $this->orderItemModel->getOrderItems($orderId),
            'user' => $this->userModel->find($order['user_id']),
        ];

        return view('admin/order_detail', $data);
    }

    public function updateStatus($orderId)
    {
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'confirmed', 'washing', 'drying', 'ironing', 'ready', 'delivered', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $this->orderModel->update($orderId, ['status' => $status]);

        return redirect()->to('/admin/orders/' . $orderId)->with('success', 'Status pesanan berhasil diupdate');
    }

    public function services()
    {
        $data = [
            'services' => $this->serviceModel->findAll(),
        ];

        return view('admin/services', $data);
    }

    public function createService()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
                'unit' => 'required|in_list[kg,pcs]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $serviceData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'unit' => $this->request->getPost('unit'),
                'is_active' => true,
            ];

            $this->serviceModel->insert($serviceData);

            return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan');
        }

        return view('admin/service_form');
    }

    public function editService($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);

        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Layanan tidak ditemukan');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
                'unit' => 'required|in_list[kg,pcs]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $serviceData = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'unit' => $this->request->getPost('unit'),
                'is_active' => $this->request->getPost('is_active') === '1',
            ];

            $this->serviceModel->update($serviceId, $serviceData);

            return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diupdate');
        }

        $data = [
            'service' => $service,
        ];

        return view('admin/service_form', $data);
    }

    public function deleteService($serviceId)
    {
        $this->serviceModel->delete($serviceId);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil dihapus');
    }

    public function customers()
    {
        $data = [
            'customers' => $this->userModel->getCustomers(),
        ];

        return view('admin/customers', $data);
    }

    public function customerDetail($customerId)
    {
        $customer = $this->userModel->find($customerId);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Pelanggan tidak ditemukan');
        }

        $data = [
            'customer' => $customer,
            'orders' => $this->orderModel->where('user_id', $customerId)->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/customer_detail', $data);
    }

    public function reports()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'orders' => $this->orderModel->where('DATE(orders.created_at) >=', $startDate)
                                         ->where('DATE(orders.created_at) <=', $endDate)
                                         ->where('orders.status', 'completed')
                                         ->getOrdersWithUser(),
            'totalRevenue' => $this->orderModel->where('DATE(orders.created_at) >=', $startDate)
                                                ->where('DATE(orders.created_at) <=', $endDate)
                                                ->where('orders.status', 'completed')
                                                ->select('SUM(orders.total_price) as total')
                                                ->first(),
        ];

        return view('admin/reports', $data);
    }
}
