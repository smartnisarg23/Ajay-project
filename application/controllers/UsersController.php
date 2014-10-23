<?php
class UsersController extends Zend_Controller_Action
{
    protected $_userObj = null;
    protected $_roleObj = null;
    protected $_companyObj = null;
    protected $_numberOfItemsToShow = null;
    protected $_page = null;
    protected $_pageRange = null;
    protected $_flashMessanger = null;
    protected $_fileAdapter = null;
    public $userProfileDir = null;

    public function init()
    {
        $this->_userObj = new Application_Model_User();
        $this->_userDetailObj = new Application_Model_DbTable_UserDetails();
        $this->_roleObj = new Application_Model_Role();
        $this->_companyObj = new Application_Model_Company();
        $this->_fileAdapter = new Zend_File_Transfer_Adapter_Http();
        $this->_pageRange = 5;
        $this->_numberOfItemsToShow = 5;
        $this->_flashMessanger = $this->_helper->getHelper('FlashMessenger');
        
        $this->_fileAdapter = new Zend_File_Transfer_Adapter_Http();
        $this->userProfileDir = USER_PROFILE_IMAGE . DIRECTORY_SEPARATOR;
    }

    public function indexAction()
    {
        $this->_page = $this->view->page = $this->getParam('page',1);
        $this->view->numberOfItemsToShow = $this->_numberOfItemsToShow;
        $this->view->messages = $this->_flashMessanger->getMessages();
        $this->view->userData = $this->_userObj->getAllUsers($this->_numberOfItemsToShow, $this->_page, $this->_pageRange);
    }

    public function loginAction()
    {
        if($this->getRequest()->isPost()) {
            $filters = array(
                '*'         => 'StringTrim',
                '*'         => 'StripTags',
            );

            $validators = array(
                'email' => 'NotEmpty',
                'password' => 'NotEmpty'
            );
            
            $data = $this->getAllParams();
            $input = new Zend_Filter_Input($filters, $validators, $data);
            if($input->isValid()) {                
                $loginIdentity = $this->getParam('email');
                $rememberMe = $this->getParam('remember_me');
                $password = $this->_helper->passwordEncrypt($this->getParam('password'));
                $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
                /* Code For Checking Login with Username*/
                $authUsernameAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'users', 'username', 'password');
                $authUsernameAdapter->setIdentity($loginIdentity)
                                    ->setCredential($password);
                $authenticationObj = Zend_Auth::getInstance();
                $authUsernameResult = $authenticationObj->authenticate($authUsernameAdapter);
                /* Code For Checking Login with Email*/
                $authEmailAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'users', 'email', 'password');
                $authEmailAdapter->setIdentity($loginIdentity)
                                    ->setCredential($password);                
                $authEmailResult = $authenticationObj->authenticate($authEmailAdapter);
                
                if ($authUsernameResult->isValid() || $authEmailResult->isValid()) {
                    if ($authEmailResult->isValid()) {
                        $userData = $authEmailAdapter->getResultRowObject();
                    }
                    if ($authUsernameResult->isValid()) {
                        $userData = $authUsernameAdapter->getResultRowObject();
                    }
                    if($userData->status == 1) {
                        $userSession = new Zend_Session_Namespace('userLogin');
                        $userSession->setExpirationSeconds(3600);
                        $userSession->userId    = $userData->id;
                        $userSession->roleId    = $userData->role_id;
                        $userSession->userName  = $userData->username;
                        $userSession->userEmail = $userData->email;
                        $userSession->status = $userData->status;
                        if($rememberMe){
                            setcookie('userEmail', $userData->email, time() + (3600*24));
                        }
                        
                        $this->_redirect('users/index');
                    } else {
                        return $this->view->error = "You are deactivated by Admin";
                    }
                } else {
                    return $this->view->error = "Wrong username or password";
                }
            } else {                
                echo "<pre>"; print_r($input->getMessages()); echo "</pre>";die;
            }
        }
    }

    public function homeAction()
    {
        
    }

    public function logoutAction()
    {
        $csrSession = new Zend_Session_Namespace('userLogin');
        Zend_Session::destroy();
        $this->_redirect('users/login');
    }

    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if($id == "") {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }
        $result = $this->_userObj->deleteUser($id);        
        if($result == TRUE) {
            $this->_flashMessanger->addMessage(array('success' => 'Record Successfully Deleted.'));
            $this->redirect('users/index');
        } else {
            $this->_flashMessanger->addMessage(array('danger' => 'Record not Successfully Deleted.Please try Again.'));
            $this->redirect('users/index');
        }
    }

    public function addAction()
    {
        if($this->getRequest()->isPost()) {
            $filters = array(
                '*'         => 'StringTrim',
                '*'         => 'StripTags',
            );
            $validators = array(
                'company'   => 'NotEmpty',
                'firstname' => 'NotEmpty',
                'lastname'  => 'NotEmpty',
                'username'  => 'NotEmpty',
                'userEmail' => 'NotEmpty',
                'password'  => 'NotEmpty',
                'role'      => 'NotEmpty',
                'status'    => 'NotEmpty',
//                'address1'  => 'NotEmpty',
//                'city'      => 'NotEmpty',
//                'state'     => 'NotEmpty',
//                'zipcode'   => 'NotEmpty',
//                'phone1'    => 'NotEmpty',
            );
            $userData = $this->getAllParams();
            $input = new Zend_Filter_Input($filters, $validators, $userData);
            if($input->isValid()) {
                $emailExistsValidator = new Zend_Validate_Db_NoRecordExists(array(
                                                                                'table' => 'users',
                                                                                'field' => 'email'
                                                                            )
                                                                         );
                $usernameExistsValidator = new Zend_Validate_Db_NoRecordExists(array(
                                                                                'table' => 'users',
                                                                                'field' => 'username'
                                                                            )
                                                                         );
                $data = array(
                    'firstname'     => $this->getParam('firstname'),
                    'lastname'      => $this->getParam('lastname'),
                    'email'         => $this->getParam('userEmail'),
                    'username'      => $this->getParam('username'),
                    'password'      => $this->_helper->passwordEncrypt($this->getParam('password')),
                    'status'        => $this->getParam('status'),
                    'role_id'       => $this->getParam('role'),
                    'company_id'    => $this->getParam('company'),
                    'created'       => date('Y-m-d H:i:s')
                );
                $detailsData = array(
                    'address_1'     => $this->getParam('address1'),
                    'address_2'     => $this->getParam('address2'),
                    'city'          => $this->getParam('city'),
                    'state'         => $this->getParam('state'),
                    'zipcode'       => $this->getParam('zipcode'),
                    'phone_1'       => $this->getParam('phone1'),
                    'phone_2'       => $this->getParam('phone2')
                );
                $files = $this->_fileAdapter->getFileInfo();            
                $uploadedFileName = $files['profileImage']['name'];
                $detailsData['profile_image'] = $uploadedFileName;
                if($emailExistsValidator->isValid($this->getParam('userEmail')) && $usernameExistsValidator->isValid($this->getParam('username'))) {
                    $result = $this->_userObj->userSave($data, $detailsData);
                    if($result !== FALSE) {
                        if($uploadedFileName != "" && $files['attachments']['error'] <= 0) {
                            $dir = $this->userProfileDir.$result;
                            @mkdir($dir, 0777);
                            $this->_fileAdapter->setDestination($dir);
                            foreach($files as $file => $info) {
                                if($uploadedFileName != "") {
                                    if (!$this->_fileAdapter->isUploaded($file)) {
                                        
                                    }
                                    if (!$this->_fileAdapter->receive()) {

                                    }
                                }
                            }
                        }
                        
                        $this->_flashMessanger->addMessage(array('success' => 'Users Successfully Added.'));
                        $this->redirect('users/index');
                    } else {
                        $this->_flashMessanger->addMessage(array('danger' => 'Users Not Added.'));
                        $this->view->userData = $data;
                        $this->view->userDetailsData = $detailsData;
                    }
                } else {
                    if(!$emailExistsValidator->isValid($this->getParam('userEmail'))) {                        
                        $this->view->error = array('error'=>'Email already Exists');
                        $this->view->userData = $data;
                        $this->view->userDetailsData = $detailsData;
                    }
                    if(!$usernameExistsValidator->isValid($this->getParam('username'))) {
                        $this->view->error = array('error'=>'Username already Exists');
                        $this->view->userData = $data;
                        $this->view->userDetailsData = $detailsData;
                    }
                }
            } else {
                $this->view->error = array('error'=>'Please fill form properly.');
            }
        }        
        $this->view->roleData = $this->_roleObj->getAllRoles();
        $this->view->companyData = $this->_companyObj->getCompanies();
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if($id != "") {
            $userData = $this->_userObj->getUser($id);
        }        
        if($this->getRequest()->isPost()) {
            $filters = array(
                '*'         => 'StringTrim',
                '*'         => 'StripTags',
                'phone_1'         => 'Digits',
                'phone_2'         => 'Digits'
            );
            $validators = array(
                'firstname' => 'NotEmpty',                
                'lastname' => 'NotEmpty',                
                'userEmail' => 'NotEmpty',                
                'username' => 'NotEmpty',                
                'status' => 'NotEmpty',                
                'role' => 'NotEmpty'
            );
            $data = $this->getAllParams();
            $input = new Zend_Filter_Input($filters, $validators, $data);
            if($input->isValid()) {
                $data = array(
                    'firstname' => $this->getParam('firstname'),
                    'lastname'  => $this->getParam('lastname'),                   
                    'status'    => $this->getParam('status'),
                    'role_id'   => $this->getParam('role'),
                    'id'        => $id
                );
                $detailsData = array(
                    'user_id'        => $id,
                    'address_1'     => $this->getParam('address1'),
                    'address_2'     => $this->getParam('address2'),
                    'city'          => $this->getParam('city'),
                    'state'         => $this->getParam('state'),
                    'zipcode'       => $this->getParam('zipcode'),
                    'phone_1'       => $this->getParam('phone1'),
                    'phone_2'       => $this->getParam('phone2')
                );
                $password = $this->getParam('password');
                if($password != "") {
                    $data['password'] = $this->_helper->passwordEncrypt($this->getParam('password'));
                } else {
                    $data['password'] = $userData['password'];
                }
                $files = $this->_fileAdapter->getFileInfo();
                if(!empty($files['profileImage']['name']))
                {
                    $uploadedFileName = $files['profileImage']['name'];
                }else
                {
                    $uploadedFileName = $this->getParam('hiddenProfileImage');
                }
                $detailsData['profile_image'] = $uploadedFileName;
                $result = $this->_userObj->userSave($data, $detailsData);

                if($result !== FALSE) {
                    if($uploadedFileName != "" && $files['attachments']['error'] <= 0) {
                            $dir = $this->userProfileDir.$result;
                            @mkdir($dir, 0777);
                            $this->_fileAdapter->setDestination($dir);
                            foreach($files as $file => $info) {
                                if($uploadedFileName != "") {
                                    if (!$this->_fileAdapter->isUploaded($file)) {
                                        
                                    }
                                    if (!$this->_fileAdapter->receive()) {

                                    }
                                }
                            }
                        }
                    $this->_flashMessanger->addMessage(array('success' => 'Users Successfully Updated.'));
                    $this->redirect('users/index');
                } else {
                    $this->_flashMessanger->addMessage(array('danger' => 'Users Not Updated.'));
                    $this->view->userData = $data;
                }
            } else {
                $this->view->error = array('error'=>'Please fill form properly.');
            }
        }
        else{
            $this->view->userData = $userData;
        }
        $this->view->roleData = $this->_roleObj->getAllRoles();
    }
}