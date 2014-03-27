<?php

namespace Aslan\Log;

use Aslan\Log\AdapterInterface;

class Multiple
{
    protected $queue;
    
    public function __construct()
    {
        $this->queue = new \SplQueue();
    }
    
    public function push(AdapterInterface $adapter)
    {
        $this->queue->push($adapter);
    }
    
    public function __call($func, $arg)
    {
        foreach ( $this->queue as $adapter )
        {
            call_user_func_array([$adapter, $func], $arg);
        }
    }
}