<?php
namespace Lupi\Controller;

abstract class Action extends \Zend_Controller_Action
{
    /**
     * @var Zend_Application
     */
    protected $bootstrap;
    
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $dm;
    
    public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs);
        $this->_helper = new \Zend_Controller_Action_HelperBroker($this);
        $this->dm = $this->getBootstrap()->getResource('odm');
        $this->init();
    }

    public function getBootstrap()
    {
        if (null === $this->bootstrap) {
            $this->bootstrap = $this->getInvokeArg('bootstrap');
        }
        return $this->bootstrap;
    }
}

