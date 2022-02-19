<?php

namespace Lilashih\Simphp\Repository;

interface RepositoryInterface
{
    public function all(): array;

    public function find($id): array;

    public function updateOrCreate(array $data);
}
