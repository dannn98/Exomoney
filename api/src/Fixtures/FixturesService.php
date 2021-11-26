<?php

namespace App\Fixtures;

use Nelmio\Alice\Loader\NativeLoader;

class FixturesService
{
    private $loader;

    /**
     * @param $loader
     */
    public function __construct()
    {
        $this->loader = new NativeLoader();
    }

    public function generate()
    {
        $objectSet = $this->loader->loadFile(__DIR__ . '/fixtures.yml');
        dd($objectSet);
    }
}