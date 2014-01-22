<?php
/**
 * api错误码
 * errorCode->errorMsg 用于ajax返回错误信息
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		2013-07-19
 */

/**
 * define error code
 */

#1 用户相关
!defined('ERROR_USER_IS_NOT_EXISTS')     AND define('ERROR_USER_IS_NOT_EXISTS',          1001);
!defined('ERROR_USER_HAS_NOT_LOGIN')     AND define('ERROR_USER_HAS_NOT_LOGIN',          1002);
!defined('ERROR_USER_HAS_NO_PERMISSION') AND define('ERROR_USER_HAS_NO_PERMISSION',      1003);
!defined('ERROR_USER_HAS_BEING_USED')    AND define('ERROR_USER_HAS_BEING_USED',         1004);
!defined('ERROR_USER_IS_INVALID')        AND define('ERROR_USER_IS_INVALID',             1005);
!defined('ERROR_USER_BIND_FAILED')       AND define('ERROR_USER_BIND_FAILED',            1006);
!defined('ERROR_PASSWD_IS_INVALID')      AND define('ERROR_PASSWD_IS_INVALID',           1007);
!defined('ERROR_PASSWD_IS_NOT_CORRECT')  AND define('ERROR_PASSWD_IS_NOT_CORRECT',       1008);
!defined('ERROR_EMAIL_IS_INVALID')       AND define('ERROR_EMAIL_IS_INVALID',            1009);
!defined('ERROR_EMAIL_HAS_BEING_USED')   AND define('ERROR_EMAIL_HAS_BEING_USED',        1010);
!defined('ERROR_INVALID_REQUEST_PARAM')  AND define('ERROR_INVALID_REQUEST_PARAM',       1011);
!defined('ERROR_ILLEGAL_API_SIGNATURE')  AND define('ERROR_ILLEGAL_API_SIGNATURE',       1012);
!defined('ERROR_NOTHING_HAS_CHANGED')    AND define('ERROR_NOTHING_HAS_CHANGED',         1013);
!defined('ERROR_RUN_TIME_ERROR_OCCURRED') AND define('ERROR_RUN_TIME_ERROR_OCCURRED',    1014);
!defined('ERROR_PHONE_IS_INVALID')       AND define('ERROR_PHONE_IS_INVALID',            1015);
!defined('ERROR_PHONE_HAS_BEING_USED')   AND define('ERROR_PHONE_HAS_BEING_USED',        1016);
!defined('ERROR_USER_IS_NOT_ACTIVE')     AND define('ERROR_USER_IS_NOT_ACTIVE',          1017);
!defined('ERROR_TOKEN_INVALID')          AND define('ERROR_TOKEN_INVALID',               1018);
!defined('ERROR_TOKEN_EXPIRES')          AND define('ERROR_TOKEN_EXPIRES',               1019);
!defined('ERROR_USER_NOT_BINDED')        AND define('ERROR_USER_NOT_BINDED',             1020);
!defined('ERROR_REFRESH_TOKEN_INVALID')  AND define('ERROR_REFRESH_TOKEN_INVALID',       1021);
!defined('ERROR_SIGN_EXPIRES')           AND define('ERROR_SIGN_EXPIRES',                1022);
!defined('ERROR_DATA_NOT_EXISTS')        AND define('ERROR_DATA_NOT_EXISTS',             1023);
!defined('ERROR_DATA_HAS_EXISTS')        AND define('ERROR_DATA_HAS_EXISTS',             1024);
!defined('ERROR_POINTS_NOT_ENOUGH')      AND define('ERROR_POINTS_NOT_ENOUGH',           1025);
!defined('ERROR_EXP_NOT_ENOUGH')         AND define('ERROR_EXP_NOT_ENOUGH',              1026);
!defined('ERROR_USER_PROFILE_IMCOMPLETE') AND define('ERROR_USER_PROFILE_IMCOMPLETE',    1027);
!defined('ERROR_TOKEN_HAS_REFRESHED')    AND define('ERROR_TOKEN_HAS_REFRESHED',         1028);
!defined('ERROR_USER_HAS_BEING_LOCKED')  AND define('ERROR_USER_HAS_BEING_LOCKED',       1029);


#2 文件上传相关
!defined('UPLOAD_ERR_TMP_NAME_NOT_EXIST')            AND define('UPLOAD_ERR_TMP_NAME_NOT_EXIST',         2011);
!defined('UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED')       AND define('UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED',    2012);
!defined('UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED')         AND define('UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED',      2013);
!defined('UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE')      AND define('UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE',   2014);
!defined('UPLOAD_ERR_BATCH_IS_NOT_ALLOWED')          AND define('UPLOAD_ERR_BATCH_IS_NOT_ALLOWED',       2015);
!defined('UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD')     AND define('UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD',       2016);
!defined('UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED')   AND define('UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED',       2017);
!defined('UPLOAD_ERR_MASTER_FILE_NOT_EXIST')         AND define('UPLOAD_ERR_MASTER_FILE_NOT_EXIST',      2018);

# custom error msg
!defined('CUSTOM_ERROR_MSG')        AND define('CUSTOM_ERROR_MSG',             3001);



/**
 * define error msg
 */
$msg = array(
    // 用户相关
    ERROR_USER_IS_NOT_EXISTS        => '用户不存在',
    ERROR_USER_HAS_NOT_LOGIN        => '用户尚未登陆',
    ERROR_USER_HAS_NO_PERMISSION    => '用户没有权限',
    ERROR_USER_HAS_BEING_USED       => '用户已被使用了',
    ERROR_USER_IS_INVALID           => '用户名格式不正确',
    ERROR_USER_BIND_FAILED          => '用户绑定失败',
    ERROR_PASSWD_IS_INVALID         => '密码长度为6-16位字符',
    ERROR_PASSWD_IS_NOT_CORRECT     => '密码不正确',
    ERROR_EMAIL_IS_INVALID          => '邮箱格式不正确',
    ERROR_EMAIL_HAS_BEING_USED      => '邮箱已被使用了',
    ERROR_INVALID_REQUEST_PARAM     => '缺少请求参数',
    ERROR_ILLEGAL_API_SIGNATURE     => '非法的API签名',
    ERROR_NOTHING_HAS_CHANGED       => '尚未修改任何内容',
    ERROR_RUN_TIME_ERROR_OCCURRED   => '服务器错误',
    ERROR_PHONE_IS_INVALID          => '非法的手机号',
    ERROR_PHONE_HAS_BEING_USED      => '手机已经被使用',
    ERROR_USER_IS_NOT_ACTIVE        => '用户未激活',
    ERROR_TOKEN_INVALID             => '非法的token（令牌）',
    ERROR_TOKEN_EXPIRES             => 'token（令牌）已经过期',
    ERROR_USER_NOT_BINDED           => '用户未做本地化绑定',
    ERROR_REFRESH_TOKEN_INVALID     => 'refresh_token非法',
    ERROR_SIGN_EXPIRES              => '签名已经过期',
    ERROR_DATA_NOT_EXISTS           => '数据不存在',
    ERROR_DATA_HAS_EXISTS           => '数据已经存在',
    ERROR_POINTS_NOT_ENOUGH         => '用户可用积分不够',
    ERROR_EXP_NOT_ENOUGH            => '用户可用经验值不够',
    ERROR_USER_PROFILE_IMCOMPLETE   => '用户基本资料不完整',
    ERROR_TOKEN_HAS_REFRESHED       => 'token（令牌）已经被刷新过',
    ERROR_USER_HAS_BEING_LOCKED     => '用户已被锁定',

    // 上传相关
    2000+UPLOAD_ERR_INI_SIZE                =>'文件大小超过了php.ini定义的upload_max_filesize值',
    2000+UPLOAD_ERR_FORM_SIZE               =>'文件大小超过了HTML定义的MAX_FILE_SIZE值',
    2000+UPLOAD_ERR_PARTIAL                 =>'文件只有部分被上传',
    2000+UPLOAD_ERR_NO_FILE                 =>'没有文件被上传',
    2000+UPLOAD_ERR_NO_TMP_DIR              =>'缺少临时文件夹',
    2000+UPLOAD_ERR_CANT_WRITE              =>'文件写入失败',
    UPLOAD_ERR_TMP_NAME_NOT_EXIST           =>'无文件上传',
    UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED      =>'未接收到数据',
    UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED        =>'文件类型不支持',
    UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE     =>'文件太大',
    UPLOAD_ERR_BATCH_IS_NOT_ALLOWED         =>'不允许批量上传',
    UPLOAD_ERR_MASTER_FILE_NOT_EXIST        =>'主图不存在',
    UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD    =>'仅支持批量上传',
    UPLOAD_ERR_FASTDFS_SAVE_ERROR_OCCURRED  =>'文件保存失败',

);

/**
 * return ajax error msg
 */
return $msg;