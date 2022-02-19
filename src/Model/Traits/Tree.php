<?php

namespace Lilashih\Simphp\Model\Traits;

trait Tree
{
    protected $parent = 'parent_id';

    public function getParentName()
    {
        return $this->parent;
    }

    public function children()
    {
        return $this->hasMany(self::class, $this->getParentName(), $this->getKeyName())
            ->orderBy($this->getSortColumn())
            ->orderBy($this->getKeyName());
    }

    public function parent()
    {
        return $this->belongsTo(self::class, $this->getParentName(), $this->getKeyName())
            ->orderBy($this->getSortColumn())
            ->orderBy($this->getKeyName());
    }
}
