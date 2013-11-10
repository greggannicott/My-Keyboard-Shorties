<?php

class My_Plugin_Shortcut extends Zend_Controller_Plugin_Abstract {
   
   
   /**
    * Returns the appropriate shortcut command to display to the user
    * @param array $shortcut An array representing a shortcut
    * @return string The shortcut command to print
    */
   public static function ReturnShortcutPerOs($shortcut) {
       $shortcut_command = null;

       // Find out the OS for the user
       $os = My_Plugin_User::ReturnUserOs();

       // Check to see if we have an override value for user's OS.
       // If we do, use it. If not, default to 'shortcut'.
       if ($os == 'windows' AND $shortcut['shortcut_windows_override'] != '') {
          $shortcut_command = $shortcut['shortcut_windows_override'];
       } elseif ($os == 'linux' AND $shortcut['shortcut_linux_override'] != '') {
          $shortcut_command = $shortcut['shortcut_linux_override'];
       } elseif ($os == 'mac' AND $shortcut['shortcut_mac_override'] != '') {
          $shortcut_command = $shortcut['shortcut_mac_override'];
       } else {
          $shortcut_command = $shortcut['shortcut'];
       }

       return $shortcut_command;
   }

}

?>
