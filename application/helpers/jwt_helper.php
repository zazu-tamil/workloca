<?php
// ============================================
// JWT HELPER - jwt_helper.php
// ============================================
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('generate_jwt')) {
    function generate_jwt($payload)
    {
        $key = "subash"; // 🔒 Use a strong secret in production
        $issuedAt = time();
        $expire = $issuedAt + 86400; // 1 day validity

        $token = [
            "iss" => "http://192.168.0.40/erp-psm",
            "iat" => $issuedAt,
            "exp" => $expire,
            "data" => $payload
        ];

        return JWT::encode($token, $key, 'HS256');
    }
}

if (!function_exists('verify_jwt')) {
    function verify_jwt($jwt)
    {
        $key = "subash";
        if (empty($jwt)) {
            return false;
        }
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return (array)$decoded->data;
        } catch (Exception $e) {
            log_message('error', 'JWT verification failed: ' . $e->getMessage());
            return false;
        }
    }
}