<?php

namespace Aslan\Config;

interface Repository extends \ArrayAccess, \Countable, \Serializable
{
    public function get($index, $default = null);
    
    public function __get($index);
    
    public function set($index, $value);
    
    public function __set($index, $value);
    
    public function merge( array $items );
    
    public function toArray();
}