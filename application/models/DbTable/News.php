<?php
class Application_Model_DbTable_News extends Zend_Db_Table_Abstract{
    protected $_name = 'news';
    
    function getNews($entries){
	$select = $this->_db->select()->from($this->_name)
			->limit($entries)->order('date DESC');
	$result = $this->_db->fetchAll($select);
                    $this->_db->closeConnection();
                    return $result;
    }
    
    function readyNewsForFooter($entries=3){
	$news = $this->getNews($entries);
	$content = '<table style="width:100% - 150px; margin:0px auto 0px auto; padding:5px;"><tr>';
	foreach($news as $n) :
	    $content .= '<td>
                <span class="newshead">'.$n["heading"].'</span><br/>
                <span class="newsdate">'.$n["date"].'</span><br/>
                '.$n["content"].'<br/>
                <a class="border" href="">Read More &raquo;</a>
            </td>';
	endforeach;
	$content .= '</tr></table>';
	return $content;
    }
}
?>