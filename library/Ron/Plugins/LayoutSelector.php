<?php

class Ron_Plugins_LayoutSelector extends Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();        
        $layout = Zend_Layout::getMvcInstance();        
        $layout->setLayout($module);
    }
}