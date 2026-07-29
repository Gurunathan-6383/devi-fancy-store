<?php
namespace App\Models;

class Catalogue
{
    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT * FROM catalogues ORDER BY created_at DESC");
    }

    public static function getPublished()
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll("SELECT * FROM catalogues WHERE is_published = TRUE ORDER BY created_at DESC");
    }

    public static function getById($id)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM catalogues WHERE id = ?", [$id]);
    }

    public static function getBySlug($slug)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch("SELECT * FROM catalogues WHERE slug = ?", [$slug]);
    }

    public static function create($data)
    {
        $db = \App\Core\Database::getInstance();
        $slug = slugify($data['title']);
        $id = $db->insert(
            "INSERT INTO catalogues (title, slug, description, image) VALUES (?, ?, ?, ?)",
            [$data['title'], $slug, $data['description'] ?? null, $data['image'] ?? null]
        );
        return self::getById($id);
    }

    public static function update($id, $data)
    {
        $db = \App\Core\Database::getInstance();
        $fields = [];
        $values = [];

        if (isset($data['title'])) {
            $slug = slugify($data['title']);
            $fields[] = 'title = ?';
            $values[] = $data['title'];
            $fields[] = 'slug = ?';
            $values[] = $slug;
        }
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }
        if (isset($data['image'])) {
            $fields[] = 'image = ?';
            $values[] = $data['image'];
        }
        if (isset($data['is_published'])) {
            $fields[] = 'is_published = ?';
            $values[] = $data['is_published'];
        }

        if (empty($fields)) {
            return null;
        }
        $values[] = $id;
        $db->query("UPDATE catalogues SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        return self::getById($id);
    }

    public static function delete($id)
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM catalogues WHERE id = ?", [$id]);
        return true;
    }

    public static function togglePublish($id)
    {
        $db = \App\Core\Database::getInstance();
        $catalogue = self::getById($id);
        if (!$catalogue) {
            return null;
        }
        $newPublished = !$catalogue['is_published'];
        $db->query("UPDATE catalogues SET is_published = ? WHERE id = ?", [$newPublished, $id]);
        $catalogue['is_published'] = $newPublished;
        return $catalogue;
    }

    public static function getProducts($id)
    {
        $db = \App\Core\Database::getInstance();
        $rows = $db->fetchAll(
            "SELECT p.*, c.name as category_name
             FROM catalogue_products cp
             JOIN products p ON cp.product_id = p.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE cp.catalogue_id = ?
             ORDER BY p.name ASC",
            [$id]
        );
        return array_map(function ($r) {
            $r['images'] = $r['images'] ? json_decode($r['images'], true) : [];
            return $r;
        }, $rows);
    }

    public static function addProduct($catalogueId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $db->query(
            "INSERT IGNORE INTO catalogue_products (catalogue_id, product_id) VALUES (?, ?)",
            [$catalogueId, $productId]
        );
        return true;
    }

    public static function removeProduct($catalogueId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $db->query(
            "DELETE FROM catalogue_products WHERE catalogue_id = ? AND product_id = ?",
            [$catalogueId, $productId]
        );
        return true;
    }
}
