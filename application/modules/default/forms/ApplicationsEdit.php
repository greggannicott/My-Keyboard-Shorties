<?php

class Form_ApplicationsEdit extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Add the Name element
       $this->addElement('text','name', array(
          'label' => 'Application Name'
          , 'required' => true
          , 'size' => 40
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,40)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('name'));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Apply Changes',
          'disableLoadDefaultDecorators' => true,
       ));

       $label = $this->getElement('submit');
       $label->addDecorators(array(
          array('ViewHelper'),
          array('HtmlTag')
       ));

       // Add the ID
       $this->addElement('hidden','id');

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => 'true',
       ));

    }
}