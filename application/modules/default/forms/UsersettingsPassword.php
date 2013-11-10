<?php

class Form_UsersettingsPassword extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // Create a validator that can be used to check that the two passwords match
       // identical field validator with custom messages
       $identValidator = new Zend_Validate_Identical($_POST['new_password']);
       $identValidator->setMessages(array('notSame' => 'Your password confirmation doesn\'t match your new password.','missingToken' => 'Your password confirmation doesn\'t match your new password.'));

       // Create a validator that can be used to check that the 'existing password'
       // provided by the user matches our records.
       $existingPasswordValidator = new My_Validators_CheckExistingPassword($_POST['existing_password']);

       // Add the 'old password' element
       $this->addElement('password','existing_password', array(
          'label' => 'Existing Password'
          , 'required' => true
          , 'filters' => array('StringTrim')
          , 'validators' => array(
                                    array('validator' => 'StringLength', 'options' => array(0,30)),
                                    $existingPasswordValidator
                                 )
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('existing_password'));

       // Add the 'new password' element
       $this->addElement('password','new_password', array(
          'label' => 'New Password'
          , 'required' => true
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,30)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('new_password'));

       // Add the 'confirm new password' element
       $this->addElement('password','new_password_confirmation', array(
          'label' => 'Confirm your new password:'
          , 'required' => true
          , 'filters' => array('StringTrim')
          , 'validators' => array(
                                    array('validator' => 'StringLength', 'options' => array(0,30)),
                                    $identValidator
                                 )
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('new_password_confirmation'));

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

