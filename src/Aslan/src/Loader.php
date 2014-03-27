<?php

namespace Aslan;

use \Aslan\Loader\Loader as AbstractLoader;

class Loader extends AbstractLoader
{
    protected $dirs = [];
    
    protected $classes = [];
    
    protected $prefixes = [];
    
    protected $namespaces = [];
    
    protected $extensions = [];
    
    public function __construct( array $option = null )
    {
        if ( $option === null )
        {
            return;
        }
        
        if ( array_key_exists('extensions', $option) )
        {
            $this->registerExtensions($option['extensions']);
        }
        
        if ( array_key_exists('namespaces', $option) )
        {
            $this->registerNamespaces($option['namespaces']);
        }
        
        if ( array_key_exists('dirs', $option) )
        {
            $this->registerDirs($option['dirs']);
        }
        
        if ( array_key_exists('prefixes', $option) )
        {
            $this->registerPrefixes($option['prefixes']);
        }
    }
    
    public function registerExtensions( array $extensions, $merge = true )
    {
        if ( $merge )
        {
            $this->extensions = array_merge($this->extensions, $extensions);
        }
        else
        {
            $this->extensions = $extensions;
        }
    }
    
    public function registerExtension( $extension )
    {
        $this->extensions[] = $extension;
    }
    
    public function unregisterExtensions( $extensions = null )
    {
        if ( $extensions === null )
        {
            $this->extensions = [];
        }
        else if ( is_array($extensions) )
        {
            $this->extensions = array_diff($this->extensions, $extensions);
        }
    }
    
    public function unregisterExtension( $extension )
    {
        if ( array_key_exists($extension, $this->extensions) )
        {
            unset($this->extensions[$extension]);
        }
    }
    
    public function getExtensions()
    {
        return $this->extensions;
    }
    
    public function registerNamespaces( array $namespaces, $merge = true )
    {
        if ( $merge )
        {
            $this->namespaces = array_merge($this->namespaces, $namespaces);
        }
        else
        {
            $this->namespaces = $namespaces;
        }
    }
    
    public function registerNamespace( $namespace, $dir )
    {
        $this->namespaces[$namespace] = $dir;
    }
    
    public function unregisterNamespaces( $namespaces = null )
    {
        if ( $namespaces === null )
        {
            $this->namespaces = [];
        }
        else if ( is_array($namespaces) )
        {
            foreach ( $namespaces as $space )
            {
                if ( array_key_exists($space, $this->namespaces) )
                {
                    unset($this->namespaces[$space]);
                }
            }
        }
    }
    
    public function unregisterNamespace( $namespaces )
    {
        if ( array_key_exists($namespaces, $this->namespaces) )
        {
            unset($this->namespaces[$namespaces]);
        }
    }
    
    public function getNamespaces()
    {
        return $this->namespaces;
    }
    
    public function registerPrefixes( array $prefixes, $merge = true )
    {
        if ( $merge )
        {
            $this->prefixes = array_merge($this->prefixes, $prefixes);
        }
        else
        {
            $this->prefixes = $prefixes;
        }
    }
    
    public function registerPrefix( $prefix, $dir )
    {
        $this->prefixes[$prefix] = $dir;
    }
    
    public function unregisterPrefixes( $prefixes = null )
    {
        if ( $prefixes === null )
        {
            $this->prefixes = [];
        }
        else if ( is_array($prefixes) )
        {
            foreach ( $prefixes as $prefix )
            {
                if ( array_key_exists($prefix, $this->prefixes) )
                {
                    unset($this->prefixes[$prefix]);
                }
            }
        }
    }
    
    public function unregisterPrefix( $prefix )
    {
        if ( array_key_exists($prefix, $this->prefixes) )
        {
            unset($this->prefixes[$prefix]);
        }
    }
    
    public function getPrefixes()
    {
        return $this->prefixes;
    }
    
    public function registerDirs( array $dirs, $merge = false )
    {
        if ( $merge )
        {
            $this->dirs = array_merge($this->dirs, $dirs);
        }
        else
        {
            $this->dirs = $dirs;
        }
    }
    
    public function registerDir( $dir )
    {
        $this->dirs[] = $dir;
    }
    
    public function unregisterDirs( $dirs = null )
    {
        if ( $dirs === null )
        {
            $this->dirs = [];
        }
        else if ( is_array($dirs) )
        {
            $this->dirs = array_diff($this->dirs, $dirs);
        }
    }
    
    public function unregisterDir( $dir )
    {
        if ( array_key_exists($dir, $this->dirs) )
        {
            unset($this->dirs[$dir]);
        }
    }
    
    public function getDirs()
    {
        return $this->dirs;
    }
    
    public function registerClasses( array $classes, $merge = false )
    {
        if ( $merge )
        {
            $this->classes = array_merge($this->classes, $classes);
        }
        else
        {
            $this->classes = $classes;
        }
    }
    
    public function registerClass( $class, $file )
    {
        $this->classes[$class] = $file;
    }
    
    public function unregisterClasses( $classes = null )
    {
        if ( $classes === null )
        {
            $this->classes = [];
        }
        else if ( is_array($classes) )
        {
            foreach ( $classes as $class )
            {
                if ( array_key_exists($class, $this->classes) )
                {
                    unset($this->classes[$class]);
                }
            }
        }
    }
    
    public function getClasses()
    {
        return $this->classes;
    }
    
    public function loadClass( $class )
    {
        $class = ltrim($class, '\\');
        
        if ( array_key_exists($class, $this->aliases) )
        {
            class_alias($this->aliases[$class], $class);
            
            return;
        }
        
        if ( array_key_exists($class, $this->classes) )
        {
            require $this->classes[$class];
        
            return;
        }
        
        $fload = function ($class, $dir = null)
        {
            if ( $classFile = $this->findClass($class, $dir) !== null )
            {
                require $classFile;
            }
        };
        
        uksort($this->namespaces, function ( $f, $s ){
	       return substr_count($f,'\\') < substr_count($s,'\\');
        });
        
        foreach ( $this->namespaces as $namespace => $dir )
        {
            if ( strpos($class, $namespace) === 0 )
            {
                $fload(substr($class, strlen($namespace)), $dir);
                
                return;
            }
        }
        
        uksort($this->prefixes, function ( $f, $s ){
		  return strlen($f) < strlen($s);
        });
        
        foreach ( $this->prefixes as $prefix => $dir )
        {
            if ( strpos($class, $prefix) === 0 )
            {
                $fload(substr($class, strlen($prefix)), $dir);
                
                return;
            }
        }
        
        $fload($class);
    }
    
    protected function findClass( $class, $dirs = null )
    {
        $file = str_replace([ '\\', '_' ], DIRECTORY_SEPARATOR, $class);
        
        if ( $dirs === null )
        {
            $dirs = array_reverse($this->dirs);
        }
        else if ( is_string($dir) )
        {
            $dirs = [$dir];
        }
        
        $extensions = array_reverse($this->extensions);
        
        foreach ( $dirs as $dir )
        {
            $fname = $dir.DIRECTORY_SEPARATOR.$file;
            
            foreach ( $extensions as $ext )
            {
                if ( is_file($fname.$ext) )
                {
                    return $fname.$ext;
                }
            }
        }
        
        return null;
    }
}