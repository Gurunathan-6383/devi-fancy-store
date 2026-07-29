<?php
namespace App\Models;

class Product
{
    private static function parseImages($row)
    {
        if ($row) {
            $row['images'] = $row['images'] ? json_decode($row['images'], true) : [];
        }
        return $row;
    }

    private static function parseImagesAll($rows)
    {
        return array_map(function ($r) {
            $r['images'] = $r['images'] ? json_decode($r['images'], true) : [];
            return $r;
        }, $rows);
    }

    private static function baseSelect()
    {
        return "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";
    }

    public static function getAll()
    {
        $db = \App\Core\Database::getInstance();
        $rows = $db->fetchAll(self::baseSelect() . " ORDER BY p.created_at DESC");
        return self::parseImagesAll($rows);
    }

    public static function getActive($filters = [])
    {
        $db = \App\Core\Database::getInstance();
        $sql = self::baseSelect() . " WHERE p.status = 'active'";
        $values = [];

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $values[] = $filters['category_id'];
        }
        if (!empty($filters['category_slug'])) {
            $sql .= " AND c.slug = ?";
            $values[] = $filters['category_slug'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $values[] = "%{$filters['search']}%";
            $values[] = "%{$filters['search']}%";
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND COALESCE(p.offer_price, p.price) >= ?";
            $values[] = (float) $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND COALESCE(p.offer_price, p.price) <= ?";
            $values[] = (float) $filters['max_price'];
        }
        if (!empty($filters['featured'])) {
            $sql .= " AND p.is_featured = TRUE";
        }

        $sql .= " ORDER BY p.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ?";
            $values[] = (int) $filters['limit'];
        }

        $rows = $db->fetchAll($sql, $values);
        return self::parseImagesAll($rows);
    }

    public static function getById($id)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch(self::baseSelect() . " WHERE p.id = ?", [$id]);
        return self::parseImages($row);
    }

    public static function getBySlug($slug)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch(self::baseSelect() . " WHERE p.slug = ?", [$slug]);
        return self::parseImages($row);
    }

    public static function create($data)
    {
        $db = \App\Core\Database::getInstance();
        $slug = slugify($data['name']);
        $images = isset($data['images']) ? json_encode($data['images']) : '[]';
        $id = $db->insert(
            "INSERT INTO products (name, slug, category_id, description, specifications, price, offer_price, stock, status, is_featured, images)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'], $slug, $data['category_id'], $data['description'] ?? null,
                $data['specifications'] ?? null, $data['price'], $data['offer_price'] ?? null,
                $data['stock'] ?? 0, $data['status'] ?? 'active', $data['is_featured'] ?? false, $images
            ]
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
        if (isset($data['category_id'])) {
            $fields[] = 'category_id = ?';
            $values[] = $data['category_id'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }
        if (isset($data['specifications'])) {
            $fields[] = 'specifications = ?';
            $values[] = $data['specifications'];
        }
        if (isset($data['price'])) {
            $fields[] = 'price = ?';
            $values[] = $data['price'];
        }
        if (isset($data['offer_price'])) {
            $fields[] = 'offer_price = ?';
            $values[] = $data['offer_price'];
        }
        if (isset($data['stock'])) {
            $fields[] = 'stock = ?';
            $values[] = $data['stock'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $values[] = $data['status'];
        }
        if (isset($data['is_featured'])) {
            $fields[] = 'is_featured = ?';
            $values[] = $data['is_featured'];
        }
        if (isset($data['images'])) {
            $fields[] = 'images = ?';
            $values[] = json_encode($data['images']);
        }

        if (empty($fields)) {
            return null;
        }
        $values[] = $id;
        $db->query("UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        return self::getById($id);
    }

    public static function delete($id)
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM products WHERE id = ?", [$id]);
        return true;
    }

    public static function getFeatured($limit = 8)
    {
        $db = \App\Core\Database::getInstance();
        $rows = $db->fetchAll(
            self::baseSelect() . " WHERE p.is_featured = TRUE AND p.status = 'active' ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        );
        return self::parseImagesAll($rows);
    }

    public static function search($query, $filters = [])
    {
        $db = \App\Core\Database::getInstance();
        $sql = self::baseSelect() . " WHERE p.status = 'active'";
        $values = [];

        if ($query) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $values[] = "%{$query}%";
            $values[] = "%{$query}%";
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $values[] = $filters['category_id'];
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND COALESCE(p.offer_price, p.price) >= ?";
            $values[] = (float) $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND COALESCE(p.offer_price, p.price) <= ?";
            $values[] = (float) $filters['max_price'];
        }

        $sortMap = [
            'price_low' => 'ORDER BY COALESCE(p.offer_price, p.price) ASC',
            'price_high' => 'ORDER BY COALESCE(p.offer_price, p.price) DESC',
            'newest' => 'ORDER BY p.created_at DESC',
            'name' => 'ORDER BY p.name ASC'
        ];
        $sql .= ' ' . ($sortMap[$filters['sort'] ?? ''] ?? 'ORDER BY p.created_at DESC');

        $rows = $db->fetchAll($sql, $values);
        return self::parseImagesAll($rows);
    }
}
