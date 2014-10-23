<?php

class Application_Model_User {

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid Table Gateway Provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        $this->setDbTable('Application_Model_DbTable_Users');
        return $this->_dbTable;
    }

    public function getUserDetailsDbTable() {
        $this->setDbTable('Application_Model_DbTable_UserDetails');
        return $this->_dbTable;
    }

    public function userSave($users, $detailsData) {
        if (isset($detailsData['user_id'])) {
            $id = $users['id'];
            try {
                $this->getDbTable()->update($users, array('id = ?' => $id));
                $detailsData['user_id'] = $id;
                $detailsData['action'] = 'update';
                $this->userDetailSave($detailsData);
                return $id;
            } catch (Exception $er) {
                return $er->getMessage();
            }
        } else {
            try {
                $detailsData['user_id'] = $this->getDbTable()->insert($users);
                $detailsData['action'] = 'insert';
                $this->userDetailSave($detailsData);
                return $detailsData['user_id'];
            } catch (Exception $er) {
                return FALSE;
            }
        }
    }

    public function userDetailSave($detailsData) {
        
        if (!empty($detailsData)) {
            if ($detailsData['action'] == 'insert') {
                try{
                    unset($detailsData['action']);
                    $this->getUserDetailsDbTable()->insert($detailsData);
                } catch (Exception $er) { 
                    return $er->getMessage();
                }
            } else {
                try{
                    unset($detailsData['action']);
                    return $this->getUserDetailsDbTable()->update($detailsData, array('user_id = ?' => $detailsData['user_id']));
                } catch (Exception $er) { 
                    return $er->getMessage();
                }
            }
        }
    }

    public function getAllUsers($numberOfItemsToShow, $page, $pageRange) {
        $result = $this->getDbTable()->select()
                ->setIntegrityCheck(false)
                ->from(array('um' => 'users'), array('firstname', 'lastname', 'email', 'username', 'status', 'id'))
                ->join(array('rm' => 'role_master'), 'um.role_id = rm.id', array('role' => 'role_name'))
                ->where('um.status != -1');
        $paginatorObj = Zend_Paginator::factory($result);
        $paginatorObj->setItemCountPerPage($numberOfItemsToShow);
        $paginatorObj->setCurrentPageNumber($page);
        $paginatorObj->setPageRange($pageRange);
        return $paginatorObj;
    }

    public function deleteUser($id) {
        if ($id != "") {
            $data = array('status' => -1);
            return $this->getDbTable()->update($data, array('id = ?' => (int) $id));
        }
    }

    public function getUser($id) {
        $resultUser = $this->getDbTable()->find($id);
        if (0 == count($resultUser)) {
            return;
        }
        $rowUser = $resultUser->current();
        $select = $this->getUserDetailsDbTable()->select()->where('user_id = ?', $id);
        $rowsUserDetail = $this->getUserDetailsDbTable()->fetchAll($select)->toArray();
        $userData = array(
            'firstname' => $rowUser->firstname,
            'lastname' => $rowUser->lastname,
            'email' => $rowUser->email,
            'username' => $rowUser->username,
            'password' => $rowUser->password,
            'status' => $rowUser->status,
            'role_id' => $rowUser->role_id,
            'id' => $rowUser->id
        );
        if(!empty($rowsUserDetail))
        {
            $userData['address_1'] = $rowsUserDetail[0]['address_1'];
            $userData['address_2'] = $rowsUserDetail[0]['address_2'];
            $userData['city'] = $rowsUserDetail[0]['city'];
            $userData['state'] = $rowsUserDetail[0]['state'];
            $userData['zipcode'] = $rowsUserDetail[0]['zipcode'];
            $userData['phone_1'] = $rowsUserDetail[0]['phone_1'];
            $userData['phone_2'] = $rowsUserDetail[0]['phone_2'];
            $userData['profile_image'] = $rowsUserDetail[0]['profile_image'];
        }
        return $userData;
    }

}
