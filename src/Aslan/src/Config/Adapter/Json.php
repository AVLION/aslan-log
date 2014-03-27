<?php

namespace Aslan\Config\Adapter;

use Aslan\Config\Repository;

use Aslan\Config\Exception\FileNotFound;

class Json extends Repository
{
    public function __construct( $filename )
    {
        if ( ! is_file($filename) )
        {
            throw new FileNotFound('File ":filename" not found', ['filename' => $filename]);
        }
        
        $this->merge(
            (array) json_decode(
                file_get_contents(
                    $filename
                ),
                true
        ));
    }
}