<?php

namespace Lilashih\Simphp\Repository\Query;

class WhereIn extends BaseQuery
{
    protected $column;
    protected $data;

    public function __construct($column, ?array $data = null)
    {
        $this->column = $column;
        $this->data = $data;
    }

    public function query($query)
    {
        if ($this->column && !is_null($this->data)) {
            $query = $query->whereIn($this->column, $this->data);
        }

        return $query;
    }
}
