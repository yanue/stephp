<?php
namespace Library\Util;

/**
 * ajax handle for web api
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Ajax
{
    const ERROR_USER_IS_NOT_EXISTS = 1001;
    const ERROR_USER_HAS_NOT_LOGIN = 1002;
    const ERROR_USER_HAS_NO_PERMISSION = 1003;
    const ERROR_USER_HAS_BEING_USED = 1004;
    const ERROR_USER_IS_INVALID = 1005;
    const ERROR_USER_BIND_FAILED = 1006;
    const ERROR_PASSWD_IS_INVALID = 1007;
    const ERROR_PASSWD_IS_NOT_CORRECT = 1008;
    const ERROR_EMAIL_IS_INVALID = 1009;
    const ERROR_EMAIL_HAS_BEING_USED = 1010;
    const ERROR_INVALID_REQUEST_PARAM = 1011;
    const ERROR_ILLEGAL_API_SIGNATURE = 1012;
    const ERROR_NOTHING_HAS_CHANGED = 1013;
    const ERROR_RUN_TIME_ERROR_OCCURRED = 1014;
    const ERROR_PHONE_IS_INVALID = 1015;
    const ERROR_PHONE_HAS_BEING_USED = 1016;
    const ERROR_USER_IS_NOT_ACTIVE = 1017;
    const ERROR_TOKEN_INVALID = 1018;
    const ERROR_TOKEN_EXPIRES = 1019;
    const ERROR_USER_NOT_BINDED = 1020;
    const ERROR_REFRESH_TOKEN_INVALID = 1021;
    const ERROR_SIGN_EXPIRES = 1022;
    const ERROR_DATA_NOT_EXISTS = 1023;
    const ERROR_DATA_HAS_EXISTS = 1024;
    const ERROR_POINTS_NOT_ENOUGH = 1025;
    const ERROR_EXP_NOT_ENOUGH = 1026;
    const ERROR_USER_PROFILE_IMCOMPLETE = 1027;
    const ERROR_TOKEN_HAS_REFRESHED = 1028;
    const ERROR_USER_HAS_BEING_LOCKED = 1029;
    const ERROR_TITLE_IS_NOT_EXISTS = 1030;
    const ERROR_TITLE_IS_EXISTS = 1031;

    #2 文件上传相关
    const UPLOAD_ERR_TMP_NAME_NOT_EXIST = 2011;
    const UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED = 2012;
    const UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED = 2013;
    const UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE = 2014;
    const UPLOAD_ERR_BATCH_IS_NOT_ALLOWED = 2015;
    const UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD = 2016;
    const UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED = 2017;
    const UPLOAD_ERR_MASTER_FILE_NOT_EXIST = 2018;

    # custom error msg
    const CUSTOM_ERROR_MSG = 3001;

    public static $errmsg = array(
        self::ERROR_USER_IS_NOT_EXISTS => '用户不存在',
        self::ERROR_USER_HAS_NOT_LOGIN => '用户尚未登陆',
        self::ERROR_USER_HAS_NO_PERMISSION => '用户没有权限',
        self::ERROR_USER_HAS_BEING_USED => '用户已被使用了',
        self::ERROR_USER_IS_INVALID => '用户名格式不正确',
        self::ERROR_USER_BIND_FAILED => '用户绑定失败',
        self::ERROR_PASSWD_IS_INVALID => '密码长度为6-16位字符',
        self::ERROR_PASSWD_IS_NOT_CORRECT => '密码不正确',
        self::ERROR_EMAIL_IS_INVALID => '邮箱格式不正确',
        self::ERROR_EMAIL_HAS_BEING_USED => '邮箱已被使用了',
        self::ERROR_INVALID_REQUEST_PARAM => '缺少请求参数',
        self::ERROR_ILLEGAL_API_SIGNATURE => '非法的API签名',
        self::ERROR_NOTHING_HAS_CHANGED => '修改或插入不成功',
        self::ERROR_RUN_TIME_ERROR_OCCURRED => '服务器错误',
        self::ERROR_PHONE_IS_INVALID => '非法的手机号',
        self::ERROR_PHONE_HAS_BEING_USED => '手机已经被使用',
        self::ERROR_USER_IS_NOT_ACTIVE => '用户未激活',
        self::ERROR_TOKEN_INVALID => '非法的token（令牌）',
        self::ERROR_TOKEN_EXPIRES => 'token（令牌）已经过期',
        self::ERROR_USER_NOT_BINDED => '用户未做本地化绑定',
        self::ERROR_REFRESH_TOKEN_INVALID => 'refresh_token非法',
        self::ERROR_SIGN_EXPIRES => '签名已经过期',
        self::ERROR_DATA_NOT_EXISTS => '数据不存在',
        self::ERROR_DATA_HAS_EXISTS => '数据已经存在',
        self::ERROR_POINTS_NOT_ENOUGH => '用户可用积分不够',
        self::ERROR_EXP_NOT_ENOUGH => '用户可用经验值不够',
        self::ERROR_USER_PROFILE_IMCOMPLETE => '用户基本资料不完整',
        self::ERROR_TOKEN_HAS_REFRESHED => 'token（令牌）已经被刷新过',
        self::ERROR_USER_HAS_BEING_LOCKED => '用户已被锁定',
        self::ERROR_TITLE_IS_NOT_EXISTS => '标题不存在',
        self::ERROR_TITLE_IS_EXISTS => '标题已经存在',

        UPLOAD_ERR_INI_SIZE => '文件大小超过了php.ini定义的upload_max_filesize值',
        UPLOAD_ERR_FORM_SIZE => '文件大小超过了HTML定义的MAX_FILE_SIZE值',
        UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
        UPLOAD_ERR_NO_FILE => '没有文件被上传',
        UPLOAD_ERR_NO_TMP_DIR => '缺少临时文件夹',
        UPLOAD_ERR_CANT_WRITE => '文件写入失败',

        self::UPLOAD_ERR_TMP_NAME_NOT_EXIST => '无文件上传',
        self::UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED => '未接收到数据',
        self::UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED => '文件类型不支持',
        self::UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE => '文件太大',
        self::UPLOAD_ERR_BATCH_IS_NOT_ALLOWED => '不允许批量上传',
        self::UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD => '仅支持批量上传',
        self::UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED => '文件保存失败',
    );

    /**
     * init session
     *
     */
    public function __construct()
    {
    }

    /**
     * echo right json data
     *
     * @param string $data
     * @param string $msg
     */
    public static function outRight($data = '', $msg = '')
    {
        self::setHead();
        $result = array(
            'error' => array('code' => 0, 'msg' => $msg, 'more' => $msg),
            'result' => 1,
            'data' => $data
        );

        if (isset($_REQUEST['callback']) && $_REQUEST['callback']) {
            echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        } else {
            echo json_encode($result, JSON_UNESCAPED_UNICODE); // php 5.4
        }
        exit;
    }


    /**
     * echo error json data
     *
     * @param $code
     * @param string $msg
     */
    public static function outError($code, $msg = '')
    {
        self::setHead();
        $result = array(
            'error' => array('code' => $code, 'msg' => self::getErrorMsg($code) ? self::getErrorMsg($code) : $msg, 'more' => $msg),
            'result' => 0,
        );
        if (isset($_REQUEST['callback']) && $_REQUEST['callback']) {
            echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        } else {
            echo json_encode($result, JSON_UNESCAPED_UNICODE); // php 5.4
        }
        exit;
    }

    /**
     * get error msg by defined code
     * @param $code
     * @return string
     */
    public static function getErrorMsg($code)
    {
        return isset($code) && isset(self::$errmsg[$code]) ? self::$errmsg[$code] : '';
    }

    /**
     * 设置ajax跨域head
     */
    public static function setHead()
    {
        header("content-type: text/javascript; charset=utf-8");
        header("Access-Control-Allow-Origin: *"); # 跨域处理
        header("Access-Control-Allow-Headers: content-disposition, origin, content-type, accept");
        header("Access-Control-Allow-Credentials: true");

        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}