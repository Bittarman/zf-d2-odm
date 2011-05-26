<?php

/* Import namespaces */
use Doctrine\Common\ClassLoader,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\ODM\MongoDB\Mongo,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
    
/** 
 * Doctrine 2 ODM Resource Plugin
 * 
 * @author Ryan Mauger
 * @copyright Ryan Mauger 2012
 */
class Lupi_Resource_Odm extends Zend_Application_Resource_ResourceAbstract 
{
    public function init()
    {
    	$options = $this->getOptions();
    	$this->registerAutoloaders($options);
        
        // Config
        $config = new \Doctrine\ODM\MongoDB\Configuration();
        foreach ($options['config'] as $option => $value) {
            $method = "set" . ucfirst($option);
            $config->{$method}($value);
        }
        
        // Annotation reader
        $reader = new AnnotationReader();
        $reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\\');
        $config->setMetadataDriverImpl(new AnnotationDriver($reader, $options['documents']['dir']));
        
        $dm = DocumentManager::create(new \Doctrine\MongoDB\Connection(new \Mongo), $config);
        return $dm;    
    }
    
    public function registerAutoloaders($options)
    {
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        
        // Document classes
        $classLoader = new ClassLoader($options['documents']['namespace'], 
                                       $options['documents']['dir']);
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), $options['documents']['namespace']);
        
    }
}