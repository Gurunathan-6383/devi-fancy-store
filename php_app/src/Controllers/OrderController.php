<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\GoogleSheetsService;

class OrderController
{
    public function placeOrder(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $items = $request->input('items');
        $total = $request->input('total');

        if (!$name || !$phone || !$address || !$items || !$total) {
            Response::error('Name, phone, address, items and total are required', 400);
        }

        $itemsStr = is_array($items) ? json_encode($items) : $items;

        $orderData = [
            'order_date' => date('Y-m-d H:i:s'),
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'items' => $itemsStr,
            'total' => $total,
            'status' => 'pending',
        ];

        GoogleSheetsService::appendToSheet($orderData);

        Response::success($orderData, 'Order placed successfully', 201);
    }

    public function getAllOrders(Request $request)
    {
        $orders = GoogleSheetsService::getOrdersFromSheet();
        $formatted = [];
        foreach ($orders as $row) {
            $formatted[] = [
                'order_date' => $row['order_date'] ?? '',
                'name' => $row['name'] ?? '',
                'phone' => $row['phone'] ?? '',
                'address' => $row['address'] ?? '',
                'items' => isset($row['items']) ? (is_string($row['items']) ? json_decode($row['items'], true) : $row['items']) : [],
                'total' => $row['total'] ?? '',
                'status' => $row['status'] ?? 'pending',
            ];
        }
        Response::success($formatted);
    }
}
