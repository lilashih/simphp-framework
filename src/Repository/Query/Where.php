<?php

namespace Lilashih\Simphp\Repository\Query;

class Where extends BaseQuery
{
    protected $column;
    protected $value;
    protected $operator;

    public function __construct($column, $value, $operator = '=')
    {
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function query($query)
    {
        if ($this->column && !is_null($this->value)) {
            $query = $query->where($this->column, $this->operator, $this->value);
        }

        return $query;
    }
}
