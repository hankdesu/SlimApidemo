<?php

namespace App\Middleware;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Models\User;

/**
*
*/
class Middleware
{
    protected $errors;

    public function userValidate($request)
    {
        $userValidator = v::attribute('username', v::notEmpty())
        ->attribute('password', v::noWhitespace()->notEmpty());

        if (!($user = User::where('username', $request->username)->count() === 0)) {
            $this->errors = ['username be used'];
        }

        try {
            return $userValidator->assert($request);
        } catch (NestedValidationException $e) {
            $this->errors = $e->getMessages();
        }
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}
