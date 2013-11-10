<?php

class IndexController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction()
    {
        // Determine the ACL
        $acl = Zend_Registry::get('acl');

        // Setup the user
        $user = My_Plugin_User::ReturnUser();

        // Associate them with the view
        $this->view->navigation()->setAcl($acl)->setRole($user->getRoleId());
    }


}

