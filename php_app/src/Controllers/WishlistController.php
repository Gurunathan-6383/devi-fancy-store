<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Wishlist;

class WishlistController
{
    public function getAll(Request $request)
    {
        $customerId = $_SESSION['customer_user']->id ?? null;
        if (!$customerId) {
            Response::error('Not authenticated', 401);
        }
        $items = Wishlist::findByCustomer($customerId);
        Response::success($items);
    }

    public function getIds(Request $request)
    {
        $customerId = $_SESSION['customer_user']->id ?? null;
        if (!$customerId) {
            Response::error('Not authenticated', 401);
        }
        $ids = Wishlist::findByCustomerIds($customerId);
        Response::success($ids);
    }

    public function toggle(Request $request)
    {
        $customerId = $_SESSION['customer_user']->id ?? null;
        if (!$customerId) {
            Response::error('Not authenticated', 401);
        }
        $productId = $request->input('product_id');
        if (!$productId) {
            Response::error('product_id is required', 400);
        }
        $result = Wishlist::toggle($customerId, $productId);
        Response::success(['added' => $result], $result ? 'Added to wishlist' : 'Removed from wishlist');
    }
}
