<?php

class My_Plugin_ShortcutsFavorites extends Zend_Controller_Plugin_Abstract {
   
   /**
    * Returns whether a shortcut is a favorite for user or not
    * @param int $shortcut_id
    * @return bool
    */
   public static function IsFavorite($shortcut_id) {
       $isFavorite = false;

       // Get user object
       $user = My_Plugin_User::ReturnUser();

       // Create a mapper to the shortcutsfavorites table
       $shortcutsfavorites_mapper = new Model_ShortcutsFavoritesMapper();

       // Check whether our favorite exists.
       // - If it does, an id is returned.
       // - If it doesn't, null is returned.
       // We can check this value to see whether it exists
       $fav_id = $shortcutsfavorites_mapper->exists($shortcut_id, $user->getId());

       // Now check to see whether it exists. If it exists, the user faved it already
       if ($fav_id) {
          $isFavorite = true;
       } else {
          $isFavorite = false;
       }

       return $isFavorite;
   }
}

?>
