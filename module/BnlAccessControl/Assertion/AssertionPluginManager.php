<?php

namespace BnlAccessControl\Assertion;

use Zend\ServiceManager\AbstractPluginManager;

class AssertionPluginManager extends AbstractPluginManager
{
    public function validatePlugin($plugin) 
    {
        return ($plugin instanceof AssertionInterface);
    }
}
?>
