<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlugins(){
        $front = Zend_Controller_Front::getInstance();        
        $front->registerPlugin(new Ron_Plugins_LoginCheck());
        $front->registerPlugin(new Ron_Plugins_SessionCheck());        
    }
}