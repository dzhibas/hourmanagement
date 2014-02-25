<?php
namespace BnlAccessControl;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;

class Module
{
    public function getConfig()
    {
        return array(
            'service_manager' => array(
                'aliases' => array(
                  'bnl_auth_servcice' => 'zfcuser_auth_service'  
                ),
                'invokables' => array(
                    'AssertionPluginManager' => 'BnlAccessControl\Assertion\AssertionPluginManager',
                ),
            ),
            'assertion_plugins' => array(
                'factories' => array(
                    'HasIdentity' => 'BnlAccessControl\Assertion\HasIdentityFactory'
                ),
            ),
        );
    }  
    
    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()
                            ->getServiceManager();
        $assertionPlugins = $serviceManager->get('Config')['assertion_plugins'];
     
        $config = new Config($assertionPlugins);
        $assertionPluginManager = $serviceManager->get("AssertionPluginManager");
        $config->configureServiceManager($assertionPluginManager);
        //var_dump($assertionPluginManager->get('HasIdentity'));//die();
    }        
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }        
}
?>
