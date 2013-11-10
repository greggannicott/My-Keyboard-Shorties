<?php

class Form_Register extends Zend_Form
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
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,30)))
       ));
       My_Plugin_Form::setDefaultLayout($this->getElement('username'));

       // Add the Password element
       $this->addElement('password','password', array(
          'label' => 'Password'
          , 'required' => true
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,15)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('password'));

       // Add the First Name element
       $this->addElement('text','first_name', array(
          'label' => 'First Name'
          , 'required' => true
          , 'size' => 20
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,20)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('first_name'));

       // Add the Surname element
       $this->addElement('text','surname', array(
          'label' => 'Surname'
          , 'required' => true
          , 'size' => 40
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,40)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('surname'));

       // Add a hidden field to set the user's role id
       $this->addElement('hidden','roleid', array (
          'value' => 'member',
          'disableLoadDefaultDecorators' => true
       ));

       // Add a hidden field to set the user's os
       $this->addElement('hidden','os', array (
          'value' => 'windows',
          'disableLoadDefaultDecorators' => true
       ));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Register',
          'disableLoadDefaultDecorators' => true
       ));

       $label = $this->getElement('submit');
       $label->addDecorators(array(
          array('ViewHelper'),
          array('HtmlTag')
       ));

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => 'true',
          'disableLoadDefaultDecorators' => true
       ));

    }


}

