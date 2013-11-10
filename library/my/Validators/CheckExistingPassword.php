<?php

class My_Validators_CheckExistingPassword extends  Zend_Validate_Abstract {

   const MISMATCH = 'mismatch';

   protected $_messageTemplates  = array(
      self::MISMATCH => "The existing password entered does not match our records"
   );

   public function isValid($value) {

      $user = My_Plugin_User::ReturnUser();

      if ($user->getPassword() != md5($value)) {
         $this->_error(self::MISMATCH);
         return false;
      }
      
      return true;

   }

}

?>
