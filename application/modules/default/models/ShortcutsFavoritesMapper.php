<?php

class Model_ShortcutsFavoritesMapper
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
         $this->setDbTable('Model_DbTable_ShortcutsFavorites');
      }
      return $this->_dbTable;
   }

   public function save(Model_ShortcutsFavorites $shortcutsfavorites) {
      $data = array(
         'shortcut_id' => $shortcutsfavorites->getShortcut_id(),
         'user_id' => $shortcutsfavorites->getUser_id(),
         'created' => date('Y-m-d H:i:s'),
      );

      if (null === ($id = $shortcutsfavorites->getId())) {
         unset($data['id']);
         $this->getDbTable()->insert($data);
      } else {
         $this->getDbTable()->update($data, array('id = ?' => $id));
      }
   }

   public function find($id, Model_ShortcutsFavorites $shortcutsfavorites) {
      $result = $this->getDbTable()->find($id);
      if (0 == count($result)) {
         return $shortcutsfavorites;
      }
      $row = $result->current();
      $shortcutsfavorites->setId($row->id)
                   ->setShortcut_id($row->shortcut_id)
                   ->setUser_id($row->user_id)
                   ->setCreated($row->created);
      return $shortcutsfavorites;
   }

   public function fetchAll() {
      $resultSet = $this->getDbTable()->fetchAll();
      $entries = array();
      foreach ($resultSet as $row) {
         $entry = new Model_ShortcutsFavorites();
         $entry->setId($row->id)
               ->setShortcut_id($row->shortcut_id)
               ->setUser_id($row->User_id)
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

   /**
    * Checks if a 'favourite' exists
    * @param <type> $shortcut_id
    * @param <type> $user_id
    * @return <type> The ID of the shortcut or null
    */
   public function exists($shortcut_id, $user_id) {
      $exists = NULL;
      $select = $this->getDbTable()->select();
      $select->where('shortcut_id = :shortcut_id and user_id = :user_id')
             ->bind(array(':shortcut_id' => $shortcut_id, ':user_id' => $user_id));
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

   /**
    * Returns a list of the shortcuts the user has favorited
    * @return array A list of favorites shortcuts for user
    */
   public function returnUserFavorites() {

      $results = array();
      $user = My_Plugin_User::ReturnUser();
      $db = Zend_Registry::get('db');

      // Return a list of applications that the user has fav shortcuts for

      $select = new Zend_Db_Select($db);
      $select->from(array('scf' => 'shortcuts_favorites'),array());
      $select->join(array('sc' => 'shortcuts'),'scf.shortcut_id = sc.id',array());
      $select->join(array('ap' => 'applications'),'sc.application_id = ap.id',array('name','id'));
      $select->where('user_id = ?', $user->getId());
      $select->group('ap.name');
      $select->order('name');

      $applications = $db->fetchAssoc($select);

      $select = null;

      // Loop through the applications
      foreach ($applications as $application) {

         // Handle the name
         $per_application['name'] = $application['name'];
         $per_application['id'] = $application['id'];

         // Generate a list of shortcuts for this app that the user has faved.
         $select = new Zend_Db_Select($db);
         $select->from(array('scf' => 'shortcuts_favorites'),array());
         $select->join(array('sc' => 'shortcuts'),'scf.shortcut_id = sc.id');
         $select->join(array('ap' => 'applications'),'sc.application_id = ap.id',array('name'));
         $select->where('user_id = ?', $user->getId());
         $select->where('ap.id = ?', $application['id']);
         $per_application['shortcuts'] = $favorites = $db->fetchAssoc($select);
         
         array_push($results,$per_application);
      }

      return $results;
   }

}

