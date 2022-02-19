<?php

namespace Lilashih\Simphp\Console\Commands;

use Exception;
use Lilashih\Simphp\Log\Log;
use Lilashih\Simphp\Migration\Migrator;

class Migrate extends BaseCommand
{
    protected $service;
    protected $count = 0;
    protected $config = [];

    public function __construct()
    {
        $this->service = new Migrator();
        $this->config = config('database');
    }

    public function handel()
    {
        try {
            $this->service->prepare();

            $paths = $this->service->getMigrations();

            foreach ($paths as $path) {
                $arr = explode('/', $path);
                $name = end($arr);
                $content = file_get_contents($path);
                $content = $this->replaceContent($content);

                $result = $this->service->execMigration($name, $content);
                $this->count += $result;

                if ($result) {
                    static::printBlue($name);
                }
            }

            if ($this->count > 0) {
                static::printGreen("Migrate successfully: {$this->count} files");
            } else {
                static::printRed("Nothing to migrate");
            }
        } catch (Exception $e) {
            Log::add('Migrate fail', [
                'msg' => $e->getMessage(),
            ], 'emergency');

            throw $e;
        }
    }

    public function replaceContent($content)
    {
        foreach ($this->config as $key => $config) {
            if ($key == 'default') continue;

            $replace = strtoupper("{$key}_DB");
            $content = str_replace('{$'.$replace.'}', $config['database'], $content);
        }

        return $content;
    }
}
