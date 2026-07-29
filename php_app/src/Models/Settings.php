<?php
namespace App\Models;

class Settings
{
    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        $rows = $db->fetchAll("SELECT * FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public static function get($key)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $row ? $row['setting_value'] : null;
    }

    public static function set($key, $value)
    {
        $db = \App\Core\Database::getInstance();
        $db->query(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?",
            [$key, $value, $value]
        );
        return true;
    }

    public static function updateMultiple($settings)
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
        return self::getAll();
    }
}
