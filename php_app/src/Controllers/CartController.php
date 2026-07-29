<?php
namespace App\Controllers;

use App\Core\Response;

class CartController
{
    public function add()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;
        $quantity = max(1, (int)($input['quantity'] ?? 1));

        if (!$productId) {
            Response::error('product_id is required', 400);
        }

        $cart = $this->getCart();
        $found = false;
        foreach ($cart as &$item) {
            if (($item['id'] ?? null) == $productId) {
                $item['quantity'] = ($item['quantity'] ?? 0) + $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $product = $this->fetchProduct($productId);
            if (!$product) {
                Response::error('Product not found', 404);
            }
            $cart[] = [
                'id' => (int)$productId,
                'name' => $product['name'] ?? '',
                'price' => (float)($product['price'] ?? 0),
                'offer_price' => (float)($product['offer_price'] ?? 0),
                'image' => $this->getFirstImage($product),
                'slug' => $product['slug'] ?? '',
                'quantity' => $quantity,
            ];
        }

        $this->saveCart($cart);
        Response::success(['cart_count' => count($cart)], 'Added to cart');
    }

    public function update()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;
        $delta = (int)($input['delta'] ?? 0);

        if (!$productId) {
            Response::error('product_id is required', 400);
        }

        $cart = $this->getCart();
        foreach ($cart as $i => &$item) {
            if (($item['id'] ?? null) == $productId) {
                $item['quantity'] = max(0, ($item['quantity'] ?? 0) + $delta);
                if ($item['quantity'] <= 0) {
                    array_splice($cart, $i, 1);
                }
                break;
            }
        }

        $this->saveCart($cart);
        Response::success(['cart_count' => count($cart)], 'Cart updated');
    }

    public function remove()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = $input['product_id'] ?? null;

        if (!$productId) {
            Response::error('product_id is required', 400);
        }

        $cart = $this->getCart();
        foreach ($cart as $i => $item) {
            if (($item['id'] ?? null) == $productId) {
                array_splice($cart, $i, 1);
                break;
            }
        }

        $this->saveCart($cart);
        Response::success(['cart_count' => count($cart)], 'Removed from cart');
    }

    private function getCart()
    {
        $raw = $_COOKIE['devi_cart'] ?? '[]';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function saveCart($cart)
    {
        $encoded = json_encode(array_values($cart));
        setcookie('devi_cart', $encoded, time() + 86400 * 30, '/');
        $_COOKIE['devi_cart'] = $encoded;
    }

    private function fetchProduct($id)
    {
        $db = \App\Core\Database::getInstance();
        $row = $db->fetch("SELECT * FROM products WHERE id = ?", [$id]);
        return $row ? (array)$row : null;
    }

    private function getFirstImage($product)
    {
        $images = $product['images'] ?? [];
        if (is_string($images)) $images = json_decode($images, true) ?: [];
        return $images[0] ?? '';
    }
}
