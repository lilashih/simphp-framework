<?php

namespace Lilashih\Simphp\Repository\Query;

class WhereLike extends BaseQuery
{
    protected $column;
    protected $value;

    public function __construct($column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    public function query($query)
    {
        if ($this->column && !is_null($this->value) && ($this->value !== '')) {
            $query = $query->where($this->column, 'like', "%$this->value%");
        }

        return $query;
    }
}
