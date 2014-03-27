<?php

namespace Aslan\Log;

use Aslan\Log\FormatterInterface;

interface AdapterInterface
{
    /** Write to log **/
    public function log($type, $message, array $context = null);
    
    public function debug($message, array $context = null);
    
    public function info($message, array $context = null);
    
    public function notice($message, array $context = null);
    
    public function warning($message, array $context = null);
    
    public function error($message, array $context = null);
    
    public function critical($message, array $context = null);
    
    public function alert($message, array $context = null);
    
    public function emergency($message, array $context = null);
    
    /** Close log source **/
    public function close();
    
    /** Formatter **/
    public function setFormatter(FormatterInterface $formatter);
    
    public function getFormatter();
    
    /** Level **/
    public function registerLevel($type, $title);
    
    public function registerLevels(array $levels);
    
    public function registerAlias($level, $alias);
    
    public function registerAliases(array $aliases);
    
    public function getLevels();
    
    public function getAliases();
}