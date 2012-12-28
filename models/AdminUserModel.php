<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-17
 * Time: 上午9:51
 * To change this template use File | Settings | File Templates.
 */
class AdminUserModel extends Model
{
	protected $_name = 'admin_users';
	protected $_logs_table = 'admin_logs';
	protected $_primary = 'uid';

	public function __construct(){
		parent::init();
	}

	public function checkUser($user){
		$count =$this->db->select('SELECT count(*) as count FROM '.$this->_name .' WHERE user_name = :user',
			array('user'=>$user)
		);
		return $count[0]['count'];
	}

	public function login($user,$passwd)
	{
		$user = $this->db->select('SELECT * FROM '.$this->_name .' WHERE user_name = :user and password = :passwd',
			array('user'=>$user,'passwd'=>$passwd)
		);

		return @$user[0];
	}

	public function updateUserInfo($data,$where){
		$res = $this->db->update($this->_name,$data,$where);
	}

	public function addLog($data){
		$res = $this->db->insert($this->_logs_table,$data);
	}

	public function getCount(){
		$count = $this->db->select('select count(uid) as count from '.$this->_name);
		return $count[0]['count'];
	}
}
