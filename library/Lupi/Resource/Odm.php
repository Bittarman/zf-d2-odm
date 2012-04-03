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
class Lupi_Resource_Odm extends Zend_Application_Resource_ResourceAbstract {
    public function init() {
        /* 
         * Get a Mongo object with the corresponding DSN via buildDSN.
         * The object is passed to the create function of the DocumentManager.
         * When nothing is passed, a connection to the localhost is build.
         */
        $mongo = $this->buildDSN();
        
    	$options = $this->getOptions();
    	$this->registerAutoloaders($options);
        
        // Config
        $config = new \Doctrine\ODM\MongoDB\Configuration();
        foreach ($options['config'] as $option => $value) {
            $method = "set" . ucfirst($option);
            $config->{$method}($value);
        }
        
        // Annotation reader & driver configs
        $reader = new AnnotationReader();
				Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::registerAnnotationClasses(); 
        $config->setMetadataDriverImpl(new AnnotationDriver($reader, $options['documents']['dir']));
								
        $dm = DocumentManager::create(new \Doctrine\MongoDB\Connection($mongo), $config);
        return $dm;    
    }
    
    public function registerAutoloaders($options) {
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        
        // Document classes
        $classLoader = new ClassLoader($options['documents']['namespace'], 
                                       $options['documents']['dir']);
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), $options['documents']['namespace']);
        
    }
    
    /**
     * Creates a DSN (Data Source Name) object and creates on its
     * basis a new Mongo.
     * When nothing is passed as an argument, a DSN on localhost (127.0.0.1:27017) is created
     * 
     * @param   type $hostname      hostname of the mongodb instance
     * @param   type $port          port of the mongodb instance
     * @param   type $username      username of the mongodb instance
     * @param   type $password      password of the mongodb instance
     * @param   type $database      database name of the mongodb instance
     * @return  new \Mongo($dsn)    returns a Mongo object
     */
    public function buildDSN($hostname='127.0.0.1', $port='27017', $username='', $password = '', $database='') {
        // local host
        if(empty($password) && empty($username)) {        
            $dsn = sprintf('mongodb://%s:%s/%s', $hostname, $port, $database);
        }
        // remote host
        else {
            $dsn = sprintf('mongodb://%s:%s@%s:27017/%s', $username, $password, $hostname, $database);           
        }
        return new \Mongo($dsn);
    }
}