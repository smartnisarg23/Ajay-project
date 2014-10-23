<?php
class Gomow_Plugins_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        $userSession = new Zend_Session_Namespace('userLogin');        
        if($userSession->userEmail != "") {            
            $authObj = Zend_Auth::getInstance();
            if(!$authObj->hasIdentity()){
                $request->setModuleName($request->getModuleName());
                $request->setControllerName('index');
            }
            $resourceObj = new Admin_Model_Resources();
            if(!$resourceObj->validResource($request)) {
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('error');
                return;
            }
            $roleMapperObj = new Admin_Model_RolesMapper();
            $roleObj = new Admin_Model_Roles();
            $permissionMapperObj = new Admin_Model_PermissionMapper();
            if($authObj->hasIdentity()) {                
                $role_id = $userSession->roleId;                
                $roleMapperObj->getRole($role_id, $roleObj);
                $aclObj = new Zend_Acl();
                $aclObj->addRole(new Zend_Acl_Role($role_id));
                /* 
                 * Code For All Roles
                 * Error Controller
                */
                $aclObj->add(new Zend_Acl_Resource('error'));
                $aclObj->allow($role_id,'error','acces-denied');
                $aclObj->allow($role_id,'error','error');                
                
                $userAllowedControllers = $permissionMapperObj->getAllowedControllers($request->getModuleName(),$roleObj->getId());
                foreach($userAllowedControllers as $resource) {
                    $aclObj->add(new Zend_Acl_Resource($resource['controller']));
                }
                $userAllowedResources = $permissionMapperObj->getCurrentUserAllowedResources($request->getModuleName(),$roleObj->getId());
                foreach($userAllowedResources as $allowResources) {
                    $aclObj->allow($role_id,$allowResources['controller'],$allowResources['action']);
                }
                Zend_Registry::set('acl', $aclObj);                
                $currentControllerName = $request->getControllerName();
                $currentActionName = $request->getActionName();
                if(!$aclObj->isAllowed($role_id,$currentControllerName,$currentActionName)) {
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('access-denied');
                    return;
                }
            }
        }
    }
}