<?php

namespace Project;

use Project\Model\ProjectTable;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e)
    {
        $sharedEvents = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
            $controller   = $e->getTarget();
            $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

            // determine whether we need to redirect to the login route.
            // redirect line: $controller->redirect()->toRoute('zfcuser/login');
            if(!$controller->ZfcUserAuthentication()->hasIdentity() && $controller->getEvent()->getRouteMatch()->getParam('controller') != 'zfcuser'){
                $controller->redirect()->toRoute('zfcuser/login');
            }

        });
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Project\Model\ProjectTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new ProjectTable($dbAdapter);
                    $table->setLogger($sm->get('logger'));
                    return $table;
                },
                'logger' => function($sm) {
                    $writer = new \Zend\Log\Writer\Stream('data/log/project.log');
                    $logger = new \Zend\Log\Logger();
                    $logger->addWriter($writer);
                    return $logger;
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
