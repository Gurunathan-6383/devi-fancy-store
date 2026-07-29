<?php
function cloudinary_upload($file_path, $folder = 'devi-fancy-store') {
    $config = require __DIR__ . '/app.php';
    $cloud = $config['cloudinary'];
    $timestamp = time();
    $params = [
        'timestamp' => $timestamp,
        'folder' => $folder,
        'transformation' => 'w_800,h_800,c_limit,q_auto',
    ];
    ksort($params);
    $signature_string = '';
    foreach ($params as $key => $value) {
        $signature_string .= $key . '=' . $value . '&';
    }
    $signature_string = rtrim($signature_string, '&') . $cloud['api_secret'];
    $signature = sha1($signature_string);
    $params['api_key'] = $cloud['api_key'];
    $params['signature'] = $signature;
    $params['file'] = new CURLFile($file_path);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloud['cloud_name']}/image/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($http_code === 200) {
        $data = json_decode($response, true);
        return $data['secure_url'] ?? null;
    }
    return null;
}

function cloudinary_upload_from_base64($base64, $folder = 'devi-fancy-store') {
    $tmp = tempnam(sys_get_temp_dir(), 'cld');
    file_put_contents($tmp, base64_decode($base64));
    $result = cloudinary_upload($tmp, $folder);
    unlink($tmp);
    return $result;
}

function cloudinary_delete($url) {
    $config = require __DIR__ . '/app.php';
    $cloud = $config['cloudinary'];
    preg_match('/\/v\d+\/(.+)\.\w+$/', $url, $matches);
    if (!isset($matches[1])) return false;
    $public_id = $matches[1];
    $timestamp = time();
    $signature = sha1("public_id={$public_id}&timestamp={$timestamp}{$cloud['api_secret']}");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloud['cloud_name']}/image/destroy");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'api_key' => $cloud['api_key'],
        'timestamp' => $timestamp,
        'public_id' => $public_id,
        'signature' => $signature,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true)['result'] === 'ok';
}
