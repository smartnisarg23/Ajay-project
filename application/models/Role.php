<?php

class Application_Model_Role
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
        $this->setDbTable('Application_Model_DbTable_Role');
        return $this->_dbTable;
    }
    
    public function getAllRoles() {
        $result = $this->getDbTable()->select();
        return $result->query()->fetchAll();
    }
}