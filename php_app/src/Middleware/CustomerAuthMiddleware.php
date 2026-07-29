<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;

class CustomerAuthMiddleware extends Middleware
{
    public function handle(Request $request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['customer_user'])) {
            $token = $this->extractToken();
            if ($token) {
                $customer = $this->verifyToken($token);
                if ($customer) {
                    $_SESSION['customer_user'] = $customer;
                    return true;
                }
            }
            if ($request->wantsJson()) {
                \App\Core\Response::error('Unauthorized', 401);
            }
            $_SESSION['redirect_after_login'] = $request->path();
            \App\Core\Response::redirect('/login');
            return false;
        }
        return true;
    }

    private function extractToken()
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (strpos($header, 'Bearer ') === 0) {
            return substr($header, 7);
        }
        return null;
    }

    private function verifyToken($token)
    {
        try {
            $payload = \App\Core\JWT::decode($token, env('JWT_SECRET'));
            if (($payload->type ?? '') !== 'customer') return null;
            $db = \App\Core\Database::getInstance();
            return $db->fetch("SELECT id, name, email, phone, created_at FROM customers WHERE id = ?", [$payload->id]);
        } catch (\Exception $e) {
            return null;
        }
    }
}
