<?php

namespace Aslan\Loader;

use \Aslan\Loader\Loader;

class Map extends Loader
{
    protected $map = [];
    
    public function __construct( array $map )
    {
        $this->map = $map;
    }
    
    public function registryMap( array $map, $merge = true )
    {
        if ( $merge )
        {
            $this->map = array_merge($this->map, $map);
        }
        else
        {
            $this->map = $map;
        }
    }
    
    public function loadClass( $class )
    {
        if ( array_key_exists($class, $this->map) )
        {
            require $this->map[$class];
        }
    }
}