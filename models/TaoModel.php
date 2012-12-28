<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-17
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 */
class TaoModel extends Model
{
	protected $_name = 'kjs_items';
	protected $_primary = 'uid';

	public function __construct(){
		parent::init();
	}

	public function getProduct($where='',$page=0,$limit=14)
	{
		$page = $page-1 <= 0 ? 0 : $page-1 ;
		return $this->db->select('select * from '.$this->_name .' limit '.$page*$limit .' ,'. $limit);
	}

	public function getCount(){
		$count = $this->db->select('select count(uid) as count from '.$this->_name);
		return $count[0]['count'];
	}
}
