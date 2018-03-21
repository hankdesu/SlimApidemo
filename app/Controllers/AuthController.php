<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\User;
use Respect\Validation\Validator as v;
use App\Token\JsonWebToken;

/**
*
*/
class AuthController
{
    public function helloName(Request $request, Response $response, $args)
    {
        return $response->write("Hello " . $args['name']);
    }

    public function register(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $user = User::create([
            'username' => $parsedBody['username'],
            'password' => password_hash($parsedBody['password'], PASSWORD_DEFAULT),
        ]);

        $jsonResponse = ['error_code' => 200, 'error_msg' => 'user registered', 'token' => JsonWebToken::signature($user), 'username' => $parsedBody['username']];

        return $response->withJson($jsonResponse, 201);
        // return $response->write("success");
    }

    public function login(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $jsonResponse = $jsonResponse = ['error_code' => 400, 'error_msg' => ['login failed']];

        $user = User::where('username', $parsedBody['username'])->first();

        if (!$user) {
            return $response->withJson($jsonResponse);
        }

        if (password_verify($parsedBody['password'], $user->password)) {
            $jsonResponse = ['error_code' => 200, 'error_msg' => 'user login', 'token' => JsonWebToken::signature($user), 'username' => $parsedBody['username']];
            return $response->withJson($jsonResponse);
        }

        return $response->withJson($jsonResponse);
    }
}
