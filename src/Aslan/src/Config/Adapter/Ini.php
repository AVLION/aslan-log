<?php

namespace Aslan\Config\Adapter;

use Aslan\Config\Repository;

use Aslan\Config\Exception\FileNotFound;

class Ini extends Repository
{
    public function __construct( $filename, $process_sections = true )
    {
        if ( ! is_file($filename) )
        {
            throw new FileNotFound('File ":filename" not found', ['filename' => $filename]);
        }
        
        $this->merge(
            (array) parse_ini_file(
                $filename, 
                $process_sections
            )
        );
    }
}