<?php

namespace Lilashih\Simphp\Route;

interface IRegister
{
    public function register(Route $route): Route;
}