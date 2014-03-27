<?php

namespace Aslan\Config;

use Aslan\Config\RepositoryInterface;

class Repository implements RepositoryInterface
{
    protected $items = [];
    
    public function offsetExists( $index )
    {
        return array_key_exists($index);
    }
    
    public function offsetGet( $index )
    {
        return $this->items[$index];
    }
    
    public function offsetSet( $index, $value )
    {
        $this->items[$index] = $value;
    }
    
    public function offsetUnset( $index )
    {
        unset($this->items[$index]);
    }
    
    public function count()
    {
        return sizeof($this->items);
    }
    
    public function serialize()
    {
        return serialize($this->items);
    }
    
    public function unserialize( $word )
    {
        $this->items = (array) unserialize($word);
    }
    
    public function __invoke( array $items )
    {
        if ( $items !== null )
        {
            $this->items = $items;
        }
    }
    
    public function get( $index, $default = null )
    {
        if ( $index === null )
        {
            return $this->items;
        }
        
        if ( array_key_exists($index, $this->items) )
        {
            return $this->items[$index];
        }
        
        $array = $this->items;
        
        foreach (explode('.', $index) as $key)
        {
            if ( ! isset($array[$key]) )
            {
                return $default;
            }
            
            $array = $array[$key];
        }
        
        return $array;
    }
    
    public function set( $index, $value )
    {
        $array =& $this->items;
        
        $keys = explode('.', $index);
        
        while (sizeof($keys) > 1)
        {
            $index = array_shift($keys);
            
            if ( ! isset($array[$index]) || ! is_array($array[$index]))
            {
                $array[$index] = [];
            }

            $array =& $array[$index];
        }
        
        $array[array_shift($keys)] = $value;
    }
    
    public function merge( array $items )
    {
        $this->items = array_merge($this->items, $items);
    }
    
    public function toArray()
    {
        return (array) $this->items;
    }
    
    public function __isset( $index )
    {
        return array_key_exists($index, $this->items);
    }
    
    public function __unset( $index )
    {
        unset($this->items[$index]);
    }
    
    public function __get( $index )
    {
        $result = $this->get($index);
        
        if ( is_array($result) )
        {
            return new Repository($result);
        }
        
        return $result;
    }
    
    public function __set( $index, $value )
    {
        $this->set($index, $value);
    }
    
    public function __construct( array $items )
    {
        $this->items = (array) $items;
    }
}