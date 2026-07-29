<?php
namespace App\Models;

class Review
{
    public static function getByProduct($productId)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetchAll(
            "SELECT r.id, r.rating, r.comment, r.created_at, c.name as customer_name
             FROM reviews r
             JOIN customers c ON r.customer_id = c.id
             WHERE r.product_id = ?
             ORDER BY r.created_at DESC",
            [$productId]
        );
    }

    public static function getStats($productId)
    {
        $db = \App\Core\Database::getInstance();
        return $db->fetch(
            "SELECT COUNT(*) as count, COALESCE(AVG(rating), 0) as avg_rating FROM reviews WHERE product_id = ?",
            [$productId]
        );
    }

    public static function create($customerId, $productId, $rating, $comment = null)
    {
        $db = \App\Core\Database::getInstance();
        $id = $db->insert(
            "INSERT INTO reviews (customer_id, product_id, rating, comment) VALUES (?, ?, ?, ?)",
            [$customerId, $productId, $rating, $comment]
        );
        return (object) ['id' => $id, 'rating' => $rating, 'comment' => $comment];
    }

    public static function hasReviewed($customerId, $productId)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch(
            "SELECT id FROM reviews WHERE customer_id = ? AND product_id = ?",
            [$customerId, $productId]
        );
        return (bool) $row;
    }
}
