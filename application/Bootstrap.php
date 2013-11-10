<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

   public function _initAutoload() {
      $autoloader = new Zend_Application_Module_Autoloader(array(
      'namespace' => '' ,
      'basePath' => APPLICATION_PATH . '/modules/default'));
      return $autoloader;
   }

   protected function _initRoute() {

      $front = Zend_Controller_Front::getInstance();
      $router = $front->getRouter();

      // Specify the API module as RESTful
      $restRoute = new Zend_Rest_Route($front, array(), array('api',));
      $router->addRoute('rest',$restRoute);

   }

   protected function _initActionHelpers() {
      $params = new My_Helper_Params();
      Zend_Controller_Action_HelperBroker::addHelper($params);

      $contexts = new My_Helper_RestContexts();
      Zend_Controller_Action_HelperBroker::addHelper($contexts);
   }

   protected function _initDb() {
      $resource = $this->getPluginResource('db');
      Zend_Registry::set('db',$resource->getDbAdapter());
   }

   protected function _initLog() {
      $log = new Zend_Log();
      $writer = new Zend_Log_Writer_Stream("../data/logs/log.txt");
      $log->addWriter($writer);
      $log->debug("Log initiated.");
      Zend_Registry::set("Log",$log);
   }

   protected function _initRoles() {
      // Initiate roles

      $acl = new Zend_Acl();

      // Setup the various roles in our system
      $acl->addRole('guest');
      $acl->addRole('member','guest'); // inherits 'guest' abilities
      $acl->addRole('admin','member'); // inherits 'member' abilities

      // Setup the various resources
      $acl->addResource('index');
      $acl->addResource('auth');
      $acl->addResource('applications');
      $acl->addResource('shortcuts');
      $acl->addResource('shortcutsfavorites');
      $acl->addResource('favorites');
      $acl->addResource('sections');
      $acl->addResource('usersettings');
      $acl->addResource('about');

      // add privileges to roles and resource combinations
      $acl->allow('guest','index','view');
      $acl->allow('guest','auth','register');
      $acl->allow('guest','auth','login');
      $acl->allow('member','auth','logout');
      $acl->allow('guest','applications','view');
      $acl->allow('admin','applications','add');
      $acl->allow('admin','applications','delete');
      $acl->allow('admin','applications','edit');
      $acl->allow('guest','shortcuts','view');
      $acl->allow('admin','shortcuts','add');
      $acl->allow('admin','shortcuts','import');
      $acl->allow('admin','shortcuts','export');
      $acl->allow('admin','shortcuts','delete');
      $acl->allow('admin','shortcuts','edit');
      $acl->allow('member','shortcutsfavorites','toggle');
      $acl->allow('member','favorites','view');
      $acl->allow('guest','sections','view');
      $acl->allow('admin','sections','add');
      $acl->allow('admin','sections','delete');
      $acl->allow('admin','sections','edit');
      $acl->allow('member','usersettings','edit');
      $acl->allow('guest','about','view');

      // Make this information availiable to all
      Zend_Registry::set("acl",$acl);

   }

   protected function _initPlaceholders() {

      $this->bootstrap('view');
      $view = $this->getResource('view');

      // Set the doc type
      $view->doctype('XHTML1_STRICT');

      // Set the page title
      $view->headTitle('Shorties')
           ->setSeparator(' - ');

      // Set the style sheet
      $view->headLink()->prependStylesheet('/css/global.css');

   }

   protected function _initSession() {
      $user_session = new Zend_Session_Namespace('guest_user');
      Zend_Registry::set('user_session',$user_session);
   }

   protected function _initNavigation() {

      // Set the default db connection here as it's not usually handled at this
      // point and we need it to build the navigation object
      $resource = $this->getPluginResource('db');
      Zend_Db_Table_Abstract::setDefaultAdapter($resource->getDbAdapter());

      // Grab a list of applications
      $applications = new Model_ApplicationsMapper();
      $application_list = new Model_Applications();
      $application_list = $applications->fetchAll();

      // Create an array for each application
      $application_array = array();
      foreach ($application_list as $application) {

         // Populate the details for the main page of the application
         $individual_application = array();
         $individual_application['label'] = $application->getName();
         $individual_application['controller'] = 'shortcuts';
         $individual_application['action'] = 'index';
         $individual_application['resource'] = 'applications';
         $individual_application['privilege'] = 'view';
         $individual_application['params']['id'] = $application->getId();

         // Take care of the pages that exist for each application (eg. add shortcut)
         //
         ## Application Details
         # Edit Application
         $individual_application['pages'][0]['label'] = 'Edit Application Details';
         $individual_application['pages'][0]['controller'] = 'applications';
         $individual_application['pages'][0]['action'] = 'edit';
         $individual_application['pages'][0]['resource'] = 'applications';
         $individual_application['pages'][0]['privilege'] = 'edit';
         $individual_application['pages'][0]['params']['id'] = $application->getId();
         # Delete Application
         $individual_application['pages'][1]['label'] = 'Delete Application';
         $individual_application['pages'][1]['controller'] = 'applications';
         $individual_application['pages'][1]['action'] = 'delete';
         $individual_application['pages'][1]['resource'] = 'applications';
         $individual_application['pages'][1]['privilege'] = 'delete';
         $individual_application['pages'][1]['params']['id'] = $application->getId();
         
         ## Shortcuts
         # Add New Shortcut
         $individual_application['pages'][2]['label'] = 'Add New Shortcut';
         $individual_application['pages'][2]['controller'] = 'shortcuts';
         $individual_application['pages'][2]['action'] = 'add';
         $individual_application['pages'][2]['resource'] = 'shortcuts';
         $individual_application['pages'][2]['privilege'] = 'add';
         $individual_application['pages'][2]['params']['id'] = $application->getId();
         # Import Shortcuts
         $individual_application['pages'][3]['label'] = 'Bulk Import Shortcuts';
         $individual_application['pages'][3]['controller'] = 'shortcuts';
         $individual_application['pages'][3]['action'] = 'import';
         $individual_application['pages'][3]['resource'] = 'shortcuts';
         $individual_application['pages'][3]['privilege'] = 'import';
         $individual_application['pages'][3]['params']['id'] = $application->getId();
         # Export Shortcuts
         $individual_application['pages'][4]['label'] = 'Export Shortcuts';
         $individual_application['pages'][4]['controller'] = 'shortcuts';
         $individual_application['pages'][4]['action'] = 'export';
         $individual_application['pages'][4]['resource'] = 'shortcuts';
         $individual_application['pages'][4]['privilege'] = 'export';
         $individual_application['pages'][4]['params']['id'] = $application->getId();
         # Edit Shortcut
         $individual_application['pages'][5]['label'] = 'Edit Shortcut';
         $individual_application['pages'][5]['controller'] = 'shortcuts';
         $individual_application['pages'][5]['action'] = 'edit';
         $individual_application['pages'][5]['resource'] = 'shortcuts';
         $individual_application['pages'][5]['privilege'] = 'edit';
         # Delete Shortcut
         $individual_application['pages'][6]['label'] = 'Delete Shortcut';
         $individual_application['pages'][6]['controller'] = 'shortcuts';
         $individual_application['pages'][6]['action'] = 'delete';
         $individual_application['pages'][6]['resource'] = 'shortcuts';
         $individual_application['pages'][6]['privilege'] = 'delete';
         
         ## Sections
         # Add New Section
         $individual_application['pages'][7]['label'] = 'Add New Section';
         $individual_application['pages'][7]['controller'] = 'sections';
         $individual_application['pages'][7]['action'] = 'add';
         $individual_application['pages'][7]['resource'] = 'sections';
         $individual_application['pages'][7]['privilege'] = 'add';
         $individual_application['pages'][7]['params']['id'] = $application->getId();
         # Edit Shortcut
         $individual_application['pages'][8]['label'] = 'Edit Section';
         $individual_application['pages'][8]['controller'] = 'sections';
         $individual_application['pages'][8]['action'] = 'edit';
         $individual_application['pages'][8]['resource'] = 'sections';
         $individual_application['pages'][8]['privilege'] = 'edit';
         # Delete Shortcut
         $individual_application['pages'][9]['label'] = 'Delete Section';
         $individual_application['pages'][9]['controller'] = 'sections';
         $individual_application['pages'][9]['action'] = 'delete';
         $individual_application['pages'][9]['resource'] = 'sections';
         $individual_application['pages'][9]['privilege'] = 'delete';

         // Add the application to the list of applications
         array_push($application_array, $individual_application);
      }

      // Now build our container
      $container = new Zend_Navigation(array(
         array(
            'label' => 'Front Page',
            'controller' => 'index',
            'action' => 'index',
            'order' => -100, // Make sure home is the first page.
            'pages' => array(
               array(
                  'label' => 'About This Site',
                  'controller' => 'about',
                  'action' => 'index',
                  'resource' => 'about',
                  'privilege' => 'view',
               ),
               array(
                  'label' => 'Register',
                  'controller' => 'auth',
                  'action' => 'register',
                  'resource' => 'auth',
                  'privilege' => 'register',
               ),
               array(
                  'label' => 'Login',
                  'controller' => 'auth',
                  'action' => 'login',
                  'resource' => 'auth',
                  'privilege' => 'login',
               ),
               array(
                  'label' => 'Logout',
                  'controller' => 'auth',
                  'action' => 'logout',
                  'resource' => 'auth',
                  'privilege' => 'logout',
               ),
               array(
                  'label' => 'My Favorites',
                  'controller' => 'favorites',
                  'action' => 'index',
                  'resource' => 'favorites',
                  'privilege' => 'view',
               ),
               array(
                  'label' => 'Add Application',
                  'controller' => 'applications',
                  'action' => 'add',
                  'resource' => 'applications',
                  'privilege' => 'add',
               ),
               array(
                  'label' => 'My Settings',
                  'controller' => 'usersettings',
                  'action' => 'index',
                  'resource' => 'usersettings',
                  'privilege' => 'edit',
                  'pages' => array(
                     array(
                        'label' => 'User Profile',
                        'controller' => 'usersettings',
                        'action' => 'profile',
                     ),
                     array(
                        'label' => 'Update Password',
                        'controller' => 'usersettings',
                        'action' => 'password',
                     ),
                     array(
                        'label' => 'Site Settings',
                        'controller' => 'usersettings',
                        'action' => 'site',
                     ),
                  )
               ),
               array(
                  'label' => 'All Applications',
                  'controller' =>'applications',
                  'action' => 'index',
                  'pages' => $application_array
               )
            )
         )
      ));
      
      Zend_Registry::set('Zend_Navigation', $container);
   }

   protected function _initSideMenu() {
      $this->bootstrap('View');
      $view = $this->getResource('View');

      $view->placeholder('sidebar')
           ->setPrefix("<fieldset>\n")
           ->setSeparator("</fieldset><fieldset>\n")
           ->setPostfix("</fieldset>\n");

   }

}

