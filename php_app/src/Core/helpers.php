<?php
function env($key, $default = null) {
    $value = getenv($key);
    if ($value !== false) return $value;
    if (isset($_ENV[$key])) return $_ENV[$key];
    static $env = null;
    if ($env === null) {
        $env = [];
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $env[trim($parts[0])] = trim($parts[1], " \t\n\r\0\x0B\"'");
                }
            }
        }
    }
    return $env[$key] ?? $default;
}

function view($view, $data = []) {
    extract($data);
    $view_path = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
    ob_start();
    if (file_exists($view_path)) {
        include $view_path;
    }
    return ob_get_clean();
}

function layout($layout, $content, $data = []) {
    $data['content'] = $content;
    return view('layouts.' . $layout, $data);
}

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect($path) {
    $base = rtrim(env('APP_URL', '/devi/php_app'), '/');
    header('Location: ' . $base . $path);
    exit;
}

function asset($path) {
    $base = rtrim(env('APP_URL', '/devi/php_app'), '/');
    return $base . '/public/' . ltrim($path, '/');
}

function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

function old($key, $default = '') {
    return $_SESSION['_old'][$key] ?? $default;
}

function slugify($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function get_settings() {
    static $settings = null;
    if ($settings === null) {
        $db = \App\Core\Database::getInstance();
        $rows = $db->query("SELECT * FROM settings")->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

function setting($key, $default = '') {
    $settings = get_settings();
    return $settings[$key] ?? $default;
}

function get_active_announcements() {
    $db = \App\Core\Database::getInstance();
    return $db->query(
        "SELECT * FROM announcements WHERE status = 'active' AND (start_date IS NULL OR start_date <= NOW()) AND (end_date IS NULL OR end_date >= NOW()) ORDER BY priority DESC, created_at ASC"
    )->fetchAll();
}

function is_admin_authenticated() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_user']);
}

function is_customer_authenticated() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['customer_user']);
}

function get_admin_user() {
    return $_SESSION['admin_user'] ?? null;
}

function get_customer_user() {
    return $_SESSION['customer_user'] ?? null;
}

function format_price($amount) {
    return '₹' . number_format((float)$amount, 0);
}

function format_date($date, $format = 'M d, Y') {
    if (!$date) return '';
    return date($format, strtotime($date));
}

function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function get_client_ip() {
    $headers = ['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    foreach ($headers as $h) {
        if (!empty($_SERVER[$h])) {
            $ips = explode(',', $_SERVER[$h]);
            return trim($ips[0]);
        }
    }
    return '127.0.0.1';
}
