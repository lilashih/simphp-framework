<?php

namespace Lilashih\Simphp\Log;

use Lilashih\Simphp\View\View;

class LogController
{
    public function index()
    {
        return new View($this->getView(), [
            'paths' => $this->getLogs(),
        ], false);
    }

    public function show($id)
    {
        $paths = $this->getLogs($id);
        $active = array_column_search($paths, 'active', true);

        if ($active) {
            $rule = '/^\[(?<time>.*)\]\s(?<chanel>\w+)\.(?<level>\w+):\s(?<message>.*)/';
            $lines = file($active['path'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $lines = array_map(function ($line) use ($rule) {
                preg_match($rule, $line, $matches);
                $content = explode(' ', $matches['message'], 2);
                $matches['message'] = $content[0];
                $matches['content'] = $content[1];
                return $matches;
            }, $lines);
        }

        return new View($this->getView(), [
            'paths' => $paths,
            'active' => $active,
            'lines' => $lines,
        ], false);
    }

    protected function getView()
    {
        return project_root(config('log.viewer.view'));
    }

    protected function getLogs($activeFile = '')
    {
        $dirname = project_root('storage/logs');
        $paths = glob("{$dirname}/*.log");
        $paths = array_map(function ($path) use ($activeFile) {
            $data = [
                'path' => $path,
                'name' => end(explode('/', $path)),
            ];
            $data['active'] = ($activeFile === $data['name']);
            return $data;
        }, $paths);

        return $paths;
    }
}
