<?php

class Application_Form_CustomExercise extends Zend_Form {
               function  __construct($options = null) {
                              parent::__construct($options);

                              $date = new Zend_Form_Element_Text('date');
                              $value = date('Y-m-d', time());
                              $date->setLabel('Date : ')
                                      ->setAttrib('readonly', true)
                                      ->setValue($value)
                                      ->setRequired(true)
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'));

                              $validator_int = new Zend_Validate_Int();
                              $validator_double = new Zend_Validate_Float();

                              $exerciseid = new Zend_Form_Element_Select('exerciseid');
                              $exerciseid->setRequired(true)
                                      ->setLabel("Exercise: ")                                     
                                      ->setRegisterInArrayValidator(false);

	          $exerciseid2 = new Zend_Form_Element_Select('exerciseid_com');
                              $exerciseid2->setRequired(false)
                                      ->setLabel("2nd Exercise (if any): ")
                                      ->setRegisterInArrayValidator(false);
	          $exerciseid3 = new Zend_Form_Element_Select('exerciseid_com2');
                              $exerciseid3->setRequired(false)
                                      ->setLabel("3rd Exercise (if any): ")
                                      ->setRegisterInArrayValidator(false);

                              $weight = new Zend_Form_Element_Text('weight');
                              $weight->setLabel('Weight: (in kg.)')->addValidator($validator_double)
                                      ->setRequired(true);

                              $reps = new Zend_Form_Element_Text('reps');
                              $reps->setLabel('Repititions: ')->addValidator($validator_int)
                                      ->setRequired(true);
	          $reps2 = new Zend_Form_Element_Text('reps_com');
                              $reps2->setLabel('2nd Repititions: ')->addValidator($validator_int)
                                      ->setRequired(false);
	          $reps3 = new Zend_Form_Element_Text('reps_com2');
                              $reps3->setLabel('3rd Repititions: ')->addValidator($validator_int)
                                      ->setRequired(false);

                              $sets = new Zend_Form_Element_Text('sets');
                              $sets->setLabel('Sets: ')->addValidator($validator_int)
                                      ->setRequired(true);

	          $comment = new Zend_Form_Element_Text('additionalcomment');
	          $comment->setLabel('Comment: (optional) ')->addFilter(new Zend_Filter_PregReplace('/([\'])*/i'))
	                  ->setRequired(false);

	          $abf = new Zend_Form_Element_Checkbox('abf');
	          $abf->setLabel('Attempted but failed? :')->setRequired(false);

                              //$submit = new Zend_Form_Element_Submit('submit');
                              //$submit->setLabel('Add this exercise')->setIgnore(true);

                              $this->addElements(array($date, $exerciseid,$exerciseid2,$exerciseid3,$weight,$reps,$reps2,$reps3,$sets,$abf,$comment));

               }
}

?>
