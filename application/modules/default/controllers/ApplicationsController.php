<?php

class ApplicationsController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction()
    {
        // action body
                         $applications = new Model_ApplicationsMapper();
                         $this->view->entries = $applications->fetchAll();
    }

    public function addAction()
    {
        // action body
                        $request = $this->getRequest();
                        $form = new Form_ApplicationsAdd();
                
                        if ($this->getRequest()->isPost()) {
                           if ($form->isValid($request->getPost())) {
                              $application = new Model_Applications($form->getValues());
                              $mapper = new Model_ApplicationsMapper();
                              $mapper->save($application);
                              return $this->_helper->redirector('index');
                           }
                        }
                
                        $this->view->form = $form;
    }

    public function editAction()
    {
        // action body
         $request = $this->getRequest();
         $form = new Form_ApplicationsEdit();

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               $application = new Model_Applications($form->getValues());
               $mapper = new Model_ApplicationsMapper();
               $mapper->save($application);
               return $this->_helper->redirector('index');
            }
         }
         
         // Read in the application details so we can populate the form
         $applications = new Model_ApplicationsMapper();
         $application = new Model_Applications();
         $application = $applications->find($request->getParam('id'), $application);
         $form->getElement('name')->setValue($application->getName());
         $form->getElement('id')->setValue($application->getId());

         $this->view->form = $form;
    }

    public function deleteAction()
    {
        // action body
       $request = $this->getRequest();
       // Delete the application if the user confirms
       if ($request->getParam('delete') == 'true') {
         $applications = new Model_ApplicationsMapper();
         $applications->delete((int) $request->getParam('id'));
         return $this->_helper->redirector('index');
       // Display the confirmation page if the user hasn't had a chance to confirm yet.
       } else {
          $applications = new Model_ApplicationsMapper();
          $application = new Model_Applications();
          $application = $applications->find($request->getParam('id'),$application);
          $this->view->application = $application;
       }
    }


}







