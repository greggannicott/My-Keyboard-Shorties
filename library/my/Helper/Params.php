<?php

class My_Helper_Params extends Zend_Controller_Action_Helper_Abstract {

   // This class is based on this tutorial: http://weierophinney.net/matthew/archives/233-Responding-to-Different-Content-Types-in-RESTful-ZF-Apps.html

   /**
    * @var array Parameters detected in raw content body
    */
   protected $_bodyParams = array();

   /**
    * Do detection of content type, and retrieve parameters from raw body if
    * present
    * @return <type>
    */
   public function init() {
      $request     = $this->getRequest();
      $contentType = $request->getHeader('Content-Type');
      $rawBody     = $request->getRawBody();

      $log = Zend_Registry::get("Log");
      $log->debug("rawBody: ".$rawBody);
      $log->debug("Content-Type: ".$contentType);

      if (!$rawBody) {
         return;
      }
      switch (true) {
         case (strstr($contentType, 'application/json')):
             $this->setBodyParams(Zend_Json::decode($rawBody));
             break;
         case (strstr($contentType, 'application/xml')):
             $config = new Zend_Config_Xml($rawBody);
             $this->setBodyParams($config->toArray());
             break;
         default:
             if ($request->isPut()) {
                 parse_str($rawBody, $params);
                 $this->setBodyParams($params);
             }
             break;
      }
   }

   /**
    * Set body params
    * @param array $params
    * @return Zend_Controller_Action_Helper_Params
    */
   public function setBodyParams(array $params) {
      $this->_bodyParams = $params;
      return $this;
   }

   /**
    * Retrieve body parameters
    * @return array
    */
   public function getBodyParams() {
      return $this->_bodyParams;
   }

   /**
    * Get body parameter
    *
    * @param string $name
    * @return mixed
    */
   public function getBodyParam($name) {
      if ($this->hasBodyParam($name)) {
         return $this->_bodyParams[$name];
      }
      return null;
   }

   /**
    * Is the given body parameter set?
    *
    * @param string $name
    * @return bool
    */
   public function hasBodyParam($name) {
      if (isset($this->_bodyParams[$name])) {
         return true;
      }
      return false;
   }

   /**
    * Do we have any body parameters?
    *
    * @return bool
    */
   public function hasBodyParams() {
      if (!empty($this->_bodyParams)) {
         return true;
      }
      return false;
   }

   /**
    * Get submit parameters
    *
    * @return array
    */
   public function getSubmitParams() {
      if ($this->hasBodyParams()) {
         return $this->getBodyParams();
      }
      return $this->getRequest()->getPost();
   }

   public function direct() {
      return $this->getSubmitParams();
   }

}

?>
