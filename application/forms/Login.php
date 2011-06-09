<?php
class Application_Form_Login extends Zend_Form{
    function __construct($options = null){
	parent::__construct($options);
	$this->setName('loginform');
	$this->setAttrib('class', 'textcenter');
	
	$v_username = new Zend_Validate_Regex('/^[a-zA-Z0-9.]+$/');
	$v_username->setMessage('Invalid characters. Only alphabets, numbers and . (dot) are allowed');
	$v_length = new Zend_Validate_StringLength(4,20);
	
	$username = new Zend_Form_Element_Text('username');
	$username->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel')),
							  array('HtmlTag',array('tag' => 'br', 'openOnly'=>true, 'placement'=>'APPEND'))
							  ))
			    ->setLabel('Username:')
			    ->addValidators(array($v_username,$v_length))
                                                                ->setAttrib('title', "Your username (containing only letters and numbers)")
			    ->setRequired(true);
	
	$password = new Zend_Form_Element_Password('password');
	$password->clearDecorators()->setDecorators(array(
							  'ViewHelper',
							  'Errors',
							  array('label',array('placement'=>'PREPEND', 'class' => 'loginlabel')),
							  array('HtmlTag',array('tag' => 'br', 'openOnly'=>true, 'placement'=>'APPEND')),
							  array('HtmlTag',array('tag' => 'br', 'openOnly'=>true, 'placement'=>'PREPEND'))
							  ))
			->setLabel('Password:')
			->addValidator($v_length)
			->setRequired(true);
			
	$submit = new Zend_Form_Element_Image('submit');
	$submit->setAttrib('src','/img/logmein.gif')->setIgnore(true)
			->removeDecorator('dt')
			->addDecorator('FormElements') //the input field itself
			->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'textcenter')); //wrap with html tag, where tag is <div>
			
	$this->addElements(array($username,$password,$submit));
    
    }
}
?>