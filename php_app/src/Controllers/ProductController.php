<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Product;

class ProductController
{
    public function getAll(Request $request)
    {
        $products = Product::findAll();
        Response::success($products);
    }

    public function getActive(Request $request)
    {
        $products = Product::getActive();
        Response::success($products);
    }

    public function getById(Request $request)
    {
        $id = $request->param('id');
        $product = Product::getById($id);
        if (!$product) {
            Response::error('Product not found', 404);
        }
        Response::success($product);
    }

    public function getBySlug(Request $request)
    {
        $slug = $request->param('slug');
        $product = Product::getBySlug($slug);
        if (!$product) {
            Response::error('Product not found', 404);
        }
        Response::success($product);
    }

    public function create(Request $request)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $category_id = $request->input('category_id');

        if (!$name || !$price || !$category_id) {
            Response::error('Name, price and category_id are required', 400);
        }

        $data = [
            'name' => $name,
            'price' => $price,
            'category_id' => $category_id,
            'description' => $request->input('description', ''),
            'specifications' => $request->input('specifications', ''),
            'offer_price' => $request->input('offer_price'),
            'stock' => $request->input('stock', 0),
            'status' => $request->input('status', 'active'),
            'is_featured' => $request->input('is_featured', false),
        ];

        $images = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (is_array($files['name'])) {
                foreach ($files['name'] as $i => $name) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp = $files['tmp_name'][$i];
                        $url = cloudinary_upload($tmp);
                        if (!$url) {
                            $ext = pathinfo($name, PATHINFO_EXTENSION);
                            $filename = uniqid() . '.' . $ext;
                            $dest = __DIR__ . '/../../public/uploads/' . $filename;
                            move_uploaded_file($tmp, $dest);
                            $url = '/public/uploads/' . $filename;
                        }
                        $images[] = $url;
                    }
                }
            } else {
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $tmp = $files['tmp_name'];
                    $url = cloudinary_upload($tmp);
                    if (!$url) {
                        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $ext;
                        $dest = __DIR__ . '/../../public/uploads/' . $filename;
                        move_uploaded_file($tmp, $dest);
                        $url = '/public/uploads/' . $filename;
                    }
                    $images[] = $url;
                }
            }
        }
        if (!empty($images)) {
            $data['images'] = json_encode($images);
        }

        $id = Product::create($data);
        $product = Product::getById($id);
        Response::success($product, 'Product created', 201);
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $product = Product::getById($id);
        if (!$product) {
            Response::error('Product not found', 404);
        }

        $data = [];
        foreach (['name', 'price', 'category_id', 'description', 'specifications', 'offer_price', 'stock', 'status', 'is_featured'] as $field) {
            $val = $request->input($field);
            if ($val !== null) {
                $data[$field] = $val;
            }
        }

        $existingImages = [];
        $existingImagesInput = $request->input('existing_images');
        if ($existingImagesInput) {
            $existingImages = is_string($existingImagesInput) ? json_decode($existingImagesInput, true) : $existingImagesInput;
        }

        $newImages = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (is_array($files['name'])) {
                foreach ($files['name'] as $i => $name) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp = $files['tmp_name'][$i];
                        $url = cloudinary_upload($tmp);
                        if (!$url) {
                            $ext = pathinfo($name, PATHINFO_EXTENSION);
                            $filename = uniqid() . '.' . $ext;
                            $dest = __DIR__ . '/../../public/uploads/' . $filename;
                            move_uploaded_file($tmp, $dest);
                            $url = '/public/uploads/' . $filename;
                        }
                        $newImages[] = $url;
                    }
                }
            } else {
                if ($files['error'] === UPLOAD_ERR_OK) {
                    $tmp = $files['tmp_name'];
                    $url = cloudinary_upload($tmp);
                    if (!$url) {
                        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $ext;
                        $dest = __DIR__ . '/../../public/uploads/' . $filename;
                        move_uploaded_file($tmp, $dest);
                        $url = '/public/uploads/' . $filename;
                    }
                    $newImages[] = $url;
                }
            }
        }

        $allImages = array_merge($existingImages, $newImages);
        if (!empty($allImages)) {
            $data['images'] = json_encode($allImages);
        }

        Product::update($id, $data);
        $product = Product::getById($id);
        Response::success($product, 'Product updated');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $product = Product::getById($id);
        if (!$product) {
            Response::error('Product not found', 404);
        }
        Product::delete($id);
        Response::success(null, 'Product deleted');
    }

    public function getFeatured(Request $request)
    {
        $products = Product::getFeatured();
        Response::success($products);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'newest');

        $products = Product::search($query, $categoryId, $minPrice, $maxPrice, $sort);
        Response::success($products);
    }
}
