<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Middleware\Middleware;

/**
 *
 */
class RegisterMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $parsedBody = json_decode($request->getBody());

        $this->userValidate($parsedBody);
        if ($this->failed()) {
            $jsonResponse = ['error_code' => 400, 'error_msg' => $this->errors];
            return $response->withJson($jsonResponse);
        }

        $response = $next($request, $response);

        return $response;
    }
}
