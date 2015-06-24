<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 2014/12/16
 * Time: 14:46
 */

namespace Library\Util;

// 获取经纬度直接距离
class LatLng
{
    const EARTH_RADIUS = 6378137;
    const PI = 3.1415926;

    public static function init()
    {
        return new self();
    }

    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = $this->rad($lat1);
        $radLat2 = $this->rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = $this->rad($lng1) - $this->rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) +
                cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * self::EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $this->LongFormat($s);
    }

    private function LongFormat($long)
    {
        if ($long > 1000) {
            return round($long / 1000) . "公里";
        } else if ($long < 1000 && $long > 0) {
            return round($long) . "米";
        }

        return "附近";
    }

    private function rad($d)
    {
        return $d * self::PI / 180.0;
    }
} 