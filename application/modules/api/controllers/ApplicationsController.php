<?php

class Api_ApplicationsController extends Zend_Rest_Controller
{

   public function init() {
      // Include the API css
      $this->view->headLink()->appendStylesheet('/css/APIModule.css');
   }

    public function indexAction()
    {


       // Grab the list of applications to return to user
       $applications_mapper = new Model_ApplicationsMapper();
       $this->view->applications = $applications_mapper->fetchAllAsArray();

       // Enable the view to access 'response'. This allows us to prepare
       // header info from within the view.
       $this->view->response = $this->getResponse();
       
       $this->view->success = true;
    }

    public function getAction() {
       $this->getResponse()->appendBody("From GgetAction() returning all applications");
    }

    public function  postAction() {
       $this->getResponse()->setHttpResponseCode(503)->appendBody("The API does not currently support POST actions.");
    }

    public function  putAction() {
       $this->getResponse()->setHttpResponseCode(503)->appendBody("The API does not currently support PUT actions.");
    }

    public function deleteAction() {
       $this->getResponse()->setHttpResponseCode(503)->appendBody("The API does not currently support DELETE actions.");
    }
    
}

