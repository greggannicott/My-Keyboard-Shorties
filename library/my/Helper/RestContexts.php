<?php

class My_Helper_RestContexts extends Zend_Controller_Action_Helper_Abstract {

   // This class is based on this tutorial: http://weierophinney.net/matthew/archives/233-Responding-to-Different-Content-Types-in-RESTful-ZF-Apps.html

   protected $_contexts = array(
      'xml',
      'json',
   );

   public function  preDispatch() {
        $controller = $this->getActionController();
        // NOTE: The line below is different to the tutorial.
        //       In the tutorial the writer uses an interface and associates
        //       that interface with the controller class
        if (!$controller instanceof Zend_Rest_Controller) {
            return;
        }

        $this->_initContexts();

        // Set a Vary response header based on the Accept header
        $this->getResponse()->setHeader('Vary', 'Accept');
   }

   public function _initContexts() {
      $cs = $this->getActionController()->getHelper('contextSwitch');
      $cs->setAutoJsonSerialization(false);
      foreach ($this->_contexts as $context) {
         foreach (array('index', 'post', 'get', 'put', 'delete') as $action) {
             $cs->addActionContext($action, $context);
         }
      }
      $cs->initContext();
   }

}

?>
