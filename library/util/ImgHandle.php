<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 8/12/15
 * Time: 11:07
 */

namespace library\util;

use Gmagick;
use GmagickDraw;
use GmagickPixel;
use Library\Core\Plugin;


class ImgHandle extends Plugin
{
    public $Gmagick;

    public function __construct()
    {
        parent::__construct();
        $this->image = new Gmagick();
    }

    /**
     * 获取原图按比例缩放后截图
     *
     * @param $buff
     * @param int $scale
     * @param $width
     * @param $height
     * @param int $x
     * @param int $y
     * @return array
     */
    public function crop_buff($buff, $scale = 1, $width, $height, $x = 0, $y = 0)
    {
        # 读取文件流
        $this->image->readImageBlob($buff);
        $srcImage = $this->image->getImageGeometry(); //获取源图片宽和高

        $new_width = intval($srcImage['width'] * $scale);
        $new_height = intval($srcImage['height'] * $scale);
        $this->image->scaleImage($new_width, $new_height, true);
        $this->image->cropimage($width, $height, $x, $y);
        # 获取文件流
        return $this->imginfo();
    }


    /**
     * 截取图片最大正方形区域
     *
     * @param $buff
     * @return array
     */
    public function cropMaxSquare($buff)
    {
        # 读取文件流
        $this->image->readImageBlob($buff);
        $srcImage = $this->image->getImageGeometry(); //获取源图片宽和高

        $width = $srcImage['width'];
        $height = $srcImage['height'];

        if ($width > $height) {
            $x = ($width - $height) / 2;
            $y = 0;
            $sqare = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $sqare = $width;
        }

        $this->image->cropimage($sqare, $sqare, $x, $y);
        # 获取文件流
        return $this->imginfo();
    }

    /**
     * 缩放原图到系统默认最大
     *
     * @param $buff
     * @param $ext
     * @param $width
     * @param $height
     * @return array
     */
    public function resize_original($buff, $ext, $width, $height)
    {

        $this->image->readImageBlob($buff);

        $w = $this->image->getImageWidth();
        $h = $this->image->getImageHeight();
        // 超出范围
        if ($w > $width || $h > $height) {
            if ($ext == 'gif') {
                $this->scaleGif($width, $height);
            } else {
                $this->image->scaleimage($width, $height, true);
            }
        }

        return $this->imginfo();
    }

    /**
     * 缩放gif图片
     *
     * @param $width
     * @param $height
     */
    private function scaleGif($width, $height)
    {
        $res = $this->image->coalesceImages();
        // 缩放每一帧
        do {
            $res->scaleimage($width, $height, true);
        } while ($res->nextImage());
        // 合并
        $this->image = $res->deconstructImages();
    }

    /**
     * 缩放图片
     *
     * @param $buff
     * @param $ext
     * @param $width
     * @param $height
     * @return array
     */
    public function thumb($buff, $ext, $width, $height)
    {
        // for gif
        $this->image->readImageBlob($buff);
        $w = $this->image->getImageWidth();
        $h = $this->image->getImageHeight();

        // can't be Enlarge
        if (!($w > $width || $h > $height)) {
            return $this->imginfo();
        }

        if ($ext == 'gif') {
            $this->scaleGif($width, $height);
        } else {
            // 神奇的事情 这里必须重新new Gmagick才能
            $this->image->scaleImage($width, $height, true);
        }

        return $this->imginfo();
    }

    /**
     * 返回图片信息
     *
     * @return array
     */
    private function imginfo()
    {
        # 获取转换后的信息
        $buff = $this->image->getImageBlob();
        $w = $this->image->getImageWidth();
        $h = $this->image->getImageHeight();
        // 释放资源
        $this->image->destroy();

        return array('buff' => $buff, 'width' => $w, 'height' => $h);
    }

    // 添加水印图片
    public function add_watermark($path, $x = 0, $y = 0)
    {
        $watermark = new Gmagick($path);
        $draw = new GmagickDraw();
        $draw->composite($watermark->getImageCompose(), $x, $y, $watermark->getImageWidth(), $watermark->getimageheight(), $watermark);

        if ($this->type == 'gif') {
            $image = $this->image;
            $canvas = new Gmagick();
            $images = $image->coalesceImages();
            foreach ($image as $frame) {
                $img = new Gmagick();
                $img->readImageBlob($frame);
                $img->drawImage($draw);

                $canvas->addImage($img);
                $canvas->setImageDelay($img->getImageDelay());
            }
            $image->destroy();
            $this->image = $canvas;
        } else {
            $this->image->drawImage($draw);
        }
    }

    /**
     * 文字水印
     *
     * @param $buff
     * @param $text
     * @param int $fontSize
     * @param string $fontColor
     * @param string $fontPath
     * @param float $angle
     * @param int $position
     * @param int $margin
     * @return array
     */
    public function txtWaterMark($buff, $text, $fontSize = 20, $fontColor = '#ccc', $fontPath = '', $angle = 1.0, $position = 0, $margin = 10)
    {
        # 读取文件流
        $this->image->readImageBlob($buff);
        $width = $this->image->getimagewidth();
        $height = $this->image->getimageheight();

//        $text = iconv('', "utf-8", $text);
//        $text = iconv("GB18030", "UTF-8//IGNORE", $text);
        $text = mb_convert_encoding($text, "GBK", "auto");
        $draw = new GmagickDraw();
        $fontPath ? $draw->setFont($fontPath) : null; #/usr/share/font/simsun.ttc
        $draw->setFontSize($fontSize); #字体大小
        $draw->setFillColor(new GmagickPixel($fontColor)); //设置字体颜色

        $w = strlen($text) * $fontSize;
        $h = $fontSize * 2;
        if ($position == 1) {
            $x = $width - $w - $margin;
            $y = $margin;
        } elseif ($position == 2) {
            $x = $width - $w - $margin;
            $y = $height - $h - $margin;
        } elseif ($position == 3) {
            $x = $margin;
            $y = $height - $h - $margin;
        } elseif ($position == 4) {
            $x = $y = $margin;
        } elseif ($position == 5) {
            $x = ceil(($width - $w) / 2);
            $y = ceil(($height - $h) / 2);
        } else {
            $x = rand(($margin), ($width - $w - $margin));
            $y = rand(($margin), ($height - $h - $margin));
        }
        $this->image->drawimage($draw);
        $this->image->annotateimage($draw, $x, $y, $angle, $text); // 参数说明 GmagickDraw对象 x轴 y轴 倾斜度 文字水印

        return $this->imginfo();
    }

    /**
     * 添加图片到图片
     *
     * @param $buff
     * @param $coverBuff
     * @param $x
     * @param $y
     * @param $cover_w
     * @param $cover_h
     * @return array
     */
    public function addImg($buff, $coverBuff, $x, $y, $cover_w = 0, $cover_h = 0)
    {
        # 读取文件流
        $this->image->readImageBlob($buff);

        # 读取
        $cover = new Gmagick($coverBuff);
        $width = $cover->getimagewidth();
        $height = $cover->getimageheight();

        # 调到指定尺寸
        if ($cover_w && $cover_h && ($width != $cover_w || $height != $cover_h)) {
            $cover->scaleImage($cover_w, $cover_h, true);
        }

        $this->image->compositeImage($cover, Gmagick::COMPOSITE_OVER, $x, $y);

        return $this->imginfo();
    }

    /**
     * 添加文字到图片
     *
     * @param $buff
     * @param $text
     * @param $x
     * @param $y
     * @param $fontPath
     * @param int $fontSize
     * @param string $fontColor
     * @return array
     */
    public function addTextToImg($buff, $text, $x, $y, $fontPath, $fontSize = 20, $fontColor = '#666')
    {
        $this->image->readImageBlob($buff);

        # 读取文件流
        $draw = new GmagickDraw();
        $fontPath ? $draw->setFont($fontPath) : null; #/usr/share/font/simsun.ttc
        $draw->setFontSize($fontSize); #字体大小
        $draw->setFillColor(new GmagickPixel($fontColor)); //设置字体颜色
        $this->image->drawimage($draw);
        $this->image->annotateimage($draw, $x, $y, 1, $text); // 参数说明 GmagickDraw对象 x轴 y轴 倾斜度 文字水印
//        $this->image->annotateimage($draw, $x, $y + 60, 1, "12312312"); // 参数说明 GmagickDraw对象 x轴 y轴 倾斜度 文字水印
        return $this->imginfo();
    }

    /**
     * @param $buff
     * @param array $text_array_param array([$text,$x, $y, $fontPath, $fontSize = 20, $fontColor = '#666'])
     */
    public function addMultiText($buff, array $text_array_param)
    {
        $this->image->readImageBlob($buff);

    }

    public function getPic()
    {
        $text = '中粮屯河（sh600737）';//中粮屯河（sh600737）
        $watermark = '305988103123zczcxzas';
        $len = strlen($text);
        $width = 10.5 * (($len - 8) / 3 * 2 + 8);
        $height = 26;

        $imagick = new Imagick();
        $color_transparent = new ImagickPixel('#ffffff');   //transparent 透明色

        $imagick->newImage($width, $height, $color_transparent, 'jpg');
        //$imagick->borderimage('#000000', 1, 1);

        $style['font_size'] = 12;
        $style['fill_color'] = '#000000';
        for ($num = strlen($watermark); $num >= 0; $num--) {
            $this->add_text($imagick, substr($watermark, $num, 1), 2 + ($num * 8), 30, 1, $style);
            $this->add_text($imagick, substr($watermark, $num, 1), 2 + ($num * 8), 5, 1, $style);
        }

        //return;
        $style['font_size'] = 20;
        $style['fill_color'] = '#FF0000';
        $style['font'] = './msyh.ttf'; ///微软雅黑字体  解决中文乱码
        //$text=mb_convert_encoding($text,'UTF-8'); //iconv("GBK","UTF-8//IGNORE",$text);
        $this->add_text($imagick, $text, 2, 20, 0, $style);

        header('Content-type: ' . strtolower($imagick->getImageFormat()));
        echo $imagick->getImagesBlob();
    }

    // 添加水印文字
    public function add_text(& $imagick, $text, $x = 0, $y = 0, $angle = 0, $style = array())
    {
        $draw = new ImagickDraw ();
        if (isset ($style ['font']))
            $draw->setFont($style ['font']);
        if (isset ($style ['font_size']))
            $draw->setFontSize($style ['font_size']);
        if (isset ($style ['fill_color']))
            $draw->setFillColor($style ['fill_color']);
        if (isset ($style ['under_color']))
            $draw->setTextUnderColor($style ['under_color']);
        if (isset ($style ['font_family']))
            $draw->setfontfamily($style ['font_family']);
        if (isset ($style ['font']))
            $draw->setfont($style ['font']);
        $draw->settextencoding('UTF-8');
        if (strtolower($imagick->getImageFormat()) == 'gif') {
            foreach ($imagick as $frame) {
                $frame->annotateImage($draw, $x, $y, $angle, $text);
            }
        } else {
            $imagick->annotateImage($draw, $x, $y, $angle, $text);
        }
    }
}