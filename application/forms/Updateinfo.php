<?php
class Application_Form_Updateinfo extends Zend_Form {
               function  __construct($options = null) {
                              parent::__construct($options);

                              $name = new Zend_Form_Element_Text('name');
                              $name->setLabel('Name: ')->setRequired(true)->addValidator(new Zend_Validate_Regex('/^[a-zA-Z\.\s]+$/'));

                              $surname = new Zend_Form_Element_Text('surname');
                              $surname->setLabel('Surname: ')->setRequired(true)->addValidator(new Zend_Validate_Regex('/^[a-zA-Z\.\s]+$/'));

                              $password = new Zend_Form_Element_Password('password');
                              $password->setLabel('Password: ')->setRequired(true);

                              $cpassword = new Zend_Form_Element_Password('cpassword');
                              $cpassword->setLabel('Confirm Password: ')->setRequired(true);

                              $contactno = new Zend_Form_Element_Text('contactno');
                              $contactno->setLabel('Contact Number: ')->setRequired(true)->addValidator(new Zend_Validate_Regex('/^[0-9()\s]+$/'));

                              $email = new Zend_Form_Element_Text('email');
                              $email->setLabel('Email: ')->setRequired(true)->addValidator(new Zend_Validate_EmailAddress());

                              $submit = new Zend_Form_Element_Submit('submit');
                              $submit->setLabel('Submit')->setIgnore(true);

                              $this->addElements(array($name,$surname,$contactno,$email,$password,$cpassword,$submit));
               }
}

?>
