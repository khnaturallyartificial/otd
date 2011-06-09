<?php
class KH_Form_Decorator_TblWrap extends Zend_Form_Decorator_Abstract{
    public function render($content){
	$placement = $this->getPlacement();
	$tag		=	$this->getOption('text');
	switch($placement){
	    case 'APPEND' :
		default:
		return '<'.$tag.'>'. $content . '</'. $tag .'>';
		break;
	    case 'PREPEND' :
		return $tag . $content;
		break;
	}
    }
}
?>