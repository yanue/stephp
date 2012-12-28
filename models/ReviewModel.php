<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-17
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 */
class ReviewModel extends Model
{
	protected $_name = 'user_review';
	private $_user = 'user_profile';
	protected $_primary = 'id';

	public function __construct(){
		parent::init();
	}

	public function getReview($where='',$page=0,$limit=14)
	{
		$page = $page-1 <= 0 ? 0 : $page-1 ;
		$res = $this->db->select('select * from '.$this->_name .' limit '.$page*$limit .' ,'. $limit);
		return self::fun_fetch_user($res,$this->db);
	}

	public function getOne($id){
		$res = $this->db->select('select * from '.$this->_name .' where id = :id',array('id'=>$id));
		return $res[0];
	}

	public function getCount(){
		$count = $this->db->select('select count('. $this->_primary .') as count from '.$this->_name);
		return $count[0]['count'];
	}

	public static function fun_fetch_user(&$items,$db) {

		for ($i=0;$i<count($items);$i++) {

			$ret = $db->select('select user_name from user_profile where uid in ('.$items[$i]['from_uid'].','.$items[$i]['to_uid'].')');

			@$items[$i]['to_user'] = $ret[0]['user_name'];
			@$items[$i]['from_user'] = $ret[1]['user_name'];
		}

		return $items;
	}



}
