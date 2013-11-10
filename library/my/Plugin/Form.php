<?php

class My_Plugin_Form extends Zend_Controller_Plugin_Abstract {

   /**
    * Sets the element passed in to use the default decorator for the site.
    * @param Zend_Form_Element $element The element you wish to set to default style
    */
   public static function setDefaultLayout($element) {
       if ($element instanceof Zend_Form_Element) {
          $element->addDecorators(array(
             array('ViewHelper'),
             array('Errors',array('class' => 'form_error')),
             array('Description',array('tag' => 'p', 'class' => 'description')),
             array('HtmlTag',array('tag' => 'p')),
             array('Label',array('tag' => 'p'))
          ));
       } else {
          throw new Exception("Element passed in is not an instance of Zend_Form_Element. Unable to style element.");
       }
   }

}

?>
