<?php

namespace Aslan;

class Exception extends \Exception
{
    public function __construct($message = '', array $variables = null, $code = 0, \Exception $previous = null)
    {
        if ($variables !== null)
        {
            $message = strtr($message, $variables);
        }
        
        \Exception::__construct($message, (int) $code, $previous);
        
        $this->code = $code;
    }
}