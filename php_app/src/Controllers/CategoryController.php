<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Category;

class CategoryController
{
    public function getAll(Request $request)
    {
        $categories = Category::findAll();
        Response::success($categories);
    }

    public function getActive(Request $request)
    {
        $categories = Category::getActive();
        Response::success($categories);
    }

    public function getById(Request $request)
    {
        $id = $request->param('id');
        $category = Category::getById($id);
        if (!$category) {
            Response::error('Category not found', 404);
        }
        Response::success($category);
    }

    public function create(Request $request)
    {
        $name = $request->input('name');
        if (!$name) {
            Response::error('Category name is required', 400);
        }

        $data = ['name' => $name];

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

        $id = Category::create($data);
        $category = Category::getById($id);
        Response::success($category, 'Category created', 201);
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $category = Category::getById($id);
        if (!$category) {
            Response::error('Category not found', 404);
        }

        $data = [];
        $name = $request->input('name');
        if ($name) {
            $data['name'] = $name;
        }

        $is_hidden = $request->input('is_hidden');
        if ($is_hidden !== null) {
            $data['is_hidden'] = $is_hidden;
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

        Category::update($id, $data);
        $category = Category::getById($id);
        Response::success($category, 'Category updated');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $category = Category::getById($id);
        if (!$category) {
            Response::error('Category not found', 404);
        }
        Category::delete($id);
        Response::success(null, 'Category deleted');
    }

    public function toggleVisibility(Request $request)
    {
        $id = $request->param('id');
        $category = Category::getById($id);
        if (!$category) {
            Response::error('Category not found', 404);
        }
        Category::toggleVisibility($id);
        $category = Category::getById($id);
        Response::success($category, 'Visibility toggled');
    }
}
