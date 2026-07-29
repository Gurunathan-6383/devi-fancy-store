<?php
namespace App\Models;

class User
{
    public static function findByEmail($email)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function findById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT id, email, created_at FROM users WHERE id = ?", [$id]);
    }
}
