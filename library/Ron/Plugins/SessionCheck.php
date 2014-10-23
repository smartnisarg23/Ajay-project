<?php

class Ron_Plugins_SessionCheck extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        $userSession = new Zend_Session_Namespace('userLogin');
        $userSession->setExpirationSeconds(3600);        
    }
}