<?php

class Application_Form_Exerciseplan extends Zend_Form{
               function  __construct($options = null) {
                              parent::__construct($options);

                              $planid = new Zend_Form_Element_Hidden('planid');
                              $planid->setRequired(true);

                              $html = array('HtmlTag',array('tag'=>'br', 'openOnly'=>true));

                              $date = new Zend_Form_Element_Select('date');
                              $date->setLabel('Date: ')->setDecorators(array('ViewHelper',$html,'Label'))->setRequired(true)
                                      ->setRegisterInArrayValidator(false);

			      $am_or_pm = new Zend_Form_Element_Checkbox('am_or_pm');
                              $am_or_pm->setRequired(true)->setDecorators(array('ViewHelper',$html,'Label'))->setLabel('For second session?: ')
				      ->setCheckedValue("1");

                              $exerciseid = new Zend_Form_Element_Select('exerciseid');
                              $exerciseid->setRequired(true)->setDecorators(array('ViewHelper',$html,'Label'))->setLabel('Exercise type: ')
                                      ->setRegisterInArrayValidator(false);

                              $exerciseid_com = new Zend_Form_Element_Select('exerciseid_com');
                              $exerciseid_com->setRequired(false)->setDecorators(array('ViewHelper',$html,'Label'))->setLabel('Second exercise type: ')
                                      ->setRegisterInArrayValidator(false);

	          $exerciseid_com2 = new Zend_Form_Element_Select('exerciseid_com2');
                              $exerciseid_com2->setRequired(false)->setDecorators(array('ViewHelper',$html,'Label'))->setLabel('Third exercise type: ')
                                      ->setRegisterInArrayValidator(false);

                              $validator_int = new Zend_Validate_Int();
                              $validator_double = new Zend_Validate_Float();

                              $weight = new Zend_Form_Element_Text('weight');
                              $weight->setLabel('Weight:')->setDecorators(array('ViewHelper',$html,'Label'))->addValidator($validator_double)
                                      ->setRequired(true);

                              $reps = new Zend_Form_Element_Text('reps');
                              $reps->setLabel('Repititions: ')->setDecorators(array('ViewHelper',$html,'Label'))->addValidator($validator_int)
                                      ->setRequired(true);

                              $reps_com = new Zend_Form_Element_Text('reps_com');
                              $reps_com->setLabel('Repititions (second exercise): ')->setDecorators(array('ViewHelper',$html,'Label'))->addValidator($validator_int)
                                      ->setRequired(false);

	          $reps_com2 = new Zend_Form_Element_Text('reps_com2');
                              $reps_com2->setLabel('Repititions (third exercise): ')->setDecorators(array('ViewHelper',$html,'Label'))->addValidator($validator_int)
                                      ->setRequired(false);

                              $sets = new Zend_Form_Element_Text('sets');
                              $sets->setDecorators(array('ViewHelper',$html,'Label'))->addValidator($validator_int)->setRequired(true)
                                      ->setAttrib("disabled","disabled")->setValue(1)->setLabel("Sets: ");

                              $comment = new Zend_Form_Element_Text('comment');
                              $comment->setLabel("Comment: ")->setDecorators(array('ViewHelper',$html,'Label'))->setRequired(false);

                              $this->addElements(array($planid,$date,$exerciseid,$am_or_pm,$exerciseid_com,$exerciseid_com2,$weight,$reps,$reps_com,$reps_com2,$sets,$comment));
               }
}

?>
