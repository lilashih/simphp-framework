<?php

namespace Lilashih\Simphp\Repository;

use Lilashih\Simphp\Pipeline\Pipeline;
use Lilashih\Simphp\Repository\Query\Mode;
use Lilashih\Simphp\Repository\Query\OrderBy;
use Lilashih\Simphp\Repository\Query\Where;
use Lilashih\Simphp\Repository\Query\WhereLike;
use Lilashih\Simphp\Exception\ModelNotFoundException;

abstract class DatabaseRepository implements RepositoryInterface
{
    protected $model;

    public function getModel()
    {
        return $this->model;
    }

    public function uniqueCheck($column, $value, $exceptId = null): int
    {
        $pipeline = new Pipeline();
        $result = $pipeline
            ->send($this->model->query())
            ->through([
                new Where($column, $value),
                new Where($this->model->getKeyName(), $exceptId, '!='),
            ])
            ->then(function ($query) {
                return $query->count();
            });
        return $result;
    }

    public function all(array $filters = [], array $with = []): array
    {
        $pipeline = new Pipeline();
        $result = $pipeline
            ->send($this->model->with($with))
            ->through([
                new Mode($filters['mode'] ?? null),
                new WhereLike('name', ($filters['name'] ?? null)),
                new OrderBy($this->model->getSortColumn()),
                new OrderBy($this->model->getKeyName()),
            ])
            ->then(function ($query) {
                return $query->get();
            });
        return $result->toArray();
    }

    public function find($id, array $with = []): array
    {
        return ($model = $this->model->with($with)->find($id)) ? ($model->toArray()) : ([]);
    }

    public function updateOrCreate(array $data, $id = null)
    {
        $model = ($id) ? ($this->model->find($id)) : (new $this->model());

        if (!$model) {
            throw new ModelNotFoundException();
        }

        foreach ($data as $attribute => $value) {
            $model->{$attribute} = $value;
        }

        $model->save();

        return $model->toArray();
    }

    public function delete($deletedBy, $id): void
    {
        if (!$model = $this->model->find($id)) {
            throw new ModelNotFoundException();
        }

        $model->delete();
    }
}
