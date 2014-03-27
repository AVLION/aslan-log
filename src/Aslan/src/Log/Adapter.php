<?php

namespace Aslan\Log;

use Aslan\Log\Level;

use Aslan\Log\FormatterInterface;

abstract class Adapter
{
    protected $formatter = null;
    
    protected $levels = [
        Level::DEBUG     => 'DEBUG',
        Level::INFO      => 'INFO',
        Level::NOTICE    => 'NOTICE',
        Level::WARNING   => 'WARNING',
        Level::ERROR     => 'ERROR',
        Level::ALERT     => 'ALERT',
        Level::CRITICAL  => 'CRITICAL',
        Level::EMERGENCE => 'EMERGENCE'
    ];
    
    protected $aliases = [];
    
    protected function getLevel($level)
    {
        if ( array_key_exists($level, $this->aliases) )
        {
            return $this->aliases[$level];
        }
        
        return $level;
    }
    
    public function registerLevel($type, $title)
    {
        $this->levels[$type] = (string) $title;
    }
    
    public function registerLevels(array $levels)
    {
        foreach ($levels as $type => $title)
        {
            $this->levels[$type] = (string) $title;
        }
    }
    
    public function registerAlias($level, $alias)
    {
        $this->aliases[$level] = $alias;
    }
    
    public function registerAliases(array $aliases)
    {
        foreach ($aliases as $level => $alias)
        {
            $this->aliases[$level] = $alias;
        }
    }
    
    public function getLevels()
    {
        return $this->levels;
    }
    
    public function getAliases()
    {
        return $this->aliases;
    }
    
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }
    
    public function getFormatter()
    {
        return $this->formatter;
    }
    
    public function debug($message, array $context = null)
    {
        $this->log(Level::DEBUG, $message, $context);
    }
    
    public function info($message, array $context = null)
    {
        $this->log(Level::INFO, $message, $context);
    }
    
    public function notice($message, array $context = null)
    {
        $this->log(Level::NOTICE, $message, $context);
    }
    
    public function warning($message, array $context = null)
    {
        $this->log(Level::WARNING, $message, $context);
    }
    
    public function error($message, array $context = null)
    {
        $this->log(Level::ERROR, $message, $context);
    }
    
    public function critical($message, array $context = null)
    {
        $this->log(Level::CRITICAL, $message, $context);
    }
    
    public function alert($message, array $context = null)
    {
        $this->log(Level::ALERT, $message, $context);
    }
    
    public function emergency($message, array $context = null)
    {
        $this->log(Level::EMERGENCE, $message, $context);
    }
}