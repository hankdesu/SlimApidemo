<?php

namespace App\Token;

use \Firebase\JWT\JWT;
use App\Method\SettingMethod;

/**
*
*/
class JsonWebToken
{
    public function signature($user)
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../bootstrap');
        $dotenv->load();

        $payload = [
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 86400,
            'iss' => getenv('servername'),
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
            ]

        ];

        $secretKey = getenv('key');
        $algorithm = getenv('algorithm');

        return JWT::encode($payload, $secretKey, $algorithm);
    }

    public function jwtDecode($token)
    {
        $secretKey = getenv('key');
        $algorithm = getenv('algorithm');

        try {
            $data = JWT::decode($token, $secretKey, [$algorithm]);
        
            return $data->data;
        } catch (\Exception $e) {
            return $error_msg = $e->getMessage();
        }
    }
}
