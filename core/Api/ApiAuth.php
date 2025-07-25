<?php

namespace Core\Api;

use Core\Http\Request;

class ApiAuth
{
    private static array $apiKeys = [];

    public static function authenticate(Request $request): bool
    {
        $apiKey = $request->get('api_key') ?? 
            self::getBearerToken($request);

        if (!$apiKey) {
            return false;
        }

        return self::validateApiKey($apiKey);
    }

    private static function getBearerToken(Request $request): ?string
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private static function validateApiKey(string $apiKey): bool
    {
        // Ici, vous pourriez valider la clé API contre une base de données
        // Pour l'exemple, nous utilisons une liste statique
        $validKeys = [
            'test-api-key-123',
            'demo-key-456'
        ];

        return in_array($apiKey, $validKeys);
    }

    public static function generateApiKey(): string
    {
        return bin2hex(random_bytes(32));
    }
}

