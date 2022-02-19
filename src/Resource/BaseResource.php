<?php

namespace Lilashih\Simphp\Resource;

abstract class BaseResource
{
    public static $key = [
        'collection' => '',
        'resource' => '',
    ];

    public static function collection()
    {
        list($items, $parameters) = self::parameters(...func_get_args());

        $data = [static::$key[__FUNCTION__] => array_map(function ($item) use ($parameters) {
            return call_user_func([get_called_class(), 'toArray'], $item, ...$parameters);
        }, $items)];

        return $data;
    }

    public static function resource()
    {
        list($item, $parameters) = self::parameters(...func_get_args());

        $data = [static::$key[__FUNCTION__] => call_user_func([get_called_class(), 'toArray'], $item, ...$parameters)];

        return $data;
    }

    protected static function parameters()
    {
        $parameters = func_get_args();
        $data = array_shift($parameters);

        return [$data, $parameters];
    }

    abstract public static function toArray(array $data): array;
}
