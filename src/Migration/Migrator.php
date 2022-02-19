<?php

namespace Lilashih\Simphp\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;

class Migrator
{
    protected $model;

    public function __construct()
    {
        $this->model = new Migration();
    }

    public function prepare()
    {
        $this->createTableIfNotExist();
    }

    public function execMigration($name, $sql): int
    {
        if (!$this->model->where('migration', $name)->first()) {
            Capsule::unprepared($sql);
            $this->model->create(['migration' => $name]);
            return 1;
        }
        return 0;
    }

    public function getMigrations()
    {
        $dirname = project_root('database/migrations');

        $condition = (is_test()) ? '/*.*' : '/*.sql';

        return glob($dirname.$condition);
    }

    protected function createTableIfNotExist()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->model->getTable()} (
            `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT,
            `migration` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        Capsule::select($sql);
    }
}
