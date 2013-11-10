<?php

class Model_SubSections implements Zend_Acl_Resource_Interface
{

   protected $_id;
   protected $_application_id;
   protected $_name;
   protected $_created;

   public function __construct(array $options = null) {
      if (is_array($options)) {
         $this->setOptions($options);
      }
   }

   public function __set($name, $value) {
      $method = 'set' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid subsection property');
      }
      $this->$method($value);
   }

   public function __get($name) {
      $method = 'get' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid subsection property');
      }
      return $this->$method();
   }

   /**
    * Returns resource id (for use with Zend_Acl)
    * @return <type>
    */
   public function getResourceId() {
      return 'sections';
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

   public function setApplication_id($id) {
      $this->_application_id = (int) $id;
      return $this;
   }

   public function getApplication_id() {
      return $this->_application_id;
   }

   public function setName($name) {
      $this->_name = (string) $name;
      return $this;
   }

   public function getName() {
      return $this->_name;
   }

   public function setCreated($created) {
      $this->_created = $created;
      return $this;
   }

   public function getCreated() {
      return $this->_created;
   }

}

