<?php

class Form_ShortcutsAdd extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */



       $this->setMethod('post');

       // Add the Keyboard Shortcut element
       $this->addElement('text','shortcut', array(
          'label' => 'Keyboard Shortcut'
          , 'required' => true
          , 'size' => 10
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('shortcut'));

       // Add the Windows Keyboard Shortcut element
       $this->addElement('text','shortcut_windows_override', array(
          'label' => 'Keyboard Shortcut (Windows override)'
          , 'required' => false
          , 'size' => 10
          , 'filters' => array('StringTrim')
       ));

        My_Plugin_Form::setDefaultLayout($this->getElement('shortcut_windows_override'));

       // Add the Mac Keyboard Shortcut element
       $this->addElement('text','shortcut_mac_override', array(
          'label' => 'Keyboard Shortcut (Mac override)'
          , 'required' => false
          , 'size' => 10
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('shortcut_mac_override'));

       // Add the Linux Keyboard Shortcut element
       $this->addElement('text','shortcut_linux_override', array(
          'label' => 'Keyboard Shortcut (Linux override)'
          , 'required' => false
          , 'size' => 10
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('shortcut_linux_override'));

       // Add the Action element
       $this->addElement('text','action', array(
          'label' => 'What action does this shortcut perform? (60 chars max)'
          , 'required' => true
          , 'size' => 60
          , 'filters' => array('StringTrim')
          , 'validators' => array(array('validator' => 'StringLength', 'options' => array(0,60)))
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('action'));

       // Add the subsection element
       $this->addElement('select','subsection_id', array(
          'label' => 'Sub-Section:',
          'registerInArrayValidator' => false
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('action'));

       // Add the Description element
       $this->addElement('text','description', array(
          'label' => 'Additional Notes (optional)'
          , 'filters' => array('StringTrim')
       ));

       My_Plugin_Form::setDefaultLayout($this->getElement('description'));

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Add New Shortcut',
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

