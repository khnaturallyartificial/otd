<?php

class Application_Form_Newplan extends Zend_Form{
               function  __construct($options = null) {
                              parent::__construct($options);
                              $this->setName('newplan');

                              $planname = new Zend_Form_Element_Text('planname');
                              $planname->setLabel("Plan Name: ")->setAttrib('title', 'Give your plan a name. 200 characters max.')
                                      ->addValidator(new Zend_Validate_StringLength(1,200))
                                      ->setRequired(true);

                              $dateval = new Zend_validate_Regex('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/');
                              $dateval->setMessage("Invalid date format. Must be in YYYY-MM-DD format.");

                              $startdate = new Zend_Form_Element_Text('startdate');
                              $startdate->setLabel("Start date: ")->setRequired(true)
                                      ->setAttrib("title", "This is the date this plan will start from.")
                                      ->addValidator($dateval)
                                      ->setRequired(true);
                              $duration = new Zend_Form_Element_Text('enddate');
                              $duration->setLabel("Duration:")->setAttrib("title","This is the number of days the plan will last. <br/><br/><strong>Please enter a number between 1 and 14.</strong>")
                                      ->addValidator(new Zend_Validate_Int())
                                      ->setValue(7)
                                      ->setRequired(true);

                              $auth = Zend_Auth::getInstance();
                              $user = $auth->getIdentity();

                              $creator = new Zend_Form_Element_Hidden("creator");
                              $creator->setValue($user->userid)
                                      ->setDecorators(array('ViewHelper'));

                              $planid = new Zend_Form_Element_Hidden('planid');

	          $powerlift = new Zend_Form_Element_Radio("powerlift");
	          $powerlift->addMultiOption(0,"Weight lifting")->addMultiOption(1,"Power lifting")
	                  ->setValue(0)->setRequired(true)->setLabel("Plan type: ");
                              

                              $submit = new Zend_Form_Element_Submit('submit');
                              $submit->setLabel("Add plan")->setIgnore(true);

                              $this->addElements(array($planid,$planname,$startdate,$duration,$creator,$powerlift,$submit));
               }
}

?>
