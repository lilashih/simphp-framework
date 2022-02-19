<?php

namespace Lilashih\Simphp\Controller;

use Lilashih\Simphp\Auth\JWT\Auth;

abstract class BaseController
{
    use Auth;

    protected $repo;
    protected $resource;
    protected $validator;
}
