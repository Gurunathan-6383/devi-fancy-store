<?php
namespace App\Models;

class ContentPage
{
    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT id, slug, title, meta_description, is_active, created_at, updated_at FROM content_pages ORDER BY id ASC");
    }

    public static function getBySlug($slug)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM content_pages WHERE slug = ?", [$slug]);
    }

    public static function getById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM content_pages WHERE id = ?", [$id]);
    }

    public static function create($data)
    {
        $db = \App\Core\Database::getInstance();
        $id = $db->insert(
            "INSERT INTO content_pages (slug, title, content, meta_description, is_active) VALUES (?, ?, ?, ?, ?)",
            [
                $data['slug'], $data['title'], $data['content'],
                $data['meta_description'] ?? null,
                isset($data['is_active']) ? $data['is_active'] : 1
            ]
        );
        return self::getById($id);
    }

    public static function update($id, $data)
    {
        $db = \App\Core\Database::getInstance();
        $fields = [];
        $values = [];

        if (isset($data['title'])) {
            $fields[] = 'title = ?';
            $values[] = $data['title'];
        }
        if (isset($data['content'])) {
            $fields[] = 'content = ?';
            $values[] = $data['content'];
        }
        if (isset($data['meta_description'])) {
            $fields[] = 'meta_description = ?';
            $values[] = $data['meta_description'] ?? null;
        }
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = ?';
            $values[] = $data['is_active'] ? 1 : 0;
        }

        if (empty($fields)) {
            return null;
        }
        $values[] = $id;
        $db->query("UPDATE content_pages SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        return self::getById($id);
    }

    public static function delete($id)
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM content_pages WHERE id = ?", [$id]);
        return true;
    }
}
