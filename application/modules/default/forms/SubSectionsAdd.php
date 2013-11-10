<?php

class Form_SubSectionsAdd extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Add the section name element
       $this->addElement('text','name', array(
          'label' => 'Sub-Section Name'
          , 'required' => true
          , 'size' => 50
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('name'));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Add New Sub-Section',
          'disableLoadDefaultDecorators' => true,
       ));

       $label = $this->getElement('submit');
       $label->addDecorators(array(
          array('ViewHelper'),
          array('HtmlTag')
       ));

       // Add a hidden field to handle the application id
       $this->addElement('hidden','application_id');

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => 'true',
       ));

    }


}

