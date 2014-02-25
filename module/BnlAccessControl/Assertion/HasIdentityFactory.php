<?php
namespace BnlAccessControl\Assertion;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HasIdentityFactory implements FactoryInterface    
{
    public function createService(ServiceLocatorInterface $sm) 
    {
        $authService = $sm->getServiceLocator()->get('bnl_auth_servcice');
        return new HasIdentity($authService);
    }
}

?>
