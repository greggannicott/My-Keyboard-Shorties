<?php

class Model_Users implements Zend_Acl_Role_Interface
{

   protected $_id;
   protected $_roleId = 'guest';
   protected $_username;
   protected $_password;
   protected $_first_name;
   protected $_surname;
   protected $_os;
   protected $_created;

   public function __construct(array $options = null) {
      if (is_array($options)) {
         $this->setOptions($options);
      }
   }

   public function __set($name, $value) {
      $method = 'set' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid user property');
      }
      $this->$method($value);
   }

   public function __get($name) {
      $method = 'get' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid user property');
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

   public function setRoleid($role_id) {
      $this->_roleId = $role_id;
      return $this;
   }

   public function getRoleId() {
      return $this->_roleId;
   }

   public function setUsername($username) {
      $this->_username = (string) $username;
      return $this;
   }

   public function getUsername() {
      return $this->_username;
   }

   public function setPassword($password) {
      $this->_password = (string) $password;
      return $this;
   }

   public function getPassword() {
      return $this->_password;
   }

   public function setFirst_name($first_name) {
      $this->_first_name = (string) $first_name;
      return $this;
   }

   public function getFirst_name() {
      return $this->_first_name;
   }

   public function setSurname($surname) {
      $this->_surname = (string) $surname;
      return $this;
   }

   public function getSurname() {
      return $this->_surname;
   }

   public function setOs($os) {
      $this->_os = (string) $os;
      return $this;
   }

   public function getOs() {
      return $this->_os;
   }

   public function setCreated($created) {
      $this->_created = $created;
      return $this;
   }

   public function getCreated() {
      return $this->_created;
   }

}

