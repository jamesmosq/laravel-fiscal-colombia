<?php

namespace JamesMosquera\FiscalColombia\Tests;

use JamesMosquera\FiscalColombia\FiscalColombiaServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FiscalColombiaServiceProvider::class,
        ];
    }
}
