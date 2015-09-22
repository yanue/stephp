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
    #1 无效信息
    const INVALID_DATA = 1001;
    const INVALID_USER = 1002;
    const INVALID_USERNAME = 1003;
    const INVALID_PHONE = 1004;
    const INVALID_EMAIL = 1005;
    const INVALID_IMG = 1006;
    const INVALID_PASSWORD = 1007;
    const INVALID_PARAM = 1007;
    const INVALID_SIGNATURE = 1008; // 无效签名
    const INVALID_ACCOUNT = 1009; // 无效签名

    #2 不存在
    const NOT_EXISTS_DATA = 2001;
    const NOT_EXISTS_USER = 2002;
    const NOT_EXISTS_PHONE = 2003;
    const NOT_EXISTS_EMAIL = 2004;
    const NOT_EXISTS_IMG = 2005;
    const NOT_EXISTS_PARAM = 2006;
    const NOT_EXISTS_ACCOUNT = 2007;

    #3 被使用过的
    const USED_PHONE = 3001;
    const USED_EMAIL = 3002;
    const USED_USER = 3003;
    const USED_USERNAME = 3004;
    const USED_ACCOUNT = 2005;

    #4 用户相关
    const USER_NOT_BIND = 4001;
    const USER_LOCKED = 4002;
    const USER_NO_PERMISSION = 4003;
    const USER_NOT_LOGIN = 4004;
    const USER_NOT_ACTIVE = 4005;
    const USER_BIND_FAILED = 4006;

    #5 文件上传相关
    const UPLOAD_ERR_TMP_NAME_NOT_EXIST = 5011;
    const UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED = 5012;
    const UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED = 5013;
    const UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE = 5014;
    const UPLOAD_ERR_BATCH_IS_NOT_ALLOWED = 5015;
    const UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD = 5016;
    const UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED = 5017;
    const UPLOAD_ERR_MASTER_FILE_NOT_EXIST = 5018;

    #6 其他
    const ERROR_SIGN_EXPIRES = 6001;
    const ERROR_REFRESH_TOKEN_INVALID = 6002;
    const ERROR_TOKEN_INVALID = 6003;
    const ERROR_TOKEN_EXPIRES = 6004;
    const ERROR_RUN_TIME_ERROR_OCCURRED = 6005;
    const ERROR_NOTHING_HAS_CHANGED = 6007;
    const ERROR_TOKEN_HAS_REFRESHED = 6008;
    # custom error msg
    const CUSTOM_ERROR_MSG = 7001;

    # 任务相关
    const TASK_CONTAINER_ORDER_NOT_EXIST = 8001; // 集装箱订单不存在
    const TASK_NOT_EXIST = 8002; // 任务不存在
    const TASK_HAS_FINISHED = 8003; // 任务已经完成了
    const TASK_FRONT_UNFINISHED = 8004; // 前置任务尚未完成
    const TASK_STATUS_CAN_NOT_FINISHED = 8005; // 状态不能为：[S02,SEA,SED,SER]
    const TASK_HAS_REPORTED_CHECK = 8006; // 已经汇报过了
    const TASK_NOT_NEED_CHECK = 8007; // 无需查验
    const TASK_APPLY_EXCHANGE_EXIST = 8008; // 已经申请过换人了
    const TASK_APPLY_EXCHANGE_NOT_EXIST = 8009; // 已经申请过换人了
    const TASK_APPLY_EXCHANGE_REJECT = 8010; //申请换人被拒绝
    const TASK_APPLY_EXCHANGE_WAIT = 8011; //申请换人被拒绝
    const TASK_APPLY_EXCHANGE_NOT_SUPPORT = 8012; //申请换人被拒绝
    const TASK_HAS_STARTED = 8013; // 任务已经开始过了

    public static $errmsg = array(
        #1 无效信息
        self::INVALID_DATA => "无效的数据",
        self::INVALID_USER => "无效的用户",
        self::INVALID_USERNAME => "无效的用户名",
        self::INVALID_PHONE => "无效的手机号",
        self::INVALID_EMAIL => "无效的邮箱",
        self::INVALID_IMG => "无效的图片",
        self::INVALID_PASSWORD => "无效的密码",
        self::INVALID_PARAM => "无效的参数",
        self::INVALID_SIGNATURE => "无效的签名",
        self::INVALID_ACCOUNT => "无效的账号",

        #2 不存在
        self::NOT_EXISTS_DATA => "数据不存在",
        self::NOT_EXISTS_USER => "用户不存在",
        self::NOT_EXISTS_PHONE => "手机号不存在",
        self::NOT_EXISTS_EMAIL => "邮箱不存在",
        self::NOT_EXISTS_IMG => "图片不存在",
        self::NOT_EXISTS_PARAM => "参数不存在",
        self::NOT_EXISTS_ACCOUNT => "账号不存在",

        #3 使用过的
        self::USED_PHONE => "手机已被使用了",
        self::USED_EMAIL => "邮箱已被使用了",
        self::USED_USER => "用户已被使用了",
        self::USED_USERNAME => "用户名已被使用了",
        self::USED_ACCOUNT => "账号已被使用了",
        self::USER_NOT_ACTIVE => "账号已被禁用了",
        self::USER_BIND_FAILED => "账号绑定失败了",

        #4 用户相关
        self:: USER_NOT_BIND => "用户尚未绑定",
        self:: USER_LOCKED => "用户已被锁定",
        self:: USER_NO_PERMISSION => '用户没有权限',
        self:: USER_NOT_LOGIN => '用户尚未登录',
        self:: USER_NOT_ACTIVE => '用户尚未激活',
        self:: USER_BIND_FAILED => '用户绑定失败',

        self::ERROR_NOTHING_HAS_CHANGED => '修改或插入不成功',
        self::ERROR_RUN_TIME_ERROR_OCCURRED => '服务器错误',
        self::ERROR_TOKEN_INVALID => '非法的token（令牌）',
        self::ERROR_TOKEN_EXPIRES => 'token（令牌）已经过期',
        self::ERROR_REFRESH_TOKEN_INVALID => 'refresh_token非法',
        self::ERROR_SIGN_EXPIRES => '签名已经过期',

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

        self::TASK_CONTAINER_ORDER_NOT_EXIST => '集装箱订单不存在',
        self::TASK_NOT_EXIST => '该任务不存在',
        self::TASK_STATUS_CAN_NOT_FINISHED => '该任务已经申请换人',
        self::TASK_HAS_FINISHED => '该任务已经做过了',
        self::TASK_FRONT_UNFINISHED => '前置任务尚未完成',
        self::TASK_HAS_REPORTED_CHECK => '已经汇报过查验情况了',
        self::TASK_NOT_NEED_CHECK => '该订单已无需查验',
        self::TASK_APPLY_EXCHANGE_EXIST => '已经申请过换人了',
        self::TASK_APPLY_EXCHANGE_NOT_EXIST => '换单记录未找到',
        self::TASK_APPLY_EXCHANGE_REJECT => '申请换人被拒绝',
        self::TASK_APPLY_EXCHANGE_WAIT => '申请换人等待审核',
        self::TASK_APPLY_EXCHANGE_NOT_SUPPORT => '换单任务不能执行',
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
            'result' => 1,
//            'error' => array('code' => 0, 'msg' => $msg, 'more' => $msg),
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
        $code_msg = self::getErrorMsg($code) ? self::getErrorMsg($code) : $msg;
        $result = array(
            'result' => 0,
            'error' => array('code' => $code, 'msg' => $code_msg, 'more' => ($code_msg == $msg ? '' : $msg)),
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