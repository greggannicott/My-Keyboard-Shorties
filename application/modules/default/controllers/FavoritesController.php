<?php

class FavoritesController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction() {

       // Setup the user
       $user = My_Plugin_User::ReturnUser();

       // Pull in the ACL details for this app
       $acl = Zend_Registry::get('acl');

       // Pass the ACL and User details on to the view. We'll need this to
       // determine what information can be displayed
       $this->view->acl = $acl;
       $this->view->user = $user;

       // Incude css
       $this->view->headLink()->appendStylesheet('/css/FavoritesController.css');

       // Include relevant Javascript
       $this->view->headScript()->appendFile('/javascript/libraries/jquery-1.3.2.js');
       $this->view->headScript()->appendFile('/javascript/Application_Controller_Shortcuts_Index.js');

       // Find a list of favorite shortcuts that belong to this user
       $favs_mapper = new Model_ShortcutsFavoritesMapper();
       $this->view->favorites = $favs_mapper->returnUserFavorites();
       
    }


}

