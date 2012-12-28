<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:33
 */
require_once 'library/Database.php';
require_once 'config.php';
class Model extends Database
{
	public $db = null;
    public function __construct(){
        
    }
	
	public function init(){
		$this->db = new Database();
		$this->db->connent(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	}
}
