<?php

class Model_Shortcuts implements Zend_Acl_Resource_Interface
{

   protected $_id;
   protected $_application_id;
   protected $_subsection_id;
   protected $_shortcut;
   protected $_shortcut_windows_override;
   protected $_shortcut_mac_override;
   protected $_shortcut_linux_override;
   protected $_action;
   protected $_description;
   protected $_created;

   public function __construct(array $options = null) {
      if (is_array($options)) {
         $this->setOptions($options);
      }
   }

   public function __set($name, $value) {
      $method = 'set' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid shortcut property');
      }
      $this->$method($value);
   }

   public function __get($name) {
      $method = 'get' . $name;
      if (('mapper' == $name) || !method_exists($this, $method)) {
         throw new Exception('Invalid shortcut property');
      }
      return $this->$method();
   }

   /**
    * Returns resource id (for use with Zend_Acl)
    * @return <type>
    */
   public function getResourceId() {
      return 'shortcuts';
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

   public function setSubsection_id($id) {
      $this->_subsection_id = (int) $id;
      return $this;
   }

   public function getSubsection_id() {
      return $this->_subsection_id;
   }

   public function setShortcut($shortcut) {
      $this->_shortcut = (string) $shortcut;
      return $this;
   }

   public function getShortcut() {
      return $this->_shortcut;
   }

   public function setShortcut_windows_override($shortcut) {
      $this->_shortcut_windows_override = (string) $shortcut;
      return $this;
   }

   public function getShortcut_windows_override() {
      return $this->_shortcut_windows_override;
   }

   public function setShortcut_mac_override($shortcut) {
      $this->_shortcut_mac_override = (string) $shortcut;
      return $this;
   }

   public function getShortcut_mac_override() {
      return $this->_shortcut_mac_override;
   }

   public function setShortcut_linux_override($shortcut) {
      $this->_shortcut_linux_override = (string) $shortcut;
      return $this;
   }

   public function getShortcut_linux_override() {
      return $this->_shortcut_linux_override;
   }

   public function setAction($action) {
      $this->_action = (string) $action;
      return $this;
   }

   public function getAction() {
      return $this->_action;
   }

   public function setDescription($description) {
      $this->_description = (string) $description;
      return $this;
   }

   public function getDescription() {
      return $this->_description;
   }

   public function setCreated($created) {
      $this->_created = $created;
      return $this;
   }

   public function getCreated() {
      return $this->_created;
   }

}

