<?php

namespace Lilashih\Simphp\Route;

use Lilashih\Simphp\View\View;

trait Response
{
    public function success(...$args)
    {
        return $this->json(200, ...$args);
    }

    public function error(...$args)
    {
        return $this->json(400, ...$args);
    }

    public function notFoundPage(...$args)
    {
        return $this->json(404, ...$args);
    }

    public function unauthorized(...$args)
    {
        return $this->json(401, ...$args);
    }

    public function permission(...$args)
    {
        return $this->json(403, ...$args);
    }

    public function validationFail(...$args)
    {
        return $this->json(422, ...$args);
    }

    protected function json($code, ...$args)
    {
        list($message, $data) = $this->formatResponse($args);

        header('Content-type: application/json');
        http_response_code((intval($code)));
        return json_encode([
            'message' => $message,
            'data' => $data,
        ], true);
    }

    protected function formatResponse($args)
    {
        $message = '';
        $data = [];
        while (count($args) > 0) {
            $first = array_shift($args);
            if (is_string($first)) {
                $message = $first;
            } elseif (is_array($first)) {
                $data = $first;
            }
        }

        return [$message, $data];
    }

    public function html(View $view)
    {
        return $view->render();
    }
}
