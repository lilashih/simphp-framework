<?php

namespace Lilashih\Simphp\Model\Traits;

trait Common
{
    public static function getTableName()
    {
        return with(new static())->getTable();
    }

    public static function getTableNameWithoutConnection()
    {
        $name = pathinfo(static::getTableName(), PATHINFO_EXTENSION);
        return (empty($name)) ? static::getTableName() : $name;
    }
    
    public function setTableWithDB()
    {
        $database = $this->getConnection()->getDatabaseName();
        $table = $this->getTable();
        $this->setTable("{$database}.{$table}");
    }
}
