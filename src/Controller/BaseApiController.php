<?php

namespace Lilashih\Simphp\Controller;

use Lilashih\Simphp\Exception\ModelNotFoundException;

abstract class BaseApiController extends BaseController
{
    protected $user = null;

    protected $messages = [
        'success_create' => 'Success',
        'success_update' => 'Success',
        'success_delete' => 'Success',
    ];

    public function index()
    {
        $items = $this->repo->all($this->filters());

        return $this->collection($items);
    }

    public function show(...$args)
    {
        if ($item = $this->repo->find(...$args)) {
            return $this->resource($item);
        }

        throw new ModelNotFoundException();
    }

    public function store()
    {
        $data = $this->validator->validate('store', $_POST);

        $model = $this->repo->updateOrCreate($data);

        return $this->messages['success_create'];
    }

    public function update(...$args)
    {
        $data = $this->validator->validate('update', $_POST, $args[0]);

        $model = $this->repo->updateOrCreate($data, ...$args);

        return $this->messages['success_update'];
    }

    public function destroy(...$args)
    {
        $this->repo->delete($this->user['id'], ...$args);

        return $this->messages['success_delete'];
    }

    /**
     * Route query
     * 
     * @return array
     */
    protected function filters(): array
    {
        return array_only($_GET ?? [], ['mode', 'name']);
    }

    /**
     * Formating response data
     * 
     * @param mixed $items
     * 
     * @return array
     */
    public function collection($items)
    {
        return $this->resource::collection($items);
    }

    /**
     * Formating response data
     * 
     * @param mixed $item
     * 
     * @return array
     */
    public function resource($item)
    {
        return $this->resource::resource($item);
    }
}
