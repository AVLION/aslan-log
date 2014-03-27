<?php

namespace Aslan\Log\Adapter;

use Aslan\Log\Exception\ErrorFile;

use Aslan\Log\Exception\ErrorWriting;

use Aslan\Log\Adapter;

use Aslan\Log\Exception\LevelNotFound;

use Aslan\Log\Formatter\Line;

class File extends Adapter
{
    protected $file = null;
    
    public function __construct($filename, array $option = [])
    {
        $pathinfo = pathinfo( $filename );
            
        if ( ! is_dir( $pathinfo['dirname'] ) )
        {
            mkdir( $pathinfo['dirname'], (isset($option['mkdir'])?$option['mkdir']: '0777' ), true );
                
            chmod( $pathinfo['dirname'], (isset($option['cmod'])? $option['cmod']: '0777') );
        }
        
        $this->file = fopen($filename, (isset($option['mode'])? $option['mode']: 'a'));
        
        if ( $this->file === false )
        {
            throw new ErrorFile('Error opening file');
        }
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
        
        if ( fwrite($this->file, $format) === false )
        {
            throw new ErrorWriting( 'An error occurred while writing' );
        }
    }
    
    public function close()
    {
        fclose($this->file);
    }
}