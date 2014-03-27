<?php

namespace Aslan\Log;

use Aslan\Log\Exception\GroupNotFound;

use Aslan\Log\Exception\GroupExists;

use Aslan\Log\AdapterInterface;

class Registry
{
    protected $group = [];
    
    public function add( $group, AdapterInterface $repository )
    {
        if ( array_key_exists($group, $this->groups) )
        {
            throw new GroupExists('Group ":group" exists', [':group' => (string) $group]);
        }
        
        $this->groups[$group] = $repository;
    }
    
    public function remove( $group )
    {
        if ( ! array_key_exists($group, $this->groups) )
        {
            throw new GroupNotFound('Group ":group" not found', [':group' => (string) $group]);
        }
        
        unset($this->groups[$group]);
    }
    
    public function & get( $group = 'default' )
    {
        if ( ! array_key_exists($group, $this->groups) )
        {
            throw new GroupNotFound('Group ":group" not found', [':group' => (string) $group]);
        }
        
        return $this->groups[$group];
    }
}