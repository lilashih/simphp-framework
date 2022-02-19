<?php

namespace Lilashih\Simphp\View;

class View
{
    protected $path = null;
    protected $data = [];

    public function __construct($path, array $data = [], $isTemplate = true)
    {
        $this->path = ($isTemplate) ? (view($path)) : ($path);

        foreach ($data as $variable => $value) {
            $this->data($variable, $value);
        }
    }

    public function data($variable, $value)
    {
        $this->data[$variable] = $value;
    }

    public function render()
    {
        extract($this->data);

        ob_start();
        include($this->path);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
