<?php

namespace Lilashih\Simphp\Repository\Query;

class OrderBy extends BaseQuery
{
    protected $column;
    protected $direction;

    public function __construct($column, $direction = 'asc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    public function query($query)
    {
        if ($this->column) {
            $query = $query->orderBy($this->column, $this->direction);
        }

        return $query;
    }
}
