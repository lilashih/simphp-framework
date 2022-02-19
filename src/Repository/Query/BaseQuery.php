<?php

namespace Lilashih\Simphp\Repository\Query;

use Closure;

abstract class BaseQuery
{
    abstract public function query($query);

    public function handle($query, Closure $next)
    {
        $query = $this->query($query);
        return $next($query);
    }
}
