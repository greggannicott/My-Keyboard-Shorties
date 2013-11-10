<?php

class My_Plugin_User extends Zend_Controller_Plugin_Abstract {
   
   /**
    * Returns a user based on session details
    * If user does not have a session, a guest user is returned.
    * If user does have session, a user based on their details is returned.
    * @return User 
    */
   public static function ReturnUser() {
       // Set a default user up - by default they have a RoleId of 'guest'
       $user = new Model_Users();

       // If the user has a session, map the data to the user
       // This includes the appropriate RoleId.
       if (Zend_Auth::getInstance()->hasIdentity()) {
          $userInfo = Zend_Auth::getInstance()->getStorage()->read();
          $user_mapper = new Model_UsersMapper();
          $user = $user_mapper->find($userInfo->id, $user);
       }
       return $user;
   }

   /**
    * Returns the OS to use when deciding which shortcuts to display to the user
    * @return Text The OS to use when showing shortcuts
    */
   public static function ReturnUserOs() {

      $os = null;

      // Get the user's details
      $user = self::ReturnUser();

      // If the user is a guest, default to windows
      if ($user->getRoleId() == 'guest') {
         $user_session = Zend_Registry::get('user_session');
         if (isset($user_session->os)) {
            $os = $user_session->os;
         } else {
            $user_session->os = 'mac';
            $os = $user_session->os;
         }
      // Otherwise, take the value from the database
      } else {
         $os = $user->getOs();
      }

      return $os;
   }
}

?>
