<?php

class ShortcutsController extends Zend_Controller_Action
{

   public function init() {
      //// Include the site menu
      $this->view->render('index/_sitewide_menu.phtml');
   }

    public function indexAction()
    {
          // action body
          $request = $this->getRequest();

          // Pull in the ACL details for this app
          $acl = Zend_Registry::get('acl');

          // Setup the user
          $user = My_Plugin_User::ReturnUser();
          
          // Pass the ACL and User details on to the view. We'll need this to
          // determine what information can be displayed
          $this->view->acl = $acl;
          $this->view->user = $user;

          // Set the style sheet
          $this->view->headLink()->appendStylesheet('/css/ShortcutsController.css');

          // Include relevant Javascript
          $this->view->headScript()->appendFile('/javascript/libraries/jquery-1.3.2.js');
          $this->view->headScript()->appendFile('/javascript/Application_Controller_Shortcuts_Index.js');

          if ($request->getParam('id')) {
             $application_id = $request->getParam('id');

             // Find details regarding the application
             $applications = new Model_ApplicationsMapper();
             $application = new Model_Applications();
             $application = $applications->find($application_id, $application);
             $this->view->application = $application;

             // Grab list of subsections
             $subsections_mapper = new Model_SubSectionsMapper();
             $subsections = new Model_SubSections();
             $subsections = $subsections_mapper->fetchByApplicationId($application_id);

             // Create an array to represent the list of subsections and keyboard shortcuts
             $results = array();
             foreach ($subsections as $subsection) {
                // Create some arrays - we'll need a few
                $subsection_array = array(); // Used to hold an individual subsection and it's shortcuts
                $list_of_shortcuts = array();  // Used to hold a list of each shortcut relevant to a subsection

                // Give the subsection a name and id
                $subsection_array['id'] = $subsection->getId();
                $subsection_array['name'] = $subsection->getName();

                // determine shortcuts that belong to this subsection
                $shortcuts_mapper = new Model_ShortcutsMapper();
                $shortcuts = new Model_Shortcuts();
                $shortcuts = $shortcuts_mapper->fetchBySubSectionId($subsection->id);

                // Convert each shortcut object into an array and add it to the list of shortcuts
                foreach ($shortcuts as $s) {
                   $individual_shortcut = array();  // Used to hold details regarding a singular shortcut
                   $individual_shortcut['id'] = $s->getId();
                   $individual_shortcut['application_id'] = $s->getApplication_id();
                   $individual_shortcut['shortcut'] = $s->getShortcut();
                   $individual_shortcut['shortcut_windows_override'] = $s->getShortcut_windows_override();
                   $individual_shortcut['shortcut_mac_override'] = $s->getShortcut_mac_override();
                   $individual_shortcut['shortcut_linux_override'] = $s->getShortcut_linux_override();
                   $individual_shortcut['action'] = $s->getAction();
                   $individual_shortcut['description'] = $s->getDescription();

                   // Add it to the array
                   array_push($list_of_shortcuts,$individual_shortcut);
                }

                // Associate the array of shortcuts to the subsection array
                $subsection_array['shortcuts'] = $list_of_shortcuts;

                // Add to the list of subsections
                array_push($results, $subsection_array);
             }

             $this->view->results = $results;

             // Set the title of the page:
             $this->view->headTitle()->append($this->view->application->getName().' Keyboard Shortcuts');

             // Display the sidebars
             $this->view->render('shortcuts/_sidebar_os.phtml');
             $this->view->render('shortcuts/_sidebar_actions.phtml');

          } else {
             throw new Exception("Unable to display page. No ID passed in.");
          }
    }

    public function addAction()
    {
        // action body
         $request = $this->getRequest();
         $form = new Form_ShortcutsAdd();

         $application_id = $request->getParam('id');

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               $shortcut = new Model_Shortcuts($form->getValues());
               $mapper = new Model_ShortcutsMapper();
               $mapper->save($shortcut);
               return $this->_helper->redirector->gotoSimple('index','shortcuts',null,array('id'=>$request->getParam('id')));
            }
         }

         // Populate the options available for Subsection
         $subsections = new Model_SubSectionsMapper();
         $subsection = new Model_SubSections();
         $subsectionsOptions = $subsections->fetchByApplicationIdAsArrayPairs($application_id);

         // Determine the number of subsections
         $subsection_count = count($subsectionsOptions);

         if ($subsection_count > 0) {
            foreach ($subsectionsOptions as $option) {
               $form->getElement('subsection_id')->addMultiOption($option['id'],$option['name']);
            }
         }

         // Make the subsection count and application id available to the view script
         $this->view->subsection_count = $subsection_count;
         $this->view->application_id = $application_id;

         $form->getElement('application_id')->setValue($application_id);
         $this->view->form = $form;
    }

    public function editAction()
    {
        // action body
         $request = $this->getRequest();
         $form = new Form_ShortcutsEdit();

         if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
               // Apply changes to the database
               $shortcut = new Model_Shortcuts($form->getValues());
               $mapper = new Model_ShortcutsMapper();
               $mapper->save($shortcut);
               // Determine the application ID so we can transfer the user back to the application page
               $application_id = $shortcut->getApplication_id();
               // Redirect the user back to the application page
               return $this->_helper->redirector->gotoSimple('index','shortcuts',null,array('id'=>$application_id));
            }
         } else {
            // Read in the application details so we can populate the form
            $shortcuts = new Model_ShortcutsMapper();
            $shortcut = new Model_Shortcuts();
            $shortcut = $shortcuts->find($request->getParam('id'), $shortcut);

            // Populate the options available for Subsection
            $subsections = new Model_SubSectionsMapper();
            $subsection = new Model_SubSections();
            $subsectionsOptions = $subsections->fetchByApplicationIdAsArrayPairs($shortcut->getApplication_id());
            foreach ($subsectionsOptions as $option) {
               $form->getElement('subsection_id')->addMultiOption($option['id'],$option['name']);
            }

            // Populate form field with data relating to shortcut
            $form->getElement('application_id')->setValue($shortcut->getApplication_id());
            $form->getElement('subsection_id')->setValue($shortcut->getSubsection_id());
            $form->getElement('shortcut')->setValue($shortcut->getShortcut());
            $form->getElement('shortcut_windows_override')->setValue($shortcut->getShortcut_windows_override());
            $form->getElement('shortcut_mac_override')->setValue($shortcut->getShortcut_mac_override());
            $form->getElement('shortcut_linux_override')->setValue($shortcut->getShortcut_linux_override());
            $form->getElement('action')->setValue($shortcut->getAction());
            $form->getElement('description')->setValue($shortcut->getDescription());
            $form->getElement('id')->setValue($shortcut->getId());
         }

         $this->view->form = $form;
    }

    public function deleteAction()
    {
        // action body
       $request = $this->getRequest();
       // Delete the shortcut if the user confirms
       if ($request->getParam('delete') == 'true') {
         $shortcuts = new Model_ShortcutsMapper();
         // Note the application ID before we delete the shortcut.
         // We'll want to redirect to that application in a bit
         $shortcut = new Model_Shortcuts();
         $shortcut = $shortcuts->find($request->getParam('id'),$shortcut);
         $application_id = $shortcut->getApplication_id();
         // Delete the shortcut
         $shortcuts->delete((int) $request->getParam('id'));
         // Redirect the user back to the application page
         return $this->_helper->redirector->gotoSimple('index','shortcuts',null,array('id'=>$application_id));
       // Display the confirmation page if the user hasn't had a chance to confirm yet.
       } else {
          $shortcuts = new Model_ShortcutsMapper();
          $shortcut = new Model_Shortcuts();
          $shortcut = $shortcuts->find($request->getParam('id'),$shortcut);
          $this->view->shortcut = $shortcut;
       }
    }


    /**
     * Allows user to import shortcuts using a CSV file
     */
    public function importAction() {

      $request = $this->getRequest();

      // Determine the application ID we're working with
      $application_id = $request->getParam('id');

      // If it is a post, upload the file and process the shortcuts
      if ($request->isPost()) {

         // Declare some variables we'll need
         $shortcuts = array();   # will hold shortcuts to process
         $sections_created = array();  # will hold a list of newly created sections

         // Create an object to process the upload
         $upload = new Zend_File_Transfer_Adapter_Http();

         // Process the upload of the file so it's present on the server
         $upload->receive();

         // Find out the location of the file
         $csv_file_path = $upload->getFileName();

         //@TODO: Validate the csv file before processing it

         // Read in the csv file and create an array of the new shortcuts to be created
         $row_number = 0;
         if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
             while (($row = fgetcsv($handle, 1000, ",",'"')) !== FALSE) {
                 $row_number++;

                 // Skip past the first row - it's a heading...
                 if ($row_number == 1)
                    continue;

                 // Create an array to hold the details for this particular shortcut
                 $shortcut = array();
                 $shortcut['section'] = $row['0'];
                 $shortcut['shortcut'] = $row['1'];
                 if (!empty($row['2'])) {$shortcut['shortcut_windows_override'] = $row['2'];} else {$shortcut['shortcut_windows_override'] = null;}
                 if (!empty($row['3'])) {$shortcut['shortcut_mac_override'] = $row['3'];} else {$shortcut['shortcut_mac_override'] = null;}
                 if (!empty($row['4'])) {$shortcut['shortcut_linux_override'] = $row['4'];} else {$shortcut['shortcut_linux_override'] = null;}
                 $shortcut['action'] = $row['5'];
                 if (!empty($row['6'])) {$shortcut['description'] = $row['6'];} else {$shortcut['description'] = null;}

                 // Add the shortcut to the list of shortcuts being processed
                 array_push($shortcuts,$shortcut);
             }
             // Close the file - we're done with it
             fclose($handle);
         }

         // Loop through the list of shortcuts
         $sections_mapper = new Model_SubSectionsMapper();
         foreach ($shortcuts as $shortcut) {

            // See whether we need to create any new sections - create them if required

            $section_name = $shortcut['section'];
            $section_id = null;
            // Note the section id if it exists. If it doesn't, create it
            if (is_null($section_id = $sections_mapper->fetchBySectionName($section_name, $application_id))) {
               // If the section does not exist, create it
               $subsection = new Model_SubSections();
               $subsection->setName($section_name);
               $subsection->setApplication_id($application_id);
               $sections_mapper->save($subsection);
               // Grab the ID
               $section_id = $sections_mapper->fetchBySectionName($section_name, $application_id);
               // Add it to the list of sections created (this will be used on the results page)
               array_push($sections_created,$section_name);
            }

            // Create the relevant shortcut
            $shortcuts_mapper = new Model_ShortcutsMapper();
            $shortcut_object = new Model_Shortcuts();
            $shortcut_object->setApplication_id($application_id);
            $shortcut_object->setSubsection_id($section_id);
            $shortcut_object->setShortcut($shortcut['shortcut']);
            if (!is_null($shortcut['shortcut_windows_override'])) {$shortcut_object->setShortcut_windows_override($shortcut['shortcut_windows_override']);}
            if (!is_null($shortcut['shortcut_mac_override'])) {$shortcut_object->setShortcut_mac_override($shortcut['shortcut_mac_override']);}
            if (!is_null($shortcut['shortcut_linux_override'])) {$shortcut_object->setShortcut_linux_override($shortcut['shortcut_linux_override']);}
            $shortcut_object->setAction($shortcut['action']);
            $shortcut_object->setDescription($shortcut['description']);
            $shortcuts_mapper->save($shortcut_object);

            // Cleanup
            $shortcut_object = null;
         }

         // Make relevant data available to the view
         $this->view->application_id = $application_id;
         $this->view->sections_created = $sections_created;
         $this->view->shortcuts = $shortcuts;

         // Display the confirmation page (rather than the default view)
         $this->render('import_success');

      }

      // If the upload hasn't taken place, prepare the form
      $form = new Form_ShortcutsImport();

      // Set the hidden field application id
      $form->getElement('id')->setValue($application_id);

      // Let the view know which form to use
      $this->view->form = $form;

      // The script will now display the default view.

    }

    public function exportAction() {

       $request = $this->getRequest();
       $export_type = 'csv';
       $application_id = $request->getParam('id');

       // Set the style sheet
       $this->view->headLink()->appendStylesheet('/css/ShortcutsController.css');

       if ($export_type == 'csv') {

          // Get application name
          $applications_mapper = new Model_ApplicationsMapper();
          $application = new Model_Applications();
          $application = $applications_mapper->find($application_id, $application);

          // Replace any forward slashes in application name with an underscore.
          // Forward slashes in the file name cause the export to fail.
          $application_name = eregi_replace('/+', '_', $application->getName());

          // Create a string to hold the CSV file name
          $csv_path = 'exports\\'.strtolower($application_name).'_'.date("Ymd").'_'.uniqid().'.csv';

          // Open a file stream
          $fp = fopen($csv_path, 'w');

          // Add the heading line
          $heading = "Section,Shortcut,Windows Override (optional),Mac Override (optional),Linux Override (optional),Action,Notes (optional)\n";
          fwrite($fp,$heading);

          $shortcuts_lines = array();

          // Grab an array of all the shortcuts
          $shortcuts_mapper = new Model_ShortcutsMapper();
          $shortcuts = $shortcuts_mapper->fetchByApplicationId($application_id);

          // Take each shortcut and create a string line for each and pass through fputcsv()
          foreach ($shortcuts as $shortcut) {
             //@TODO: Make sure the output matches the structure that gets imported - including headings
             $line = My_Plugin_Section::ReturnName($shortcut->getSubsection_id()).','.$shortcut->getShortcut().','.$shortcut->getShortcut_windows_override().','.$shortcut->getShortcut_mac_override().','.$shortcut->getShortcut_linux_override().','.$shortcut->getAction().','.$shortcut->getDescription();
             fputcsv($fp,split(',', $line));
          }

          // Pass the view the URL of the newly created CSV file
          $this->view->csv_file_path = '\\'.$csv_path;
       }

    }


}







