<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Announcement;

class AnnouncementController
{
    public function getAll(Request $request)
    {
        $announcements = Announcement::findAll();
        Response::success($announcements);
    }

    public function getActive(Request $request)
    {
        $announcements = Announcement::getActive();
        Response::success($announcements);
    }

    public function getById(Request $request)
    {
        $id = $request->param('id');
        $announcement = Announcement::getById($id);
        if (!$announcement) {
            Response::error('Announcement not found', 404);
        }
        Response::success($announcement);
    }

    public function create(Request $request)
    {
        $title = $request->input('title');
        $message = $request->input('message');

        if (!$title || !$message) {
            Response::error('Title and message are required', 400);
        }

        $data = [
            'title' => $title,
            'message' => $message,
            'type' => $request->input('type', 'general'),
            'status' => $request->input('status', 'active'),
            'bg_color' => $request->input('bg_color', '#e04a6f'),
            'text_color' => $request->input('text_color', '#ffffff'),
            'priority' => $request->input('priority', 0),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'redirect_url' => $request->input('redirect_url'),
        ];

        $id = Announcement::create($data);
        $announcement = Announcement::getById($id);
        Response::success($announcement, 'Announcement created', 201);
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $announcement = Announcement::getById($id);
        if (!$announcement) {
            Response::error('Announcement not found', 404);
        }

        $data = [];
        foreach (['title', 'message', 'type', 'status', 'bg_color', 'text_color', 'priority', 'start_date', 'end_date', 'redirect_url'] as $field) {
            $val = $request->input($field);
            if ($val !== null) {
                $data[$field] = $val;
            }
        }

        Announcement::update($id, $data);
        $announcement = Announcement::getById($id);
        Response::success($announcement, 'Announcement updated');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        $announcement = Announcement::getById($id);
        if (!$announcement) {
            Response::error('Announcement not found', 404);
        }
        Announcement::delete($id);
        Response::success(null, 'Announcement deleted');
    }

    public function toggleStatus(Request $request)
    {
        $id = $request->param('id');
        $announcement = Announcement::getById($id);
        if (!$announcement) {
            Response::error('Announcement not found', 404);
        }
        Announcement::toggleStatus($id);
        $announcement = Announcement::getById($id);
        Response::success($announcement, 'Status toggled');
    }
}
