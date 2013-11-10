<?php

class SubSectionsController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction()
    {
        // action body
          $request = $this->getRequest();
          if ($request->getParam('id')) {
             // Find details regarding the sub-sections
             $application_id = $request->getParam('id');
             $subsections = new Model_SubSectionsMapper();
             $this->view->entries = $subsections->fetchByApplicationId($application_id);
             // Find details regarding the application
             $applications = new Model_ApplicationsMapper();
             $application = new Model_Applications();
             $application = $applications->find($application_id, $application);
             $this->view->application = $application;
          } else {
             throw new Exception("Unable to display page. No ID passed in.");
          }
    }

    public function addAction()
    {
        // action body
         $request = $this->getRequest();
         $form = new Form_SubSectionsAdd();

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               $subsection = new Model_SubSections($form->getValues());
               $mapper = new Model_SubSectionsMapper();
               $mapper->save($subsection);
               return $this->_helper->redirector->gotoSimple('index','Shortcuts',null,array('id'=>$request->getParam('id')));
            }
         }
         $form->getElement('application_id')->setValue($request->getParam('id'));
         $this->view->form = $form;
    }

    public function editAction()
    {
        // action body
         $request = $this->getRequest();
         $form = new Form_SubSectionsEdit();

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               // Apply changes to the database
               $subsection = new Model_SubSections($form->getValues());
               $mapper = new Model_SubSectionsMapper();
               $mapper->save($subsection);
               // Determine the application ID so we can transfer the user back to the application page
               $application_id = $subsection->getApplication_id();
               // Redirect the user back to the application page
               return $this->_helper->redirector->gotoSimple('index','Shortcuts',null,array('id'=>$application_id));
            }
         } else {
            // Read in the application details so we can populate the form
            $subsections = new Model_SubSectionsMapper();
            $subsection = new Model_Shortcuts();
            $subsection = $subsections->find($request->getParam('id'), $subsection);
            $form->getElement('application_id')->setValue($subsection->getApplication_id());
            $form->getElement('name')->setValue($subsection->getName());
            $form->getElement('id')->setValue($subsection->getId());
         }

         $this->view->form = $form;
    }

    public function deleteAction()
    {
        // action body
       $request = $this->getRequest();
       // Delete the shortcut if the user confirms
       if ($request->getParam('delete') == 'true') {
         $subsections = new Model_SubSectionsMapper();
         // Note the application ID before we delete the shortcut.
         // We'll want to redirect to that application in a bit
         $subsection = new Model_SubSections();
         $subsection = $subsections->find($request->getParam('id'),$subsection);
         $application_id = $subsection->getApplication_id();
         // Delete the shortcut
         $subsections->delete((int) $request->getParam('id'));
         // Redirect the user back to the application page
         return $this->_helper->redirector->gotoSimple('index','Shortcuts',null,array('id'=>$application_id));
       // Display the confirmation page if the user hasn't had a chance to confirm yet.
       } else {
          $subsections = new Model_ShortcutsMapper();
          $subsection = new Model_Shortcuts();
          $subsection = $subsections->find($request->getParam('id'),$subsection);
          $this->view->subsection = $subsection;
       }
    }


}







