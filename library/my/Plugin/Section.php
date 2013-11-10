<?php

class My_Plugin_Section extends Zend_Controller_Plugin_Abstract {
   
   /**
    * Returns the name of a section based on an ID
    * @param int $id Section ID
    * @return string Section Name
    */
   public static function ReturnName($id) {
       $sections_mapper = new Model_SubSectionsMapper();
       $section = new Model_SubSections();
       $section = $sections_mapper->find($id, $section);
       return $section->getName();
   }
}

?>
