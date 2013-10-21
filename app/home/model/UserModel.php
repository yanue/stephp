<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yanue
 * Date: 13-6-30
 * Time: PM11:35
 * To change this template use File | Settings | File Templates.
 */

namespace App\Home\Model;

use Library\Core\Model;
use Library\Db\DB;
use Library\Util\Debug;

class UserModel extends Model{
    private $_user = 'user';
    function __construct(){
        parent::__construct();
    }

    public function getTest(){
        $res = $this->db->from('article')
            ->where('id > ?', 1)
            ->orderBy('id DESC')
            ->limit(5)->fetchAll();
        return $res;
    }

    public function insertTest(){
        $query = $this->db
            -> insertInto(
                'article',
                array(
                    'id'=>null,
                    'group_id'=>'2',
                    'time'=>date("y-m-d H:i:s"),
                    'title'=>'asdaasdadas啊阿斯顿啊阿斯顿 阿斯  ',
                    'content' => 'asdasdasd asa测 阿斯顿阿斯顿阿斯顿  阿斯顿啊速度 '
                ))->execute();
//        $query->execute();
    }

}