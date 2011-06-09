<?php

class Application_Form_Uploadfile extends Zend_Form {
               public function  __construct($options = null) {
                              parent::__construct($options);

                              $this->setName("upload");
                              $this->setAttrib('enctype', 'multipart/form-data');

                              $userid = new Zend_Form_Element_Hidden('useridh');
                              $userid->setRequired(true);

                              $upload = new Zend_Form_Element_File('file_path');
                              $upload->setLabel('Upload file')->setRequired(true)
                                      ->setDescription("JPG files only. Recomended dimensions: 120px by 140px")
                                      ->addValidator('Count', false, array('min'=>1, 'max'=>1))
                                      ->addValidator('Extension', false, 'jpg')
                                      ->addValidator('Size',false,array('max' => '512kB'));

                              $submit = new Zend_Form_Element_Submit('submit');
                              $submit->setLabel("Upload")->setIgnore(true)->setAttrib('class', 'rounded3');

                              $this->addElements(array($userid,$upload,$submit));

               }
}

?>
