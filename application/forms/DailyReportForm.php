<?php


class Application_Form_DailyReportForm extends Zend_Form {
               function  __construct($options = null) {
                              parent::__construct($options);

                              $date = new Zend_Form_Element_Text('date');
                              $value = date('Y-m-d', time());
                              $date->setLabel('Date : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setAttrib('readonly', true)
	                  ->setAttrib('class','pickdate dailyreportdate')
	                  ->setAttrib('style','width:150px;height:18px;font-size:12px;padding:1px;background-color:white;')

                                      //->setValue($value)
                                      ->setRequired(true)
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'));

                              $bw_morning = new Zend_Form_Element_Text('bw_morning');
                              $bw_morning->setLabel("Morning  : ")->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1,2})?$/'));

                              $morning_heart_rate = new Zend_Form_Element_Text('mhr');
                              $morning_heart_rate->setLabel('Morning Heart Rate')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->addValidator(new Zend_Validate_Int());


                              $bw_evening = new Zend_Form_element_Text('bw_evening');
                              $bw_evening->setLabel("Evening :* ")->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1,2})?$/'));

                              $urine_morning = new Zend_Form_element_Text('urine_morning');
                              $urine_morning->setLabel('Morning : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Between(1,8))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1})?$/'));

                              $urine_evening = new Zend_Form_element_Text('urine_evening');
                              $urine_evening->setLabel('Evening : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Between(1,8))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1})?$/'));

                              $fluid = new Zend_Form_element_Text('fluid');
                              $fluid->setLabel("Estimated fluid consumption :* ")->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(0)
                                      ->addValidator(new Zend_Validate_Between(0,8))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1})?$/'));

                              $sleep_quality = new Zend_Form_element_Text('sleep_quality');
                              $sleep_quality->setLabel('Sleep quality :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $sleep_quantity = new Zend_Form_element_Text('sleep_quantity');
                              $sleep_quantity->setLabel('Sleep quantity :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,16))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9]+(.[0-9]{1})?$/'));

                              $mental_recovery = new Zend_Form_element_Text('mental_recovery');
                              $mental_recovery->setLabel('Mental Recovery :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $physical_recovery = new Zend_Form_element_Text('physical_recovery');
                              $physical_recovery->setLabel('Physical Recovery :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $pre_training_energy = new Zend_Form_element_Text('pre_training_energy');
                              $pre_training_energy->setLabel('Pre training energy levels :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $muscle_soreness = new Zend_Form_element_Text('muscle_soreness');
                              $muscle_soreness->setLabel('Muscle Soreness :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $general_fatigue = new Zend_Form_element_Text('general_fatigue');
                              $general_fatigue->setLabel('General Fatigue :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->setValue(1)
                                      ->addValidator(new Zend_Validate_Between(1,5))
                                      ->addValidator(new Zend_Validate_Regex('/^[0-9](.[0-9]{1})?$/'));

                              $session1_duration = new Zend_Form_Element_Text('session1_duration');
                              $session1_duration->setLabel('Session 1 Duration :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->addValidator(new Zend_Validate_Between(0,500))
                                      ->addValidator(new Zend_Validate_Int());

                              $session2_duration = new Zend_Form_Element_Text('session2_duration');
                              $session2_duration->setLabel('Session 2 Duration : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Between(0,500))
                                      ->addValidator(new Zend_Validate_Int());
                              
                              $session1_intensity = new Zend_Form_Element_Text('session1_intensity');
                              $session1_intensity->setLabel('Session 1 intensity :* ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(true)
                                      ->addValidator(new Zend_Validate_Between(0,10))
                                      ->addValidator(new Zend_Validate_Int());

                              $session2_intensity = new Zend_Form_Element_Text('session2_intensity');
                              $session2_intensity->setLabel('Session 2 intensity : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false)
                                      ->addValidator(new Zend_Validate_Between(0,10))
                                      ->addValidator(new Zend_Validate_Int());

                              $comment = new Zend_Form_Element_Textarea('comment');
                              $comment->setLabel('Additional Comment : ')->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setRequired(false);

                              $tom = new Zend_Form_Element_Radio('tom');
                              $tom->clearDecorators()->setDecorators(array('ViewHelper', 'Errors'))
                                      ->setMultiOptions(array('y'=>'Yes', "n"=>"No"))
                                      ->setValue('n')
                                      ->setSeparator("")
                                      ->setRequired(false);

                              $this->addElements(array($date,$bw_morning,$bw_evening,$urine_morning,
                                  $urine_evening,$fluid,$sleep_quality,$sleep_quantity,$mental_recovery,$physical_recovery,
                                  $morning_heart_rate
                                  ,$pre_training_energy,$muscle_soreness,$general_fatigue,$session1_duration,$session2_duration,
                                  $session1_intensity,$session2_intensity,$comment,$tom));



               }
}

?>
