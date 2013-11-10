<?php

class My_Plugin_AcceptHandler extends Zend_Controller_Plugin_Abstract {

   // This class is based on this tutorial: http://weierophinney.net/matthew/archives/233-Responding-to-Different-Content-Types-in-RESTful-ZF-Apps.html

   public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
      if (!$request instanceof Zend_Controller_Request_Http) {
         return;
      }

      $log = Zend_Registry::get("Log");
      $header = $request->getHeader('Accept');
      $log->debug("Accept: ".$header);
      switch (true) {
         case (strstr($header, 'application/json')):
             $request->setParam('format', 'json');
             break;
         case (strstr($header, 'application/xml')
               && (!strstr($header, 'html'))):
             $request->setParam('format', 'xml');
             break;
         default:
             break;
      }
   }
}

?>
