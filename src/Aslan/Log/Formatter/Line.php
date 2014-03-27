<?php

namespace Aslan\Log\Formatter;

use Aslan\Log\FormatterInterface;

class Line implements FormatterInterface
{
    protected $format = null;
    
    protected $dateFormat = null;
    
    protected $defaultFormat = '[%date%][%type%] %message%';
    
    protected $defaultDateFormat = 'Y-m-d H:i:s';
    
    public function __construct($format = null, $dateFormat = null)
    {
        $this->format = $format;
        
        $this->dateFormat = $dateFormat;
        
        $this->defaultFormat .= PHP_EOL;
    }
    
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    public function getFormat()
    {
        return $this->format;
    }
    
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }
    
    public function getDateFormat()
    {
        return $this->dateFormat;
    }
    
    public function format($message, $type, $timestamp, array $context = null)
    {
        if ($context !== null)
        {
            $message = strtr($message, $context);
        }
        
        $date = new \DateTime();
        
        $date->setTimestamp($timestamp);
        
        $format = ($this->format)? $this->format: $this->defaultFormat;
        
        $dateFormat = ($this->dateFormat)? $this->dateFormat: $this->defaultDateFormat;
        
        return strtr($format, [
            '%message%' => $message,
            '%type%' => $type,
            '%date%' => $date->format($dateFormat)
        ]);
    }
}