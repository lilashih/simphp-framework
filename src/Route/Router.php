<?php

namespace Lilashih\Simphp\Route;

use Lilashih\Simphp\View\View;
use Lilashih\Simphp\Exception\PageNotFoundException;
use Lilashih\Simphp\Exception\PermissionException;
use Lilashih\Simphp\Exception\ValidationException;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class Router
{
    use Response;

    public const NAMESPACE_CONTROLLER = 'App\\Http\\Controllers';

    protected $controller;
    protected $fun;
    protected $parameters = [];

    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route->register();

        json_decode(file_get_contents('php://input'), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }
        if ($_POST) {
            array_walk_recursive($_POST, function (&$v) {
                $v = trim($v);
            });
        }
        if ($_GET) {
            array_walk_recursive($_GET, function (&$v) {
                $v = trim($v);
            });
        }
    }

    public function response()
    {
        try {
            $this->setController();

            if (!is_callable([$this->controller, $this->fun])) {
                throw new PageNotFoundException();
            }

            $response = $this->controller->{$this->fun}(...$this->parameters);

            return (is_a($response, View::class)) ? ($this->html($response)) : ($this->success($response));
        } catch (PageNotFoundException $e) {
            return $this->notFoundPage($e->getMessage());
        } catch (ValidationException $e) {
            return $this->validationFail($e->getMessage(), $e->getData());
        } catch (PermissionException $e) {
            return $this->permission($e->getMessage());
        } catch (ExpiredException $e) {
            return $this->unauthorized('expired token');
        } catch (SignatureInvalidException $e) {
            return $this->unauthorized('invalid token');
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    protected function setController()
    {
        $schema = $this->getSchema();

        if (empty($match = $this->route->match($schema))) {
            throw new PageNotFoundException();
        }
        list($action, $this->parameters) = $match;
        list($controllerName, $this->fun) = explode('@', $action);

        $f = substr($controllerName, 0, 1);
        if ($f != '\\') {
            $controllerName = self::NAMESPACE_CONTROLLER . "\\{$controllerName}";
        }

        $this->controller = new $controllerName();
    }

    protected function getSchema()
    {
        return "{$this->getMethod()}:{$this->getURI()}";
    }

    protected function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    protected function getURI()
    {
        $url = strtok($_SERVER['REQUEST_URI'], '?');
        $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

        return trim($url, '/');
    }
}
