<?php
namespace App\Core;

class JWT
{
    public static function encode($payload, $secret)
    {
        $header = self::base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload['iat'] = $payload['iat'] ?? time();
        $payload['exp'] = $payload['exp'] ?? time() + 604800;
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "{$header}.{$payloadEncoded}", $secret, true)
        );
        return "{$header}.{$payloadEncoded}.{$signature}";
    }

    public static function decode($token, $secret)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \Exception('Invalid token format');
        }
        list($header, $payload, $signature) = $parts;
        $expectedSig = self::base64UrlEncode(
            hash_hmac('sha256', "{$header}.{$payload}", $secret, true)
        );
        if (!hash_equals($expectedSig, $signature)) {
            throw new \Exception('Invalid signature');
        }
        $data = json_decode(self::base64UrlDecode($payload));
        if (!$data) {
            throw new \Exception('Invalid payload');
        }
        if (isset($data->exp) && $data->exp < time()) {
            throw new \Exception('Token expired');
        }
        return $data;
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
