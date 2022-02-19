<?php

namespace Lilashih\Simphp\Model;
use Illuminate\Database\Eloquent\Relations\Pivot as BasePivot;

abstract class Pivot extends BasePivot
{
    protected $connection = 'default';
    public $timestamps = false;

    public static function getTableName()
    {
        $instance = new static();
        $database = $instance->getConnection()->getDatabaseName();
        $table = $instance->getTable();
        return "{$database}.{$table}";
    }
}
