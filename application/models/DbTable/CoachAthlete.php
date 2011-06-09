<?php
class Application_Model_DbTable_CoachAthlete extends Zend_Db_Table_Abstract{
    protected $_name = "coachathlete";

    function detachfromcoach($aid){
        if($this->delete('athleteid = '.$aid)){
            return true;
        }
        else{
            return false;
        }
    }
}

?>
