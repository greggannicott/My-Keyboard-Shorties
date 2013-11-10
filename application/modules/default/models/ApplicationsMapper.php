<?php

class Model_ApplicationsMapper
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
         $this->setDbTable('Model_DbTable_Applications');
      }
      return $this->_dbTable;
   }

   public function save(Model_Applications $applications) {
      $data = array(
         'name' => $applications->getName(),
         'created' => date('Y-m-d H:i:s'),
      );

      if (null === ($id = $applications->getId())) {
         unset($data['id']);
         $this->getDbTable()->insert($data);
      } else {
         $this->getDbTable()->update($data, array('id = ?' => $id));
      }
   }

   public function find($id, Model_Applications $applications) {
      $result = $this->getDbTable()->find($id);
      if (0 == count($result)) {
         return;
      }
      $row = $result->current();
      $applications->setId($row->id)
                   ->setName($row->name)
                   ->setCreated($row->created);
      return $applications;
   }

   public function fetchAll() {
      $resultSet = $this->getDbTable()->fetchAll();
      $entries = array();
      foreach ($resultSet as $row) {
         $entry = new Model_Applications();
         $entry->setId($row->id)
               ->setName($row->name)
               ->setCreated($row->created);
         $entries[] = $entry;
      }
      return $entries;
   }

   public function fetchAllAsArray() {
      $entries = array();
      $objects = $this->fetchAll();
      foreach ($objects as $application) {
         $entries[$application->getId()]['id'] = $application->getId();
         $entries[$application->getId()]['name'] = $application->getName();
         $entries[$application->getId()]['created'] = $application->getCreated();
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

}

