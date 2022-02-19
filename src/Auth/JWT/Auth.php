<?php

namespace Lilashih\Simphp\Auth\JWT;

use Exception;
use Firebase\JWT\SignatureInvalidException;

trait Auth
{
    /**
     * Get the user by token
     *
     * @return mixed
     * @throws Exception
     */
    protected function getUser()
    {
        try {
            $model = config('jwt.model');
            
            $id = (new JWT())->parseToken($this->token())['sub'];
            $user = (new $model)->find($id);

            if (!$user && $id != -1) {
                throw new SignatureInvalidException('Signature Invalid');
            }
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the token in request header
     *
     * @return mixed
     * @throws Exception
     */
    protected function token()
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new Exception('Unauthorized');
        }

        $bearer = $_SERVER['HTTP_AUTHORIZATION'];
        return str_replace('Bearer ', '', $bearer);
    }
}
