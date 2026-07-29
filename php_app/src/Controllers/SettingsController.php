<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

class SettingsController
{
    private $allowedKeys = ['store_name', 'logo', 'phone', 'email', 'address', 'theme'];

    public function getAll(Request $request)
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        Response::success($settings);
    }

    public function update(Request $request)
    {
        $db = Database::getInstance();
        $data = $request->all();

        $logo = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tmp = $file['tmp_name'];
                $logo = cloudinary_upload($tmp);
                if (!$logo) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $ext;
                    $dest = __DIR__ . '/../../public/uploads/' . $filename;
                    move_uploaded_file($tmp, $dest);
                    $logo = '/public/uploads/' . $filename;
                }
            }
        }
        if ($logo) {
            $data['logo'] = $logo;
        }

        foreach ($data as $key => $value) {
            if (in_array($key, $this->allowedKeys)) {
                $existing = $db->fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
                if ($existing) {
                    $db->update("UPDATE settings SET setting_value = ? WHERE setting_key = ?", [$value, $key]);
                } else {
                    $db->insert("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)", [$key, $value]);
                }
            }
        }

        $rows = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        Response::success($settings, 'Settings updated');
    }

    public function getPublic(Request $request)
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('store_name', 'logo', 'phone', 'email', 'address')");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        Response::success($settings);
    }
}
