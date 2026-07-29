<?php
namespace App\Models;

class Wishlist
{
    public static function getByCustomer($customerId)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT w.id, w.product_id, p.name, p.slug, p.price, p.offer_price, p.images, p.stock, c.name as category_name
             FROM wishlists w
             JOIN products p ON w.product_id = p.id
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE w.customer_id = ?
             ORDER BY w.created_at DESC",
            [$customerId]
        );
    }

    public static function add($customerId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query(
            "INSERT IGNORE INTO wishlists (customer_id, product_id) VALUES (?, ?)",
            [$customerId, $productId]
        );
        return $stmt->rowCount() > 0;
    }

    public static function remove($customerId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query(
            "DELETE FROM wishlists WHERE customer_id = ? AND product_id = ?",
            [$customerId, $productId]
        );
        return $stmt->rowCount() > 0;
    }

    public static function isInWishlist($customerId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch(
            "SELECT id FROM wishlists WHERE customer_id = ? AND product_id = ?",
            [$customerId, $productId]
        );
        return (bool) $row;
    }

    public static function getIds($customerId)
    {
        $db = \App\Core\Database::getInstance();
        $rows = $db->fetchAll("SELECT product_id FROM wishlists WHERE customer_id = ?", [$customerId]);
        return array_map(function ($r) {
            return $r['product_id'];
        }, $rows);
    }
}
