<?php

class AuthController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function loginAction() {

       // This code is based on: http://zendframework.com/manual/en/learning.multiuser.authentication.html

       // If the user already has identity, let them know they can't login
       if (Zend_Auth::getInstance()->hasIdentity()) {
          $this->render('login-unrequired');
       }

       // Assign the default Database
       $db = Zend_Db_Table::getDefaultAdapter();

       // Assign the form data
       $request = $this->getRequest();

       // Create a new instance of the login form
       $loginForm = new Form_Login();

       // Has user submitted form?
       if ($request->isPost()) {

          // Is login valid
          // Note: this DOES NOT check credentials
          if ($loginForm->isValid($request->getPost())) {

             // Create new db datapter to interface with user table
             $adapter = new Zend_Auth_Adapter_DbTable(
                     $db
                     , 'users'
                     , 'username'
                     , 'password'
                     , 'MD5(?)'
                     );

             // Pass in username and password submitted by user
             $adapter->setIdentity($loginForm->getValue('username'));
             $adapter->setCredential($loginForm->getValue('password'));

             // Create an instance of Zend_Auth
             // This will hold details regarding the user's login
             $auth = Zend_Auth::getInstance();

             // Do login details match those in database?
             $result = $auth->authenticate($adapter);
             if ($result->isValid()) {

                // Store the user's data in Zend_Auth so we can get to it
                // from anywhere across the site. This will include everything
                // BUT the password.
                $userInfo = $adapter->getResultRowObject(null, 'password');
                $authStorage = $auth->getStorage();
                $authStorage->write($userInfo);

                // Redirect to the index page.
                return $this->_helper->redirector->gotoSimple('index','index',null);

             // Login failed
             } else {

                // Set error message that can be displayed to end user
                $this->view->error_message = "Sorry, the login credentials you provided were incorrect. Please try again:";

             }
          }
       }

       // Associate the form with the view
       $this->view->form = $loginForm;

    }

    public function logoutAction() {

       // Clear everything - session is cleared also!
       Zend_Auth::getInstance()->clearIdentity();

       // Redirect user to homepage
       return $this->_helper->redirector->gotoSimple('index','index',null);

    }

    public function registerAction() {

       // If the user already has identity, let them know they can't login
       if (Zend_Auth::getInstance()->hasIdentity()) {
          $this->render('register-unrequired');
       }

         $request = $this->getRequest();
         $form = new Form_Register();

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               $user = new Model_Users($form->getValues());
               $mapper = new Model_UsersMapper();
               $mapper->save($user);
               return $this->_helper->redirector->gotoSimple('index','index',null);
            }
         }

         // Find out the user's current os choice
         $user_session = Zend_Registry::get('user_session');

         $form->getElement('os')->setValue($user_session->os);
         $this->view->form = $form;
    }

}







