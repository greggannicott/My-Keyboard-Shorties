<?php

class Model_ShortcutsMapper
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
         $this->setDbTable('Model_DbTable_Shortcuts');
      }
      return $this->_dbTable;
   }

   public function save(Model_Shortcuts $shortcuts) {
      $data = array(
         'application_id' => $shortcuts->getApplication_id(),
         'subsection_id' => $shortcuts->getSubsection_id(),
         'shortcut' => $shortcuts->getShortcut(),
         'shortcut_windows_override' => $shortcuts->getShortcut_windows_override(),
         'shortcut_mac_override' => $shortcuts->getShortcut_mac_override(),
         'shortcut_linux_override' => $shortcuts->getShortcut_linux_override(),
         'action' => $shortcuts->getAction(),
         'description' => $shortcuts->getDescription(),
         'created' => date('Y-m-d H:i:s'),
      );

      if (null === ($id = $shortcuts->getId())) {
         unset($data['id']);
         $this->getDbTable()->insert($data);
      } else {
         $this->getDbTable()->update($data, array('id = ?' => $id));
      }
   }

   public function find($id, Model_Shortcuts $shortcuts) {
      $result = $this->getDbTable()->find($id);
      if (0 == count($result)) {
         return;
      }
      $row = $result->current();
      $shortcuts->setId($row->id)
                   ->setApplication_id($row->application_id)
                   ->setSubsection_id($row->subsection_id)
                   ->setShortcut($row->shortcut)
                   ->setShortcut_windows_override($row->shortcut_windows_override)
                   ->setShortcut_mac_override($row->shortcut_mac_override)
                   ->setShortcut_linux_override($row->shortcut_linux_override)
                   ->setAction($row->action)
                   ->setDescription($row->description)
                   ->setCreated($row->created);
      return $shortcuts;
   }

   public function fetchAll() {
      $resultSet = $this->getDbTable()->fetchAll();
      $entries = array();
      foreach ($resultSet as $row) {
         $entry = new Model_Shortcuts();
         $entry->setId($row->id)
               ->setApplication_id($row->application_id)
               ->setSubsection_id($row->subsection_id)
               ->setShortcut($row->shortcut)
               ->setShortcut_windows_override($row->shortcut_windows_override)
               ->setShortcut_mac_override($row->shortcut_mac_override)
               ->setShortcut_linux_override($row->shortcut_linux_override)
               ->setAction($row->action)
               ->setDescription($row->description)
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
         $entry = new Model_Shortcuts();
         $entry->setId($row->id)
               ->setApplication_id($row->application_id)
               ->setSubsection_id($row->subsection_id)
               ->setShortcut($row->shortcut)
               ->setShortcut_windows_override($row->shortcut_windows_override)
               ->setShortcut_mac_override($row->shortcut_mac_override)
               ->setShortcut_linux_override($row->shortcut_linux_override)
               ->setAction($row->action)
               ->setDescription($row->description)
               ->setCreated($row->created);
         $entries[] = $entry;
      }
      return $entries;
   }

   public function fetchBySubSectionId($subsection_id) {
      $entries = array();
      $select = $this->getDbTable()->select();
      $select->where('subsection_id = ?', $subsection_id);
      $resultSet = $this->getDbTable()->fetchAll($select);
      foreach ($resultSet as $row) {
         $entry = new Model_Shortcuts();
         $entry->setId($row->id)
               ->setApplication_id($row->application_id)
               ->setSubsection_id($row->application_id)
               ->setShortcut($row->shortcut)
               ->setShortcut_windows_override($row->shortcut_windows_override)
               ->setShortcut_mac_override($row->shortcut_mac_override)
               ->setShortcut_linux_override($row->shortcut_linux_override)
               ->setAction($row->action)
               ->setDescription($row->description)
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

}

