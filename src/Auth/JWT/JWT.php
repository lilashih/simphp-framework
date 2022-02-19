<?php

namespace Lilashih\Simphp\Auth\JWT;

use Firebase\JWT\JWT as JWTService;

class JWT
{
    protected $config;

    public function __construct()
    {
        $this->config = config('jwt');
    }

    public function createToken($id)
    {
        $payload = $this->payload($id, $this->config['expiration']);
        return JWTService::encode($payload, $this->config['key']);
    }

    public function parseToken($token)
    {
        if ($this->isTestToken($token)) {
            return ['sub' => -1];
        }
        return get_object_vars(JWTService::decode($token, $this->config['key'], $this->config['algorithm']));
    }

    protected function isTestToken($token)
    {
        return $this->config['test_token'] === $token;
    }

    /**
     * Payload:
     *   iss (issuer)
     *   sub (subject)
     *   aud (audience)
     *   exp (expiration time)
     *   nbf (not before)
     *   iat (issued at)
     *   jti (jwt id)
     *
     * @param int $id
     * @param int $expiration
     *
     * @return array
     */
    protected function payload($id, $expiration)
    {
        return [
            'sub' => $id, // user id
            'iat' => time(),
            'exp' => time() + $expiration,
        ];
    }
}
