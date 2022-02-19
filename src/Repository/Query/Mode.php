<?php

namespace Lilashih\Simphp\Repository\Query;

class Mode extends BaseQuery
{
    protected $mode;

    /**
     * Filter mode
     *
     * @param string $mode
     *   - empty string：It will not return soft deleted data
     *   - trashed：It will return soft deleted data only
     *   - all：It will return all data, include soft deleted
     */
    public function __construct($mode = '')
    {
        $this->mode = $mode;
    }

    public function query($query)
    {
        switch ($this->mode) {
            case 'trashed':
                $query = $query->onlyTrashed();
                break;
            case 'all':
                $query = $query->withTrashed();
                break;
        }

        return $query;
    }
}
