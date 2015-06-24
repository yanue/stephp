<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 2014/10/30
 * Time: 14:56
 */

namespace Library\Util;

// 动态获取图片尺寸
// http://gi2.md.alicdn.com/bao/uploaded/i2/TB1RwPtGpXXXXbuXVXXXXXXXXXX_!!0-item_pic.jpg_430x430q90.jpg
class ImgSize
{
    // 获取图片原始大小
    public static function getOriginal($img_url)
    {
        $img_ext = pathinfo($img_url, PATHINFO_EXTENSION);
        $img_path = self::getOriginalWithoutExt($img_url);
        if ($img_url) {
            return $img_path . '.' . $img_ext;
        }
        return $img_url;
    }

    // 替换图片
    public static function getWapDetailPicReplace($detail)
    {
        preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:))[\'|\"].*?[\/]?>/', $detail, $matches);
        if (isset($matches[1])) {
            $matches[1] = array_unique($matches[1]);
            foreach ($matches[1] as $item) {
                $detail = str_replace($item, self::getAutoHeight($item), $detail);
            }
        }
        return $detail;

    }

    public static function getAutoHeight($img_url, $width = 800)
    {
        if ($img_url) {
            // 允许的域名
            $img_host = parse_url($img_url, PHP_URL_HOST);
            if (!in_array($img_host, array(STATIC_DOMAIN, MAIN_DOMAIN, 'www.zksofa.com', 'static.zksofa.com', 'static.saleasy.net', 'gi2.md.alicdn.com'))) {
                return $img_url;
            }

            // 获取原始图片未带后缀
            $originalImgPath = self::getOriginalWithoutExt($img_url);
            if (!$originalImgPath) {
                return $img_url;
            }
            $img_ext = pathinfo($img_url, PATHINFO_EXTENSION);

            if ($width) {
                return $originalImgPath . '.' . $img_ext . '_' . $width . '-.' . $img_ext;
            }
        }

        return $img_url;
    }

    public static function getAutoWidth($img_url, $height = 200)
    {
        if ($img_url) {

            // 允许的域名
            $img_host = parse_url($img_url, PHP_URL_HOST);
            if (!in_array($img_host, array(STATIC_DOMAIN, MAIN_DOMAIN, 'static.zksofa.com', 'static.saleasy.net', 'gi2.md.alicdn.com'))) {
                return $img_url;
            }

            // 获取原始图片未带后缀
            $originalImgPath = self::getOriginalWithoutExt($img_url);
            if (!$originalImgPath) {
                return $img_url;
            }
            $img_ext = pathinfo($img_url, PATHINFO_EXTENSION);

            if ($height) {
                return $originalImgPath . '.' . $img_ext . '_' . '-' . $height . '.' . $img_ext;
            }
        }

        return $img_url;
    }

    // 按尺寸获取图片
    public static function getSize($img_url, $w = 200, $h = null)
    {
        if ($img_url) {
            // 允许的域名
            $img_host = parse_url($img_url, PHP_URL_HOST);
            if (!in_array($img_host, array(STATIC_DOMAIN, MAIN_DOMAIN, 'static.xiangyouji.com.cn', 'www.xiangyouji.com.cn', 'xiangyouji.com.cn', 'gi2.md.alicdn.com'))) {
                return $img_url;
            }

            // 获取原始图片未带后缀
            $originalImgPath = self::getOriginalWithoutExt($img_url);
            if (!$originalImgPath) {
                return $img_url;
            }
            $img_ext = pathinfo($img_url, PATHINFO_EXTENSION);
            // 长宽检测
            if ($w && $h) {
                return $originalImgPath . '.' . $img_ext . '_' . $w . 'x' . $h . '.' . $img_ext;
            }

            if ($w && !$h) {
                return $originalImgPath . '.' . $img_ext . '_' . $w . 'x' . $w . '.' . $img_ext;
            }

            if (!$w && $h) {
                return $originalImgPath . '.' . $img_ext . '_' . $h . 'x' . $h . '.' . $img_ext;
            }

            if (!$w && !$h) {
                return $originalImgPath . '.' . $img_ext;
            }
        }

        return $img_url;
    }

    // 获取原始图片未带后缀
    private static function getOriginalWithoutExt($img_url)
    {
        if ($img_url) {
            $img_ext = pathinfo($img_url, PATHINFO_EXTENSION);
            // like /avatar/M00/00/01/wKgBqFJyG2iAfHD0AAAxCAGuAII166.jpg_430x430.jpg
            if (preg_match('/^(.*)\.' . $img_ext . '(_([0-9]+)x([0-9]+)\.' . $img_ext . ')?$/is', $img_url, $match)) {
                return $match[1];
            }
        }
        return "";
    }
}