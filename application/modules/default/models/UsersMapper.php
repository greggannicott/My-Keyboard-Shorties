<?php

class Model_UsersMapper
{

   protected $_dbTable;

   public function setDbTable($dbTable) {
      if (is_string($dbTable)) {
         $dbTable = new $dbTable();
      }
      if (!$dbTable instanceOf Zend_Db_Table_Abstract) {
         throw new Exception('Invalid data gateway provided.');
      }
      $this->_dbTable = $dbTable;
      return $this;
   }

   public function getDbTable() {
      if (null === $this->_dbTable) {
         $this->setDbTable('Model_DbTable_Users');
      }
      return $this->_dbTable;
   }

   public function save(Model_Users $users, $update_password = false) {
      // Password is ignored as we only want to use it if this is an insert
      // If it's not an insert, it resutls in the md5 value being used an converted
      // into another md5 value.
      $data = array(
         'role_id' => $users->getRoleId(),
         'username' => $users->getUsername(),
         'first_name' => $users->getFirst_name(),
         'surname' => $users->getSurname(),
         'os' => $users->getOs(),
         'created' => date('Y-m-d H:i:s'),
      );

      // If we want to include the password (or if it's an insert) we'll include
      // the password.
      if ($update_password == true OR null === ($id = $users->getId())) {
         $data['password'] = md5($users->getPassword());
      }

      if (null === ($id = $users->getId())) {
         unset($data['id']);
         $this->getDbTable()->insert($data);
      } else {
         $this->getDbTable()->update($data, array('id = ?' => $id));
      }
   }

   public function find($id, Model_Users $users) {
      $result = $this->getDbTable()->find($id);
      if (0 == count($result)) {
         return;
      }
      $row = $result->current();
      $users->setId($row->id)
                   ->setRoleid($row->role_id)
                   ->setUsername($row->username)
                   ->setPassword($row->password)
                   ->setFirst_name($row->first_name)
                   ->setSurname($row->surname)
                   ->setOs($row->os)
                   ->setCreated($row->created);
      return $users;
   }

   public function fetchAll() {
      $resultSet = $this->getDbTable()->fetchAll();
      $entries = array();
      foreach ($resultSet as $row) {
         $entry = new Model_Users();
         $entry->setId($row->id)
               ->setRoleid($row->role_id)
               ->setUsername($row->username)
               ->setPassword($row->password)
               ->setFirst_name($row->first_name)
               ->setSurname($row->surname)
               ->setOs($row->os)
               ->setCreated($row->created);
         $entries[] = $entry;
      }
      return $entries;
   }

   public function delete($id) {
      if (!is_numeric($id) || null === $id) {
         throw new Exception('ID provided is invalid');
      } else {
         $this->getDbTable()->delete(array('id = ?' => $id));
      }
      return true;
   }

   public function updatePassword($new_password) {
      $this->getDbTable()->update($data, array('id = ?' => $id));
   }

}