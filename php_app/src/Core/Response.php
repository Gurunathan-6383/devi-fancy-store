<?php
namespace App\Core;

class Response
{
    public static function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($data = null, $message = 'Success', $status = 200)
    {
        return self::json(['success' => true, 'data' => $data, 'message' => $message], $status);
    }

    public static function error($message = 'Error', $status = 400, $errors = null)
    {
        $response = ['success' => false, 'message' => $message];
        if ($errors) $response['errors'] = $errors;
        return self::json($response, $status);
    }

    public static function redirect($path)
    {
        $base = rtrim(env('APP_URL', '/devi/php_app'), '/');
        header('Location: ' . $base . $path);
        exit;
    }

    public static function html($content)
    {
        echo $content;
        exit;
    }
}
