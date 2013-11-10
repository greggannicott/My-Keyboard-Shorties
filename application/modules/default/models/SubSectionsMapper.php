<?php

class Model_SubSectionsMapper
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
         $this->setDbTable('Model_DbTable_SubSections');
      }
      return $this->_dbTable;
   }

   public function save(Model_SubSections $subsections) {
      $data = array(
         'application_id' => $subsections->getApplication_id(),
         'name' => $subsections->getName(),
         'created' => date('Y-m-d H:i:s'),
      );

      if (null === ($id = $subsections->getId())) {
         unset($data['id']);
         $this->getDbTable()->insert($data);
      } else {
         $this->getDbTable()->update($data, array('id = ?' => $id));
      }
   }

   public function find($id, Model_SubSections $subsections) {
      $result = $this->getDbTable()->find($id);
      if (0 == count($result)) {
         return;
      }
      $row = $result->current();
      $subsections->setId($row->id)
                   ->setApplication_id($row->application_id)
                   ->setName($row->name)
                   ->setCreated($row->created);
      return $subsections;
   }

   public function fetchAll() {
      $resultSet = $this->getDbTable()->fetchAll();
      $entries = array();
      foreach ($resultSet as $row) {
         $entry = new Model_SubSections();
         $entry->setId($row->id)
               ->setApplication_id($row->application_id)
               ->setName($row->name)
               ->setCreated($row->created);
         $entries[] = $entry;
      }
      return $entries;
   }

   public function fetchByApplicationId($application_id) {
      $entries = array();
      $select = $this->getDbTable()->select();
      $select->where('application_id = ?', $application_id);
      $resultSet = $this->getDbTable()->fetchAll($select);
      foreach ($resultSet as $row) {
         $entry = new Model_SubSections();
         $entry->setId($row->id)
               ->setApplication_id($row->application_id)
               ->setName($row->name)
               ->setCreated($row->created);
         $entries[] = $entry;
      }
      return $entries;
   }

   /**
    * Returns an array containing an id and name for each section relating to the application id provided.
    *
    * This can be used when populating 'select' form elements.
    * @param int $application_id
    * @return array Array containing id and name for each subsection relating to the application id provided
    */
   public function fetchByApplicationIdAsArrayPairs($application_id) {
      $subsections = array();
      $objects = $this->fetchByApplicationId($application_id);
      foreach($objects as $object) {
         $item['id'] = $object->getId();
         $item['name'] = $object->getName();
         $subsections[] = $item;
      }
      return $subsections;
   }

   public function delete($id) {
      if (!is_numeric($id) || null === $id) {
         throw new Exception('ID provided is invalid');
      } else {
         $this->getDbTable()->delete(array('id = ?' => $id));
      }
      return true;
   }

   /**
    * Checks whether a section name already exists
    * @param string $name
    * @param int $application_id
    * @return int Id of entry that already exists
    */
   public function fetchBySectionName($name, $application_id) {
      $exists = NULL;
      $select = $this->getDbTable()->select();
      $select->where('name = lower(:name) and application_id = :application_id')
             ->bind(array(':name' => $name, ':application_id' => strtolower($application_id)));
      $resultSet = $this->getDbTable()->fetchAll($select);

      // If the fav exists, associate the ID of the fav with 'exists'
      // Otherwise, leave it as null
      if ($resultSet->count() > 0) {
         foreach ($resultSet as $row) {
            $exists = $row->id;
            break;
         }
      }
      return $exists;
   }

}

