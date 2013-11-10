<?php

class UsersettingsController extends Zend_Controller_Action
{

    protected $_flashMessenger = null;

    public function init()
    {
       //// Include the site menu
       $this->view->render('index/_sitewide_menu.phtml');
       
       $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
       $this->initView();
    }

    public function indexAction()
    {
       // Set the style sheet
       $this->view->headLink()->appendStylesheet('/css/UsersettingsController.css');
       $this->view->messages = $this->_flashMessenger->getMessages();
    }

    public function profileAction() {
      $request = $this->getRequest();
      $form = new Form_UsersettingsProfile();

      // Get the current user
      $user = My_Plugin_User::ReturnUser();

      if ($this->getRequest()->isPost()) {

         // Update the user in the database
         $users_mapper = new Model_UsersMapper();
         $user->setUsername($request->getParam('username'));
         $user->setFirst_name($request->getParam('first_name'));
         $user->setSurname($request->getParam('surname'));
         $users_mapper->save($user);

         // Add a message to FlashMessenger
         $this->_flashMessenger->addMessage('Your user profile has been updated.');

         // Redirect the user
         return $this->_helper->redirector->gotoSimple('index','usersettings',null);

      } else {
         // Set the OS to the current user config
         $form->getElement('username')->setValue($user->getUsername());
         $form->getElement('first_name')->setValue($user->getFirst_name());
         $form->getElement('surname')->setValue($user->getSurname());

         $this->view->form = $form;
      }
    }

    public function passwordAction() {
      $request = $this->getRequest();
      $form = new Form_UsersettingsPassword();
      $error = false;

      // Get the current user
      $user = My_Plugin_User::ReturnUser();

      if ($request->isPost()) {
         if ($form->isValid($_POST)) {
            // Check that the 'existing' password provided matches the user's password
            if ($user->getPassword() != md5($request->getParam('existing_password'))) {
               $error = true;
               $error_message = "You did not enter the correct 'existing password'";
            }

            // Update the user in the database
            $users_mapper = new Model_UsersMapper();
            $user->setPassword($request->getParam('new_password'));
            $users_mapper->save($user, true);

            // Only if the change worked do we want to redirect. Otherwise, we'll re-output the form
            if ($error == false) {
               // Add a message to FlashMessenger
               $this->_flashMessenger->addMessage('Your password has been updated.');

               // Redirect the user
               return $this->_helper->redirector->gotoSimple('index','usersettings',null);
            }
         }
      }

      $this->view->form = $form;
      
    }

    public function siteAction()
    {
      $request = $this->getRequest();
      $form = new Form_UsersettingsSite();

      // Get the current user
      $user = My_Plugin_User::ReturnUser();

      if ($this->getRequest()->isPost()) {
         
         // Update the user in the database
         $users_mapper = new Model_UsersMapper();
         $user->setOs($request->getParam('os'));
         $users_mapper->save($user);

         // Add a message to FlashMessenger
         $this->_flashMessenger->addMessage('Your site settings have been updated.');

         // Redirect the user
         return $this->_helper->redirector->gotoSimple('index','usersettings',null);

      } else {
         // Set the OS to the current user config
         $form->getElement('os')->setValue($user->getOs());

         $this->view->form = $form;
      }

    }

    public function changeosAction() {

       $request = $this->getRequest();

       // Get the current user
       $user = My_Plugin_User::ReturnUser();

       if ($user->getRoleId() == 'guest') {
         $user_session = Zend_Registry::get('user_session');
         $user_session->os = $request->getParam('os');
       } else {
         // Update the user in the database
         $users_mapper = new Model_UsersMapper();
         $user->setOs($request->getParam('os'));
         $users_mapper->save($user);
       }

       // If we have an application id, redirect the user to the application page
       if ($request->getParam('application_id') != '') {
          return $this->_helper->redirector->gotoSimple('index','shortcuts',null,array('id'=>$request->getParam('application_id')));
       }

    }


}

