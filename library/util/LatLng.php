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

    /**
     * 获取两点距离(米)
     *
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @param bool|true $format 是否格式化
     * @return string
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2, $format = false)
    {
        $radLat1 = $this->rad($lat1);
        $radLat2 = $this->rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = $this->rad($lng1) - $this->rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) +
                cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * self::EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $format ? $this->LongFormat($s) : $s;
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

    /**
     * 获取两点方位(角度)
     * -- -180到180范围
     *
     * @param $lat_a
     * @param $lng_a
     * @param $lat_b
     * @param $lng_b
     * @return float
     */
    public function getRotation($lat_a, $lng_a, $lat_b, $lng_b)
    {
        $lat_a = $lat_a * self::PI / 180;

        $lng_a = $lng_a * self::PI / 180;

        $lat_b = $lat_b * self::PI / 180;

        $lng_b = $lng_b * self::PI / 180;


        $d = sin($lat_a) * sin($lat_b) + cos($lat_a) * cos($lat_b) * cos($lng_b - $lng_a);

        $d = sqrt(1 - $d * $d);
        if ($d != 0) {
            $d = cos($lat_b) * sin($lng_b - $lng_a) / $d;

            $d = asin($d) * 180 / self::PI;
        }

        return $d;
    }
} 