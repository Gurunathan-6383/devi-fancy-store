<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\JWT;
use App\Models\Customer;

class CustomerAuthController
{
    public function signup(Request $request)
    {
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json) {
            $_POST = array_merge($_POST, $json);
        }
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (!$name || !$email || !$password) {
            Response::error('Name, email and password are required', 400);
        }

        if (strlen($password) < 6) {
            Response::error('Password must be at least 6 characters', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error('Invalid email format', 400);
        }

        $existing = Customer::findByEmail($email);
        if ($existing) {
            Response::error('Email already registered', 400);
        }

        $customerId = Customer::create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $customer = Customer::findById($customerId);

        $token = JWT::encode([
            'id' => $customer['id'],
            'email' => $customer['email'],
            'type' => 'customer',
        ], env('JWT_SECRET'));

        $_SESSION['customer_user'] = $customer;

        Response::success([
            'token' => $token,
            'customer' => [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'] ?? '',
                'created_at' => $customer['created_at'],
            ],
        ], 'Signup successful', 201);
    }

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

        $customer = Customer::findByEmail($email);
        if (!$customer || !password_verify($password, $customer['password'])) {
            Response::error('Invalid credentials', 401);
        }

        $token = JWT::encode([
            'id' => $customer['id'],
            'email' => $customer['email'],
            'type' => 'customer',
        ], env('JWT_SECRET'));

        $_SESSION['customer_user'] = $customer;

        Response::success([
            'token' => $token,
            'customer' => [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'] ?? '',
                'created_at' => $customer['created_at'],
            ],
        ], 'Login successful');
    }

    public function verify(Request $request)
    {
        $customer = $_SESSION['customer_user'] ?? null;
        if (!$customer) {
            Response::error('Not authenticated', 401);
        }
        Response::success([
            'customer' => [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'] ?? '',
                'created_at' => $customer['created_at'],
            ],
        ]);
    }
}
