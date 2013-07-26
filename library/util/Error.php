<?php
/**
 * Exception.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @date        2013-07-25
 */

namespace Library\Util;
use Library\Core\Loader;

class Exception {

    public function __controller()
    {

    }

    public function errorHandle(){
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
        if(Loader::getConfig('phpSettings.debug')){
            register_shutdown_function(array('Library\Util\Debug','shutdown_handle'));
        }else{
            if($isAjax){
                echo json_encode(null);
            }else{
                echo json_encode(null);
                register_shutdown_function($this,'_errtpl');
            }
        }
    }

    /**
     *
     */
    private function _errtpl(){
        header('HTTP/1.1 404 page not found');
        echo '<title>404 page not found</title>';
        echo 'page not found!';
    }

}