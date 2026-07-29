<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin_user'])) {
            $token = $this->extractToken();
            if ($token) {
                $user = $this->verifyToken($token);
                if ($user) {
                    $_SESSION['admin_user'] = $user;
                    return true;
                }
            }
            if ($request->wantsJson()) {
                \App\Core\Response::error('Unauthorized', 401);
            }
            \App\Core\Response::redirect('/admin/login');
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
            if ($payload->type ?? '' === 'customer') return null;
            $db = \App\Core\Database::getInstance();
            $user = $db->fetch("SELECT id, email, created_at FROM users WHERE id = ?", [$payload->id]);
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
}
