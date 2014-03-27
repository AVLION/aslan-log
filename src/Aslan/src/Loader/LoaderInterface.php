<?php

namespace Aslan\Loader;

interface LoaderInterface
{
    public function loadClass( $class );
    
    public function register( $prepend = false );
    
    public function unregister();
}