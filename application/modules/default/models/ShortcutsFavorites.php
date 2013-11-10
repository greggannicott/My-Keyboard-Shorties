<?php

class Model_ShortcutsFavorites
{

   protected $_id;
   protected $_shortcut_id;
   protected $_user_id;
   protected $_created;

   public function __construct(array $options = null) {
      if (is_array($options)) {
         $this->setOptions($options);
      }
   }

   public function __set($name, $value) {
      $method = 'set' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid shortcuts favorites property');
      }
      $this->$method($value);
   }

   public function __get($name) {
      $method = 'get' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid shortcuts favorites property');
      }
      return $this->$method();
   }

   public function setOptions(array $options) {
      $methods = get_class_methods($this);
      foreach ($options as $key => $value) {
         $method = 'set'.ucfirst($key);
         if (in_array($method, $methods)) {
            $this->$method($value);
         }
      }
      return $this;
   }

   public function setId($id) {
      $this->_id = (int) $id;
      return $this;
   }

   public function getId() {
      return $this->_id;
   }

   public function setShortcut_id($id) {
      $this->_shortcut_id = (int) $id;
      return $this;
   }

   public function getShortcut_id() {
      return $this->_shortcut_id;
   }

   public function setUser_id($id) {
      $this->_user_id = (int) $id;
      return $this;
   }

   public function getUser_id() {
      return $this->_user_id;
   }

   public function setCreated($created) {
      $this->_created = $created;
      return $this;
   }

   public function getCreated() {
      return $this->_created;
   }

}

