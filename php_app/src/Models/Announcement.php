<?php
namespace App\Models;

class Announcement
{
    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT * FROM announcements ORDER BY priority DESC, created_at DESC");
    }

    public static function getActive()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM announcements
             WHERE status = 'active'
               AND (start_date IS NULL OR start_date <= NOW())
               AND (end_date IS NULL OR end_date >= NOW())
             ORDER BY priority DESC, created_at ASC"
        );
    }

    public static function getById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM announcements WHERE id = ?", [$id]);
    }

    public static function create($data)
    {
        $db = \App\Core\Database::getInstance();
        $id = $db->insert(
            "INSERT INTO announcements (title, message, type, status, bg_color, text_color, priority, start_date, end_date, redirect_url)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'], $data['message'], $data['type'] ?? 'general',
                $data['status'] ?? 'active', $data['bg_color'] ?? '#e04a6f',
                $data['text_color'] ?? '#ffffff', $data['priority'] ?? 0,
                $data['start_date'] ?? null, $data['end_date'] ?? null,
                $data['redirect_url'] ?? null
            ]
        );
        return self::getById($id);
    }

    public static function update($id, $data)
    {
        $db = \App\Core\Database::getInstance();
        $fields = [];
        $values = [];
        $allowed = ['title', 'message', 'type', 'status', 'bg_color', 'text_color', 'priority', 'start_date', 'end_date', 'redirect_url'];

        foreach ($allowed as $key) {
            if (isset($data[$key])) {
                $fields[] = "{$key} = ?";
                $values[] = $data[$key] === '' ? null : $data[$key];
            }
        }

        if (empty($fields)) {
            return null;
        }
        $values[] = $id;
        $db->query("UPDATE announcements SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        return self::getById($id);
    }

    public static function delete($id)
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM announcements WHERE id = ?", [$id]);
        return true;
    }

    public static function toggleStatus($id)
    {
        $db = \App\Core\Database::getInstance();
        $item = self::getById($id);
        if (!$item) {
            return null;
        }
        $newStatus = $item->status === 'active' ? 'inactive' : 'active';
        $db->query("UPDATE announcements SET status = ? WHERE id = ?", [$newStatus, $id]);
        $item->status = $newStatus;
        return $item;
    }
}
