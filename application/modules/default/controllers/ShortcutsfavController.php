<?php

class ShortcutsfavController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction()
    {

    }

    /**
     * Toggles a favorite
     */
    public function toggleAction() {

       // Create a var to hold the results of the action
       $results = array();

       

       // Get the user's details
       $user = My_Plugin_User::ReturnUser();

       // Get the ID for the shortcut
       $request = $this->getRequest();
       $shortcut_id = $request->getParam('id');

       // Create a ShortcutsFavorites object
       $shortcutsfavorites_mapper = new Model_ShortcutsFavoritesMapper();

       // Get the value of the shortcut fav ID if it exists
       $fav_id = $shortcutsfavorites_mapper->exists($shortcut_id, $user->getId());

       // Toggle it
       if ($fav_id) {
          // it exists, so we should delete it
          $shortcutsfavorites_mapper->delete($fav_id);
          // Create the results ready to send back to the browser
          $results['type'] = "success";
          $results['action'] = "off";

       } else {
          // it doesn't exist, so create it..
          $shortcutsfavorite = new Model_ShortcutsFavorites();
          $shortcutsfavorite->setShortcut_id($shortcut_id);
          $shortcutsfavorite->setUser_id($user->getId());
          $shortcutsfavorites_mapper->save($shortcutsfavorite);
          // Create the results ready to send back to the browser
          $results['type'] = "success";
          $results['action'] = "on";
       }

       // Return a JSON object for the js to process
       $this->_helper->json($results);

    }

}







