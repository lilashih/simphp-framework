<?php

namespace Lilashih\Simphp\Model;

use Lilashih\Simphp\Model\Traits\Common;
use Lilashih\Simphp\Model\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model implements IModel
{
    use Sortable;
    use Common;

    protected $connection = 'default';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function __construct(array $attributes = [])
    {
        $this->setTableWithDB();
        parent::__construct($attributes);
    }
}
