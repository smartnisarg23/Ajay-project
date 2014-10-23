<?php

class Gomow_Action_Helpers_GetAllResources extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader
     * @return void
     */
    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

    /**
     * Returns All Resources
     *     
     * @return int
     * @throws Exception
     */
    public function getAllResources() {
        $front = Zend_Controller_Front::getInstance();
        $acl = array();
        foreach ($front->getControllerDirectory() as $module => $path) {
            //if ($module == 'admin') {
                foreach (scandir($path) as $file) {
                    if (strtolower($file) != "errorcontroller.php") {
                        if (strstr($file, "Controller.php") !== false) {
                            include_once $path . DIRECTORY_SEPARATOR . $file;
                            foreach (get_declared_classes() as $class) {
                                if (is_subclass_of($class, 'Zend_Controller_Action')) {
                                    $classes[] = $class;
                                }
                            }
                            $classes = array_unique($classes);                            
                            foreach ($classes as $class) {
                                $controllerClasses = strtolower(substr($class, 0, strpos($class, "Controller")));
                                $actions = array();
                                foreach (get_class_methods($class) as $action) {
                                    if (strstr($action, "Action") !== false) {
                                        $act = substr($action, 0, -6);
                                        $actions[] = $act;
                                    }
                                }
                                $acl[$module][$controllerClasses] = $actions;
                            }
                        }
                    }
                }
            //}
        }        
        return $acl;
    }

    /**
     * Strategy pattern: call helper as broker method
     * @param  int $month
     * @param  int $year
     * @return int
     */
    public function direct() {
        return $this->getAllResources();
    }

}