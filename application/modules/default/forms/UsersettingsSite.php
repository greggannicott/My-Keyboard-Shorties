<?php

class Form_UsersettingsSite extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Add the OS element
       $this->addElement('select','os', array (
          'label' => 'Operating System',
          'required' => true,
          'multioptions' => array('windows'=>'Windows', 'mac'=>'Mac', 'linux'=>'Linux'),
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('os'));

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

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => 'true',
       ));

    }


}

