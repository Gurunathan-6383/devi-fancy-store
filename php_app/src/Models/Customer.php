<?php
namespace App\Models;

class Customer
{
    public static function findByEmail($email)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM customers WHERE email = ?", [$email]);
    }

    public static function findById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT id, name, email, phone, created_at FROM customers WHERE id = ?", [$id]);
    }

    public static function create($data)
    {
        $db = \App\Core\Database::getInstance();
        $id = $db->insert(
            "INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['phone'] ?? null, $data['password']]
        );
        return $id;
    }
}
