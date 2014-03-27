<?php

namespace Aslan\Log\Adapter;

use Aslan\Log\Level;

use Aslan\Log\Adapter;

use Aslan\Log\Exception\LevelNotFound;

use Aslan\Log\Formatter\Line;

class Syslog extends Adapter
{
    protected $aliases = [
        Level::DEBUG => LOG_DEBUG,
        Level::ALERT => LOG_ALERT,
        Level::CRITICAL => LOG_CRIT,
        Level::EMERGENCE => LOG_EMERG,
        Level::ERROR => LOG_ERR,
        Level::INFO => LOG_INFO,
        Level::NOTICE => LOG_NOTICE,
        Level::WARNING => LOG_WARNING
    ];
    
    public function __construct($ident, array $option = [])
    {
        openlog($ident, $option['option'], $option['facility']);
    }
    
    public function log($type, $message, array $context = null)
    {
        if ( ! array_key_exists($type, $this->levels) )
        {
            throw new LevelNotFound('Level ":level" not found', [':level' => $type]);
        }
        
        if ( $this->formatter === null )
        {
            $this->formatter = new Line();
        }
        
        $format = $this->formatter->format($message, $this->levels[$type], time(), $context);
        
        syslog($this->getLevel($type), $format);
    }
    
    public function close()
    {
        closelog();
    }
}