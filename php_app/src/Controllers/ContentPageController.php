<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\ContentPage;

class ContentPageController
{
    public function getAll(Request $request)
    {
        $pages = ContentPage::findAll();
        Response::success($pages);
    }

    public function getBySlug(Request $request)
    {
        $slug = $request->param('slug');
        $page = ContentPage::getBySlug($slug);
        if (!$page) {
            Response::error('Page not found', 404);
        }
        Response::success($page);
    }

    public function getById(Request $request)
    {
        $id = $request->param('id');
        $page = ContentPage::getById($id);
        if (!$page) {
            Response::error('Page not found', 404);
        }
        Response::success($page);
    }

    public function create(Request $request)
    {
        $slug = $request->input('slug');
        $title = $request->input('title');
        $content = $request->input('content');

        if (!$slug || !$title || !$content) {
            Response::error('Slug, title and content are required', 400);
        }

        $data = [
            'slug' => $slug,
            'title' => $title,
            'content' => $content,
            'meta_description' => $request->input('meta_description', ''),
            'is_active' => $request->input('is_active', true),
        ];

        $id = ContentPage::create($data);
        $page = ContentPage::getById($id);
        Response::success($page, 'Content page created', 201);
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $page = ContentPage::getById($id);
        if (!$page) {
            Response::error('Content page not found', 404);
        }

        $data = [];
        foreach (['slug', 'title', 'content', 'meta_description', 'is_active'] as $field) {
            $val = $request->input($field);
            if ($val !== null) {
                $data[$field] = $val;
            }
        }

        ContentPage::update($id, $data);
        $page = ContentPage::getById($id);
        Response::success($page, 'Content page updated');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $page = ContentPage::getById($id);
        if (!$page) {
            Response::error('Content page not found', 404);
        }
        ContentPage::delete($id);
        Response::success(null, 'Content page deleted');
    }
}
