<?php
class Application_Form_Register extends Zend_Form{
    function __construct($options = null){
	parent::__construct($options);
	$this->setName('registerform');
	$this->setAttrib('class', 'textcenter');
	
	$v_username = new Zend_Validate_Regex('/^[a-zA-Z0-9.]+$/');
	$v_username->setMessage('Invalid characters. Only alphabets, numbers and . (dot) are allowed');
	$v_name = new Zend_Validate_Regex('/^[a-zA-Z\']+$/');
	$v_name->setMessage('Invalid characters. Only alphabets and \' allowed');
	$v_length = new Zend_Validate_StringLength(4,20);
	$v_contactnolength = new Zend_Validate_StringLength(7,15);
	$v_length50 = new Zend_Validate_StringLength(2,50);
	$v_alpha = new Zend_Validate_Alpha();
	$v_contactno = new Zend_Validate_Regex('/^[0-9\s()]+$/');
	$v_email = new Zend_Validate_EmailAddress();
	
	$username = new Zend_Form_Element_Text('username');
	$username->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))				    
			    ->setLabel('Username: ')
			    ->addValidators(array($v_username,$v_length))
			    ->setAttrib('title','Between 4 and 20 characters. A - Z, 0 - 9, and dot(.) allowed')
			    ->setRequired(true);
	
	$password = new Zend_Form_Element_Password('password');
	$password->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			->setLabel('Password: ')
			->addValidator($v_length)
			->setAttrib('title','Between 4 and 20 characters. All characters allowed.')
			->setRequired(true);
	
	$cpassword = new Zend_Form_Element_Password('cpassword');
	$cpassword->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Description',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10 formbottomborder'))
			->setLabel('Confirm Password: ')
			->setDescription('Please retype the password in text box above to confirm')
			->addValidator($v_length)
			->setRequired(true);
			
			
	$name = new Zend_Form_Element_Text('name');
	$name->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setLabel('Name: ')
			    ->addValidators(array($v_name,$v_length50))
			    ->setAttrib('title','Between 2 and 50 characters. A - Z and apostrophe(\') allowed')
			    ->setRequired(true);
	
	$surname = new Zend_Form_Element_Text('surname');
	$surname->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setLabel('Surname: ')
			    ->setAttrib('title','Between 2 and 50 characters. A - Z, and apostrophe(\') allowed')
			    ->addValidators(array($v_name,$v_length50))
			    ->setRequired(true);
	
	$contactno = new Zend_Form_Element_Text('contactno');
	$contactno->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setLabel('Contact No: ')
			    ->addValidators(array($v_contactno,$v_contactnolength))
			    ->setErrorMessages(array('Invalid contact number.
						     Allowed:Numbers, space and brackets. Between 7 to 15 characters.'))
			    ->setAttrib('title','Between 7 and 15 characters. Numbers, spaces and brackets allowed')
			    ->setRequired(true);
			    
	$email = new Zend_Form_Element_Text('email');
	$email->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setLabel('Email Address: ')
			    ->setAttrib('title','Email should be in this format: local@domain.ext')
			    ->addValidators(array($v_email,$v_length50))
			    ->setRequired(true);

                    $dob = new Zend_Form_Element_Text('date_of_birth');
                    $dob->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
                                                                ->setLabel('Date Of Birth: ')
                                                                ->setAttrib('title','Please select the date from the drop down calendar')
                                                                ->setRequired(true);

                    $height = new Zend_Form_Element_Text('height');
                    $height->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
                                                                ->setLabel('Height: (m.) ')
                                                                ->setErrorMessages(array("Please only enter a number (2 decimal places max.)"))
                                                                ->addValidator(new Zend_Validate_Regex("/^[0-9]+(.[0-9]{1,2})?$/"))
                                                                ->setRequired(true);
			
	$submit = new Zend_Form_Element_Image('submit');
	$submit->setAttrib('src','/img/register.gif')->setIgnore(true)
			->removeDecorator('dt')
			->addDecorator('FormElements') //the input field itself
			->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'textcenter')); //wrap with html tag, where tag is <div>
			
	$coachcode = new Zend_Form_Element_Text('coachcode');
	$coachcode->setLabel('Coach Code: ')->setDescription('If you are a coach, please enter your coach code here.')
	->removeDecorator('dt')->removeDecorator('dd');

                    $sex = new Zend_Form_Element_Radio('sex');
                    $sex->setLabel('Sex: ')
                            ->addMultiOption("M","Male")
                            ->setSeparator("")
                    ->addMultiOption("F", "Female")->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setRequired(true);

                    $o_or_po = new Zend_Form_Element_Radio('olym_or_power');
                    $o_or_po->setLabel('I am a: ')
                            ->addMultiOption("o","Weightlifter")
                            ->setSeparator("<br/>")
                    ->addMultiOption("p", "Powerlifter")->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel'))
							  ))
			    ->addDecorator('HtmlTag',array('tag' => 'div', 'class'=>'pad10'))
			    ->setRequired(true);
                    
			
	$this->addElements(array($username,$password,$cpassword,$coachcode,$name,$surname,$contactno,$email,$dob,$height,$o_or_po,$sex,$submit));

	
    }
}
?>