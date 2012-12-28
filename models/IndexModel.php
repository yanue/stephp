<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:51
 */
class IndexModel extends Model
{
    public function __construct(){
        parent::init();
    }

    // test
    public function testList()
    {
        //print_r($this);
        return $this->db->select('SELECT * FROM text');
    }

}
