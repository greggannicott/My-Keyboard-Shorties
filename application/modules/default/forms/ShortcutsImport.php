<?php

class Form_ShortcutsImport extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

       $this->setMethod('post');

       // This is required as it's an upload form
       $this->setAttrib('enctype', 'multipart/form-data');

       // Create the element to handle the upload
       $upload_element = new Zend_Form_Element_File('import_file');
       $upload_element->setLabel('Upload a CSV file:');
       // Ensure only 1 file
       $upload_element->addValidator('Count',false,1);
       // Limit to 100k
       $upload_element->addValidator('Size', false, 102400);
       // Limit the type to csv file
       $upload_element->addValidator('Extension', false, 'csv');
       // Add the element to the form
       $this->addElement($upload_element);

       // Add the submit button
       $this->addElement('submit', 'submit', array(
          'ignore' => true,
          'label' => 'Import Shortcuts',
       ));

       // Add a hidden field to handle the application id
       $this->addElement('hidden','id');

       // And finally, add some CSRF protection
       $this->addElement('hash','csrf', array(
          'ignore' => true,
       ));

    }


}

