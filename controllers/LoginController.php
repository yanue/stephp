<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:15
 */
class LoginController extends Controller
{
    public function __construct() {
        parent::__construct();
		$this->setNav(4,1,1);
    }

    public function index(){

        $this->view->setLayout('layout');

    }

    public function logindo(){
		$this->loadConfig('errcode');
		$user = $_REQUEST['user'];
		$passwd = $_REQUEST['passwd'];
		if($passwd && $user){
			$this->loadModel('AdminUser');
			$hasUser = $this->model->checkUser($user);
			if($hasUser==0){
				$this->outError(ERROR_USER_IS_NOT_EXISTS);
			}else{
				// passwd has used md5 and hash
				$this->loadLib('Hash');
				$password = Hash::create('sha1',md5($passwd),HASH_PASSWORD_KEY);
				$userInfo = $this->model->login($user,$password);
				if($userInfo){
					unset($userInfo['password']);
					$_SESSION['_CUSR']=$userInfo['user_name'];
					$_SESSION['_CUID']=$userInfo['uid'];

					// update login info
					$lastData = array('last_ip'=>$_SERVER['REMOTE_ADDR'],'last_login'=>time(),'login_count'=>$userInfo['login_count']+1);
					$this->model->updateUserInfo($lastData,'uid = '.$userInfo['uid']);

					// add login logs
					$this->loadConfig('logcode');
					$logContent = getLogConent(LOG_USER_LOGIN);

					// add log
					$logData = array('user_id'=>$userInfo['uid'],'content'=>$logContent,'time'=>time());
					$this->model->addLog($logData);

					// out right info
					$this->outRight('');
				}else{
					$this->outError(ERROR_PASSWD_IS_NOT_CORRECT);
				}
			}
		}else{
			$this->outError(ERROR_INVALID_REQUEST_PARAM);
			exit;
		}
    }

	public function loginOut(){
		//session_start();
		unset($_SESSION);
		session_destroy();

		$url = $this->view->baseUrl('login');
		header('Location:'.$url);
	}

    public function test(){
        $this->setLayout();
        //$this->view->setContent($this->controller,$this->action);
        $this->view->render('index/other');
    }
}
