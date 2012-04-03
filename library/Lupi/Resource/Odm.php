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
     * The function creates a DSN (Data Source Name) object and creates on its
     * basis a new Mongo.
     * When nothing is passed as an argument, a DSN on localhost is created
     * 
     * @param type $mongodb_hostname    hostname of the mongodb instance
     * @param type $mongodb_port        port of the mongodb instance
     * @param type $mongodb_username    username of the mongodb instance
     * @param type $mongodb_password    password of the mongodb instance
     * @param type $mongodb_database    database name of the mongodb instance
     * @return \Mongo                   returns a Mongo object
     */
    public function buildDSN($mongodb_hostname='127.0.0.1', $mongodb_port='27017', $mongodb_username='', $mongodb_password = '', $mongodb_database='') {
        // local host
        if(empty($mongodb_password) && empty($mongodb_username)) {        
            $dsn = sprintf('mongodb://%s:%s/%s', $mongodb_hostname, $mongodb_port, $mongodb_database);
        }
        // remote host
        else {
            $dsn = sprintf('mongodb://%s:%s@%s:27017/%s', $mongodb_username, $mongodb_password, $mongodb_hostname, $mongodb_database);           
        }
        return new \Mongo($dsn);
    }
}