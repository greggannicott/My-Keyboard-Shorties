<?php

class Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Add the Username element
       $this->addElement('text','username', array(
          'label' => 'Username'
          , 'required' => true
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('username'));

       // Add the password element
       $this->addElement('password','password', array (
          'label' => 'Password'
          , 'required' => true
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('password'));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Login',
          'disableLoadDefaultDecorators' => true,
       ));

       $label = $this->getElement('submit');
       $label->addDecorators(array(
          array('ViewHelper'),
          array('HtmlTag')
       ));

    }


}

