<?php

namespace Lilashih\Simphp\Test;

use Lilashih\Simphp\Route\Router;
use Lilashih\Simphp\Auth\JWT\JWT;
use Exception;
use PHPUnit\Framework\TestCase;
use Lilashih\Simphp\Log\Log;

abstract class BaseTest extends TestCase
{
    protected static $token = null;

    public static function setUpBeforeClass(): void
    {
        // self::setConfigRoot();

        if (is_null(self::$token)) {
            self::$token = (new JWT())->createToken(-1);
        }
    }

    public static function tearDownAfterClass()
    {
        self::$token = null;
    }

    protected static function setConfigRoot()
    {
        $_SERVER['DOCUMENT_ROOT'] = getcwd() . '/public';
    }

    protected function apiAs(array $headers)
    {
        if (self::$token) {
            $headers['Authorization'] = "Bearer " . self::$token;
        }
        return $headers;
    }

    protected function mockRequest($method, $url, array $data = [])
    {
        $method = strtoupper($method);
        $url = '/' . trim($url, '/');

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $url;

        $_GET = null;
        $_POST = null;
        if ($data) {
            if ($method === 'GET') {
                $_SERVER['REQUEST_URI'] = "{$url}?" . http_build_query($data);
                $_GET = $data;
            } else {
                // 新增 編輯
                $_POST = $data;
            }
        }

        $route = require project_root() . '/app/route.php';
        return (new Router($route))->response();
    }

    // mock request
    protected function mockApi($method, $url, array $data = [], $expectStatusCode = 200, array $headers  = [])
    {
        try {
            $url = trim($url, '/');

            $headers = $this->apiAs($headers);
            foreach ($headers as $name => $val) {
                $name = strtoupper($name);
                $_SERVER["HTTP_{$name}"] = $val;
            }

            $response = $this->mockRequest($method, $url, $data);
            $body = json_decode($response, true);

            $this->assertEquals($expectStatusCode, http_response_code());

            foreach ($headers as $name => $val) {
                $name = strtoupper($name);
                unset($_SERVER["HTTP_{$name}"]);
            }

            return $body;
        } catch (Exception $e) {
            Log::add($e->getMessage(), [
                'class' => $e,
                'method' => $method,
                'url' => $url,
                'data' => $data,
                'body' => $body,
            ], 'debug', 'phpunit');
            throw $e;
        }
    }
}
