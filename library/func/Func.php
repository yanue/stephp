<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * 共用函数 - Func.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/func
 * @time     2013-07-11
 */



class Func {

    public static function uniqueNum(){
        $t = explode(' ',microtime());
        $strtime = $t[1];
        # 时间戳后四位+micortime+时间戳前6位
        $o = substr($strtime,6).substr($t[0],2,6).substr($strtime,0,6);
        return $o;
    }

    public static function formatTime($time)
    {
        $rtime = date("Y-m-d H:i",$time);
        $htime = date("H:i",$time);
        $time = time() - $time;
        if ($time < 60)
        {
            $str = '刚刚';
        }elseif($time < 60 * 60)
        {
            $min = floor($time/60);
            $str = $min.'分钟前';
        }elseif($time < 60 * 60 * 24)
        {
            $h = floor($time/(60*60));
            $str = $h.'小时前 '.$htime;
        }elseif($time < 60 * 60 * 24 * 3)
        {
            $d = floor($time/(60*60*24));
            if($d==1)
                $str = '昨天 '.$htime;
            else
                $str = '前天 '.$htime;
        }else
        {
            $str = $rtime;
        }
        return $str;
    }

    /**
    重置图片高宽和位置，溢出隐藏
    保证在规定窗口满窗显示
    直接在图片style上使用输出
    参数：
    $ratio 	: 需要定义图片的宽高比
    $w		: 外框的宽度
    $h		: 外框的高度
    $type 	: 返回值类型，1.组合好的css字串，2.图片新的高宽值数组
    返回值：
    例：width:200px;height:521px;margin-top:-260px;
    */
    function resizeImgStyle($ratio,$w=200,$h=160,$type=1){
        $width = $w != 0 ? $w : 200;
        $height = $h != 0 ? $h : 200;
        $frameRatio= $w/$h;//外框大小比例
        $offestX = 0;
        $offestY = 0;
        $newImgWidth = 0;
        $newImgHeight = 0;
        if($ratio>=$frameRatio){
            //宽一点 最小边高 取高
            $newImgHeight = $height;
            $newImgWidth = $height*$ratio;
            $offestY = 0;
            $offestX = ($newImgWidth-$width)/2;
        }else{
            //窄一点 最小边宽 取宽
            $newImgWidth = $width;
            $newImgHeight = $width/$ratio;
            $offestX = 0;
            $offestY = ($newImgHeight-$height)/2;
        }

        if($type==2){
            // 返回数组
            $style = array('img_width'=>(int)$newImgWidth,'img_height'=>(int)$newImgHeight);
        }else{
            // 判断
            $offsetStyle = '';
            $offsetStyle .= $offestX>0 ? 'margin-left:-'.(int)$offestX .'px;' : '' ;
            $offsetStyle .= $offestY>0 ? 'margin-top:-'.(int)$offestY .'px;' : '' ;

            $style =  'width:'.(int)$newImgWidth.'px;'.'height:'.(int)$newImgHeight.'px;'.$offsetStyle;
        }

        return $style;
    }
}