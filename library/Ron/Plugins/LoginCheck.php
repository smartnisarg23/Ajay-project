<?php

class Ron_Plugins_LoginCheck extends Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        $userSession = new Zend_Session_Namespace('userLogin');
        if($userSession->userEmail == "") {
            $request->setModuleName($request->getModuleName())
                    ->setControllerName('users')
                    ->setActionName('login')
                    ->isDispatched(false);
        }            
    }
}