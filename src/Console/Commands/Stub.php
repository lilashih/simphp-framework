<?php

namespace Lilashih\Simphp\Console\Commands;

use Exception;
use Lilashih\Simphp\Log\Log;

class Stub extends BaseCommand
{
    protected $config = [];
    protected $className;
    protected $route;
    protected $title;
    protected $table;
    protected $group;

    public function __construct()
    {
        $className = dialog('Please Enter the name of model class（Ex. Image）：');
        $route = dialog('Please Enter the name of route（Ex. images）：');
        $group = dialog('Please Enter the name of phpunit group（Ex. image）：');
        $table = dialog('Please Enter the name of DB（Ex. my_images）：');
        $title = dialog('Please Enter the title of API document（Ex. Image）：');

        $this->className = ucwords($className);
        $this->route = $route;
        $this->title = $title;
        $this->table = $table;
        $this->group = $group;

        $this->config = config('stub');

        static::print();
    }

    public function handel()
    {
        try {
            $this->makeController();
            $this->makeModel();
            $this->makeRepository();
            $this->makeResource();
            $this->makeRequest();
            $this->makeMigration();
            $this->makeTest();
        } catch (Exception $e) {
            Log::add('Error.', [
                'msg' => $e->getMessage(),
            ], 'emergency');

            throw $e;
        }
    }

    protected function makeController()
    {
        $type = 'controller';
        $file = "{$this->getFolder($type)}/{$this->className}Controller.php";
        $this->makeFile($type, $file);
    }

    protected function makeModel()
    {
        $type = 'model';
        $file = "{$this->getFolder($type)}/{$this->className}.php";
        $this->makeFile($type, $file);
    }

    protected function makeRepository()
    {
        $type = 'repository';
        $file = "{$this->getFolder($type)}/{$this->className}Repository.php";
        $this->makeFile($type, $file);
    }

    protected function makeRequest()
    {
        $type = 'request';
        $file = "{$this->getFolder($type)}/{$this->className}Request.php";
        $this->makeFile($type, $file);
    }

    protected function makeResource()
    {
        $type = 'resource';
        $file = "{$this->getFolder($type)}/{$this->className}Resource.php";
        $this->makeFile($type, $file);
    }

    protected function makeMigration()
    {
        $type = 'migration';$today = date('Y_m_d_His');
        $file = "{$this->getFolder($type)}/{$today}_create_{$this->table}_table.sql";
        $this->makeFile($type, $file);
    }

    protected function makeTest()
    {
        $type = 'test';
        $file = "{$this->getFolder($type)}/{$this->className}Test.php";
        $this->makeFile($type, $file);
    }

    protected function makeFile(string $type, string $file)
    {
        if ($this->isEnable($type)) {
            $created = $this->saveContent($file, $this->getContent($type));
            if ($created) {
                static::printYellow("Create {$type} successfully：{$file}");
            }
        }
    }

    protected function isEnable(string $type): bool
    {
        return $this->config[$type]['enable'] ?? false;
    }

    protected function getFolder(string $type): string
    {
        return '/' . trim($this->config[$type]['folder'], '/');
    }

    protected function getStub(string $type): string
    {
        return '/' . trim($this->config[$type]['stub'], '/');
    }

    private function getContent(string $type): string
    {
        $path = project_root() . $this->getStub($type);
        $content = file_get_contents($path);

        $content = str_replace(
            ["{{ className }}", "{{ route }}", "{{ title }}", "{{ table }}", "{{ group }}"],
            [$this->className, $this->route, $this->title, $this->table, $this->group],
            $content
        );

        return $content;
    }

    private function saveContent($file, $content)
    {
        $path = project_root($file);
        if (!file_exists($path)) {
            file_put_contents($path, $content);
            return true;
        }
        return false;
    }
}
