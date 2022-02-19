<?php

namespace Lilashih\Simphp\Test;

interface ITestApi
{
    public function repo();

    public function resourceKey(): string;

    public function urlKey(): string;

    public function getStoreData();

    public function getUpdateData();
}
