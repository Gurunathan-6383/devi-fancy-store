<?php
namespace App\Models;

class Category
{
    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT * FROM categories ORDER BY name ASC");
    }

    public static function getActive()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT * FROM categories WHERE is_hidden = FALSE ORDER BY name ASC");
    }

    public static function getById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public static function getBySlug($slug)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public static function create($name, $image = null)
    {
        $db = \App\Core\Database::getInstance();
        $slug = slugify($name);
        $id = $db->insert(
            "INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)",
            [$name, $slug, $image]
        );
        return self::getById($id);
    }

    public static function update($id, $data)
    {
        $db = \App\Core\Database::getInstance();
        $fields = [];
        $values = [];
        if (isset($data['name'])) {
            $slug = slugify($data['name']);
            $fields[] = 'name = ?';
            $values[] = $data['name'];
            $fields[] = 'slug = ?';
            $values[] = $slug;
        }
        if (isset($data['image'])) {
            $fields[] = 'image = ?';
            $values[] = $data['image'];
        }
        if (isset($data['is_hidden'])) {
            $fields[] = 'is_hidden = ?';
            $values[] = $data['is_hidden'];
        }
        if (empty($fields)) {
            return null;
        }
        $values[] = $id;
        $db->query("UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        return self::getById($id);
    }

    public static function delete($id)
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM categories WHERE id = ?", [$id]);
        return true;
    }

    public static function toggleVisibility($id)
    {
        $db = \App\Core\Database::getInstance();
        $category = self::getById($id);
        if (!$category) {
            return null;
        }
        $newHidden = !$category->is_hidden;
        $db->query("UPDATE categories SET is_hidden = ? WHERE id = ?", [$newHidden, $id]);
        $category->is_hidden = $newHidden;
        return $category;
    }
}
