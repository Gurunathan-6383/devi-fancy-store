<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Review;

class ReviewController
{
    public function getByProduct(Request $request)
    {
        $productId = $request->param('productId');
        if (!$productId) {
            Response::error('Product ID is required', 400);
        }
        $reviews = Review::getByProduct($productId);
        $stats = Review::getStats($productId);
        Response::success([
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }

    public function create(Request $request)
    {
        $customerId = $_SESSION['customer_user']['id'] ?? null;
        if (!$customerId) {
            Response::error('Not authenticated', 401);
        }

        $productId = $request->input('product_id');
        $rating = $request->input('rating');

        if (!$productId) {
            Response::error('product_id is required', 400);
        }
        if (!$rating || $rating < 1 || $rating > 5) {
            Response::error('Rating must be between 1 and 5', 400);
        }

        $existing = Review::hasReviewed($customerId, $productId);
        if ($existing) {
            Response::error('You have already reviewed this product', 400);
        }

        $review = Review::create($customerId, $productId, (int)$rating, $request->input('comment', ''));
        Response::success($review, 'Review created', 201);
    }
}
