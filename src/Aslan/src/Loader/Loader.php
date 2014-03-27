<?php

namespace Aslan\Loader;

use \Aslan\Loader\LoaderInterface;

abstract class Loader implements LoaderInterface
{
    public function register( $prepend = false )
    {
        spl_autoload_register([$this, 'loadClass'], true, $prepend);
    }
    
    public function unregister()
    {
        spl_autoload_unregister([$this, 'loadClass']);
    }
}