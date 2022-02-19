<?php

use Illuminate\Support\Arr;

if (!function_exists('dd')) {
    function dd($data)
    {
        var_dump($data);
        exit;
    }
}

if (!function_exists('dump')) {
    function dump($data)
    {
        var_dump($data);
    }
}

if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('_env')) {
    function _env($key, $default = null)
    {
        if (isset($_ENV[$key])) {
            $value = $_ENV[$key];
        } else {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('view')) {
    function view($path)
    {
        return project_root("resources/views/{$path}");
    }
}

if (!function_exists('now')) {
    function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}

if (!function_exists('array_column_search')) {
    function array_column_search($data, $column, $search, $getValue = true)
    {
        if (($key = array_search($search, array_column($data, $column))) !== false) {
            return ($getValue) ? ($data[$key]) : $key;
        }
        return null;
    }
}

if (!function_exists('project_root')) {
    function project_root($path = '')
    {
        $root = $_SERVER['DOCUMENT_ROOT'] . "/./..";
        $path = ($path) ? (trim($path, '/')) : ($path);
        return ($path) ? ("{$root}/{$path}") : $root;
    }
}

if (!function_exists('dialog')) {
    function dialog($string)
    {
        echo $string;
        $handle = fopen("php://stdin", "r");
        return trim(fgets($handle));
    }
}

if (!function_exists('config')) {
    function config($key)
    {
        if (is_null($key)) {
            throw new Exception("can't be null");
        }

        try {
            list($file, $key) = explode('.', $key, 2);
        } catch (Exception $e) {
            $key = null;
        }

        $array = require project_root() . "/config/{$file}.php";
        return Arr::get($array, $key);
    }
}

if (!function_exists('random_str')) {
    function random_str($limit)
    {
        return strtoupper(substr(md5(mt_rand()), 0, $limit));
    }
}

if (!function_exists('path_slash')) {
    function path_slash($subject)
    {
        return ((DIRECTORY_SEPARATOR === '\\') ? str_replace('/', '\\', $subject) : str_replace('\\', '/', $subject));
    }
}

if (!function_exists('is_test')) {
    function is_test()
    {
        return (_env('APP_ENV') === 'test');
    }
}