<?php

class CompanyController extends Zend_Controller_Action
{
    protected $_numberOfItemsToShow = null;
    protected $_page = null;
    protected $_pageRange = null;
    protected $_flashMessanger = null;
    protected $_companyObj;


    public function init()
    {
        $this->_pageRange = 5;
        $this->_numberOfItemsToShow = 20;
        $this->_flashMessanger = $this->_helper->getHelper('FlashMessenger');
        $this->_companyObj = new Application_Model_Company();
    }

    public function indexAction()
    {
        $this->_page = $this->view->page = $this->getParam('page',1);
        $this->view->numberOfItemsToShow = $this->_numberOfItemsToShow;
        $this->view->messages = $this->_flashMessanger->getMessages();
        $this->view->companyData = $this->_companyObj->getAllCompany($this->_numberOfItemsToShow, $this->_page, $this->_pageRange);
    }

    public function addAction()
    {
        if($this->getRequest()->isPost()) {
            $filters = array(
                '*'         => 'StringTrim',
                '*'         => 'StripTags',
            );
            $validators = array(
                'companyName' => 'NotEmpty',
                'address1'    => 'NotEmpty',
                'city'        => 'NotEmpty',
                'state'       => 'NotEmpty',
                'zipcode'     => 'NotEmpty',
                'phone1'      => 'NotEmpty',
                'status'      => 'NotEmpty',
            );
            $userData = $this->getAllParams();
            $input = new Zend_Filter_Input($filters, $validators, $userData);
            if($input->isValid()) {
                $data = array(
                    'company_name'  => $this->getParam('companyName'),
                    'address_1'     => $this->getParam('address1'),
                    'address_2'     => $this->getParam('address2'),
                    'city'          => $this->getParam('city'),
                    'state'         => $this->getParam('state'),
                    'zipcode'       => $this->getParam('zipcode'),
                    'phone_1'       => $this->getParam('phone1'),
                    'phone_2'       => $this->getParam('phone2'),
                    'status'        => $this->getParam('status')
                );
                $result = $this->_companyObj->companySave($data);
                if($result !== FALSE) {
                    $this->_flashMessanger->addMessage(array('success' => 'Company Successfully Added.'));
                    $this->redirect('company/index');
                } else {
                    $this->_flashMessanger->addMessage(array('danger' => 'Company Not Added.'));
                    $this->view->companyData = $data;
                }
            } else {
                $this->view->error = array('error'=>'Please fill form properly.');
            }
        }
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if($id != "") {
            $companyData = $this->_companyObj->getCompany($id);
        }        
        $this->view->companyData = $companyData;
        if($this->getRequest()->isPost()) {
            $filters = array(
                '*'         => 'StringTrim',
                '*'         => 'StripTags',
            );
            $validators = array(
                'companyName' => 'NotEmpty',
                'address1'    => 'NotEmpty',
                'city'        => 'NotEmpty',
                'state'       => 'NotEmpty',
                'zipcode'     => 'NotEmpty',
                'phone1'      => 'NotEmpty',
                'status'      => 'NotEmpty',
            );
            $data = $this->getAllParams();
            $input = new Zend_Filter_Input($filters, $validators, $data);
            if($input->isValid()) {
                $data = array(
                    'company_name'  => $this->getParam('companyName'),
                    'address_1'     => $this->getParam('address1'),
                    'address_2'     => $this->getParam('address2'),
                    'city'          => $this->getParam('city'),
                    'state'         => $this->getParam('state'),
                    'zipcode'       => $this->getParam('zipcode'),
                    'phone_1'       => $this->getParam('phone1'),
                    'phone_2'       => $this->getParam('phone2'),
                    'status'        => $this->getParam('status'),
                    'id'            => $id   
                );
                $result = $this->_companyObj->companySave($data);
                if($result !== FALSE) {
                    $this->_flashMessanger->addMessage(array('success' => 'Compnay Successfully Updated.'));
                    $this->redirect('company/index');
                } else {
                    $this->_flashMessanger->addMessage(array('danger' => 'Company Not Updated.'));
                    $this->view->companyData = $data;
                }
            } else {
                $this->view->error = array('error'=>'Please fill form properly.');
            }
        }
    }

    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if($id == "") {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }
        $result = $this->_companyObj->deleteCompany($id);        
        if($result == TRUE) {
            $this->_flashMessanger->addMessage(array('success' => 'Company Successfully Deleted.'));
            $this->redirect('company/index');
        } else {
            $this->_flashMessanger->addMessage(array('danger' => 'Company not Successfully Deleted.Please try Again.'));
            $this->redirect('company/index');
        }
    }
}