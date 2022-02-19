<?php

namespace Lilashih\Simphp\Model\Traits;

trait Sortable
{
    protected $sortColumn = 'sort';

    public function getSortColumn()
    {
        return $this->sortColumn;
    }
}
