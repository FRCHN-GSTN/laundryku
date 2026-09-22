<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Throttle implements FilterInterface
{
    /**
     * Rate limit rules: [max_requests, time_window_in_seconds]
     *
     * @var array<string, array{0: int, 1: int}>
     */
    protected array $rules = [
        'auth/attempt'  => [5, 60],    // 5 login attempts per minute
        'auth/register' => [3, 300],   // 3 registrations per 5 minutes
        'order/create'  => [5, 60],    // 5 orders per minute
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $path = $request->getUri()->getPath();
        $ip = $request->getIPAddress();

        foreach ($this->rules as $route => $limits) {
            if (str_starts_with($path, $route)) {
                $maxAttempts = $limits[0];
                $window = $limits[1];
                $key = 'throttle_' . md5($path . '_' . $ip);

                $cache = \Config\Services::cache();
                $attempts = (int) $cache->get($key);

                if ($attempts >= $maxAttempts) {
                    $retryAfter = $cache->getMetadata($key)['expire'] ?? $window;
                    return $this->show429($request, $window, $maxAttempts);
                }

                $cache->save($key, $attempts + 1, $window);
                break;
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }

    private function show429(RequestInterface $request, int $window, int $maxAttempts): ResponseInterface
    {
        if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            $response = \Config\Services::response();
            return $response->setJSON([
                'error' => 'Terlalu banyak percobaan. Silakan tunggu ' . $window . ' detik.',
                'retry_after' => $window,
            ])->setStatusCode(429);
        }

        return redirect()->back()->with('error', 'Terlalu banyak percobaan. Silakan tunggu ' . $window . ' detik sebelum mencoba lagi.');
    }
}
