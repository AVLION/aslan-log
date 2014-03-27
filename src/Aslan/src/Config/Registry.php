<?php

namespace Aslan\Config;

use Aslan\Config\RepositoryInterface;

use Aslan\Config\Exception\GroupNotFound;

use Aslan\Config\Exception\GroupExists;

class Registry
{
    protected $groups = [];
    
    public function add( $group, RepositoryInterface $repository )
    {
        if ( array_key_exists($group, $this->groups) )
        {
            throw new GroupExists('Group ":group" exists', [':group' => $group]);
        }
        
        $this->groups[$group] = $repository;
    }
    
    public function remove( $group )
    {
        if ( ! array_key_exists($group, $this->groups) )
        {
            throw new GroupNotFound('Group ":group" not found', [':group' => $group]);
        }
        
        unset($this->groups[$group]);
    }
    
    public function & get( $group = 'default' )
    {
        if ( ! array_key_exists($group, $this->groups) )
        {
            throw new GroupNotFound('Group ":group" not found', [':group' => $group]);
        }
        
        return $this->groups[$group];
    }
}