<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $isLoggedIn = $session->get('logged_in');
        $uri = $request->getUri()->getPath();

        if (!$isLoggedIn) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userRole = $session->get('user_role');

        // Admin routes: only admin
        if (strpos($uri, '/admin') === 0) {
            if ($userRole !== 'admin') {
                return redirect()->to('/customer/dashboard')->with('error', 'Akses ditolak. Hanya untuk admin.');
            }
        }

        // Customer routes: only customer
        if (strpos($uri, '/customer') === 0) {
            if ($userRole !== 'customer') {
                return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak. Hanya untuk pelanggan.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
