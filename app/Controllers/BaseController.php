<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Controller;
use Psr\Http\Message\ServerRequestInterface;

class BaseController extends Controller
{
    protected $request;
    protected $response;

    public function initController(RequestInterface $request, ResponseInterface $response, $logger)
    {
        $this->request = $request;
        $this->response = $response;
        parent::initController($request, $response, $logger);
    }

    protected function isLoggedIn()
    {
        return session()->get('logged_in') === true;
    }

    protected function isAdmin()
    {
        return session()->get('user_role') === 'admin';
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    protected function requireAdmin()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        if (!$this->isAdmin()) {
            return redirect()->to('/customer/dashboard')->with('error', 'Akses ditolak');
        }
        return null;
    }
}
