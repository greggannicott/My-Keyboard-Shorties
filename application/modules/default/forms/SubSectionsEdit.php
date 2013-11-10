<?php

class Form_SubSectionsEdit extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Add the name element
       $this->addElement('text','name', array(
          'label' => 'Sub-Section Name'
          , 'size' => 50
          , 'required' => true
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('name'));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Save Changes',
          'disableLoadDefaultDecorators' => true,
       ));

       $label = $this->getElement('submit');
       $label->addDecorators(array(
          array('ViewHelper'),
          array('HtmlTag')
       ));

       // Add a hidden field to handle the application id
       $this->addElement('hidden','application_id');

       // Add a hidden field to handle the sub-section id
       $this->addElement('hidden','id');

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => 'true',
       ));

    }


}

