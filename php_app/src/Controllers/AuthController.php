<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\JWT;
use App\Models\User;

class AuthController
{
    public function login(Request $request)
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json) {
            $_POST = array_merge($_POST, $json);
        }
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$email || !$password) {
            Response::error('Email and password are required', 400);
        }

        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            Response::error('Invalid credentials', 401);
        }

        $token = JWT::encode([
            'id' => $user['id'],
            'email' => $user['email'],
            'type' => 'admin',
        ], env('JWT_SECRET'));

        $_SESSION['admin_user'] = $user;

        Response::success([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'created_at' => $user['created_at'],
            ],
        ], 'Login successful');
    }

    public function verify(Request $request)
    {
        $user = $_SESSION['admin_user'] ?? null;
        if (!$user) {
            Response::error('Not authenticated', 401);
        }
        Response::success([
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'created_at' => $user['created_at'],
            ],
        ]);
    }
}
