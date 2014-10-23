<?php

class Application_Model_Company
{
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid Table Gateway Provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        $this->setDbTable('Application_Model_DbTable_Company');
        return $this->_dbTable;
    }
    
    public function companySave($company) {
        if(isset($company['id'])) {
            $id = $company['id'];
            try {
                return $this->getDbTable()->update($company, array('id = ?' => $id));
            } catch(Exception $er) {
                return $er->getMessage();
            }
        } else {
            try {
                return $this->getDbTable()->insert($company);
            } catch(Exception $er) {
                return FALSE;
            }
        }
    }
    
    public function getAllCompany($numberOfItemsToShow,$page,$pageRange) {
        $result = $this->getDbTable()->select()
                                     ->where('status != -1');
        $paginatorObj = Zend_Paginator::factory($result);
        $paginatorObj->setItemCountPerPage($numberOfItemsToShow);
        $paginatorObj->setCurrentPageNumber($page);
        $paginatorObj->setPageRange($pageRange);
        return $paginatorObj;
    }
    
    public function deleteCompany($id) {
        if($id != "") {
            $data = array('status' => -1);
            return $this->getDbTable()->update($data, array('id = ?' => (int)$id));
        }
    }
    
    public function getCompany($id) {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $userData = array(
            'company_name'  => $row->company_name,
            'address_1'     => $row->address_1,
            'address_2'     => $row->address_2,
            'city'          => $row->city,
            'state'         => $row->state,
            'zipcode'       => $row->zipcode,
            'phone_1'       => $row->phone_1,
            'phone_2'       => $row->phone_2,
            'status'        => $row->status,
            'id'            => $row->id
        );
        return $userData;
    }
    
    public function getCompanies() {
        $result = $this->getDbTable()->select()->where('status != -1');
        return $result->query()->fetchAll();
    }
}