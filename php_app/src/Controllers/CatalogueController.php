<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Catalogue;

class CatalogueController
{
    public function getAll(Request $request)
    {
        $catalogues = Catalogue::findAll();
        Response::success($catalogues);
    }

    public function getPublished(Request $request)
    {
        $catalogues = Catalogue::getPublished();
        Response::success($catalogues);
    }

    public function getById(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        Response::success($catalogue);
    }

    public function getBySlug(Request $request)
    {
        $slug = $request->param('slug');
        $catalogue = Catalogue::getBySlug($slug);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        $catalogue['products'] = Catalogue::getProducts($catalogue['id']);
        Response::success($catalogue);
    }

    public function create(Request $request)
    {
        $title = $request->input('title');
        if (!$title) {
            Response::error('Catalogue title is required', 400);
        }

        $data = [
            'title' => $title,
            'description' => $request->input('description', ''),
        ];

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tmp = $file['tmp_name'];
                $image = cloudinary_upload($tmp);
                if (!$image) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $ext;
                    $dest = __DIR__ . '/../../public/uploads/' . $filename;
                    move_uploaded_file($tmp, $dest);
                    $image = '/public/uploads/' . $filename;
                }
            }
        }
        if ($image) {
            $data['image'] = $image;
        }

        $id = Catalogue::create($data);
        $catalogue = Catalogue::getById($id);
        Response::success($catalogue, 'Catalogue created', 201);
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }

        $data = [];
        $title = $request->input('title');
        if ($title) {
            $data['title'] = $title;
        }
        $description = $request->input('description');
        if ($description !== null) {
            $data['description'] = $description;
        }

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tmp = $file['tmp_name'];
                $image = cloudinary_upload($tmp);
                if (!$image) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $ext;
                    $dest = __DIR__ . '/../../public/uploads/' . $filename;
                    move_uploaded_file($tmp, $dest);
                    $image = '/public/uploads/' . $filename;
                }
            }
        }
        if ($image) {
            $data['image'] = $image;
        }

        Catalogue::update($id, $data);
        $catalogue = Catalogue::getById($id);
        Response::success($catalogue, 'Catalogue updated');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        Catalogue::delete($id);
        Response::success(null, 'Catalogue deleted');
    }

    public function togglePublish(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        Catalogue::togglePublish($id);
        $catalogue = Catalogue::getById($id);
        Response::success($catalogue, 'Publish status toggled');
    }

    public function addProduct(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        $productId = $request->input('product_id');
        if (!$productId) {
            Response::error('product_id is required', 400);
        }
        Catalogue::addProduct($id, $productId);
        Response::success(null, 'Product added to catalogue');
    }

    public function removeProduct(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        $productId = $request->param('productId');
        Catalogue::removeProduct($id, $productId);
        Response::success(null, 'Product removed from catalogue');
    }

    public function getProducts(Request $request)
    {
        $id = $request->param('id');
        $catalogue = Catalogue::getById($id);
        if (!$catalogue) {
            Response::error('Catalogue not found', 404);
        }
        $products = Catalogue::getProducts($id);
        Response::success($products);
    }
}
