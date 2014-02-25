<?php

namespace BnlAccessControl\Assertion;

class HasIdentity implements AssertionInterface
{
    protected $authenticationService;
    
    public function __construct($authenticationService) {
        $this->authenticationService = $authenticationService;
    }
    
    public function assert() {
        return $this->authenticationService->hasIdentity();
    }
}
?>
