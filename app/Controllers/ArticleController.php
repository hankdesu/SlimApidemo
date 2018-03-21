<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\User;
use App\Models\Article;
use Respect\Validation\Validator as v;
use App\Token\JsonWebToken;

/**
*
*/
class ArticleController
{
    public function pageOfArticle(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $jwtDecode = JsonWebToken::jwtDecode($parsedBody['token']);

        if (!is_object($jwtDecode)) {
            $jsonResponse = ['error_code' => 401, 'error_msg' => $jwtDecode,];
            return $response->withJson($jsonResponse);
        };

        $article_Count = Article::where('user_id', $jwtDecode->id)->count();

        $total_Pages = ceil($article_Count / 3);

        if ($parsedBody['now_page'] > $total_Pages || $parsedBody['now_page'] < 1 || $article_Count == 0) {
            $jsonResponse = ['error_code' => 402, 'error_msg' => 'json error',];
            return $response->withJson($jsonResponse);
        }

        $start_row = ($parsedBody['now_page'] - 1) * 3;

        $articles = Article::selectRaw('id, title, subtitle, FROM_UNIXTIME(UNIX_TIMESTAMP(created_at), "%M %D %Y") as created_date')
        ->where('user_id', $jwtDecode->id)
        ->offset($start_row)
        ->limit(3)
        ->orderBy('created_at', 'desc')
        ->get();

        $jsonResponse = ['error_code' => 200, 'error_msg' => 'fetch success', 'listofarticle' => $articles, 'total_pages' => $total_Pages];
        return $response->withJson($jsonResponse);
    }

    public function postArticle(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $jwtDecode = JsonWebToken::jwtDecode($parsedBody['token']);

        if (!is_object($jwtDecode)) {
            $jsonResponse = ['error_code' => 401, 'error_msg' => $jwtDecode,];
            return $response->withJson($jsonResponse);
        };

        foreach ($parsedBody as $value) {
            if (empty($value)) {
                $jsonResponse = ['error_code' => 403, 'error_msg' => 'must fill whole form',];
                return $response->withJson($jsonResponse);
            }
        }

        $user = User::find($jwtDecode->id);

        $content = nl2br($parsedBody['content']);
        $parsedBody['content'] = $content;

        $article = new Article;
        $article->fill($parsedBody);
        $user->articles()->save($article);

        $jsonResponse = ['error_code' => 200, 'error_msg' => 'post article'];
        return $response->withJson($jsonResponse);
    }

    public function content(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $jwtDecode = JsonWebToken::jwtDecode($parsedBody['token']);

        if (!is_object($jwtDecode)) {
            $jsonResponse = ['error_code' => 401, 'error_msg' => $jwtDecode,];
            return $response->withJson($jsonResponse);
        };

        $article = Article::selectRaw('id, title, subtitle, content, FROM_UNIXTIME(UNIX_TIMESTAMP(created_at), "%M %D %Y") as created_date')
        ->where('id', $parsedBody['id'])
        ->get();
        // $article = Article::find($parsedBody['id']);

        $jsonResponse = ['error_code' => 200, 'error_msg' => 'fetch success', 'article' => $article];
        return $response->withJson($jsonResponse);
    }

    public function delete(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();

        $jwtDecode = JsonWebToken::jwtDecode($parsedBody['token']);

        if (!is_object($jwtDecode)) {
            $jsonResponse = ['error_code' => 401, 'error_msg' => $jwtDecode,];
            return $response->withJson($jsonResponse);
        };

        $article = Article::where('id', $parsedBody['id'])->delete();

        if ((bool) $article != false) {
            $jsonResponse = ['error_code' => 200, 'error_msg' => 'article success delete',];
            return $response->withJson($jsonResponse);
        }

        $jsonResponse = ['error_code' => 402, 'error_msg' => 'json error',];
        return $response->withJson($jsonResponse);
    }
}
