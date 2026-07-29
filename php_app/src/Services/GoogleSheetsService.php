<?php
namespace App\Services;

class GoogleSheetsService
{
    public static function appendToSheet($values)
    {
        $config = require __DIR__ . '/../../config/app.php';
        $gs = $config['google_sheets'];
        if (empty($gs['client_email'])) return false;

        $clientEmail = $gs['client_email'];
        $privateKey = str_replace('\\n', "\n", $gs['private_key']);
        $spreadsheetId = $gs['spreadsheet_id'];

        $jwtToken = self::createJwtAssertion($clientEmail, $privateKey);
        $accessToken = self::getAccessToken($jwtToken);

        if (!$accessToken) {
            error_log("Failed to get Google access token");
            return true;
        }

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/Sheet1!A:G:append?valueInputOption=USER_ENTERED&insertDataOption=INSERT_ROWS";
        $data = json_encode(['values' => $values]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) return true;
        error_log("Google Sheets append error: " . $response);
        return false;
    }

    public static function getOrdersFromSheet()
    {
        $config = require __DIR__ . '/../../config/app.php';
        $gs = $config['google_sheets'];
        if (empty($gs['client_email'])) return [];

        $clientEmail = $gs['client_email'];
        $privateKey = str_replace('\\n', "\n", $gs['private_key']);
        $spreadsheetId = $gs['spreadsheet_id'];

        $jwtToken = self::createJwtAssertion($clientEmail, $privateKey);
        $accessToken = self::getAccessToken($jwtToken);
        if (!$accessToken) return [];

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/Sheet1!A:G";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $rows = $data['values'] ?? [];
        return count($rows) > 1 ? array_slice($rows, 1) : [];
    }

    private static function createJwtAssertion($clientEmail, $privateKey)
    {
        $header = self::base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $now = time();
        $claim = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);
        $payload = self::base64UrlEncode($claim);
        $signature = '';
        openssl_sign("{$header}.{$payload}", $signature, $privateKey, 'sha256WithRSAEncryption');
        return "{$header}.{$payload}." . self::base64UrlEncode($signature);
    }

    private static function getAccessToken($jwtToken)
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwtToken,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
