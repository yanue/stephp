<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');


class View
{
    protected $_content = '';
    protected $_layout = 'layout';
    public function __construct (){
    }

    // render
    public function render($name){
        $file =  Bootstrap::$_moduleCurPath.'views/'.$name.'.php';
        if(file_exists($file)){
            include_once $file;
        }
    }

    public function baseUrl($uri=''){
        return Request::baseUrl($uri);
    }

    public function setContent($filename=''){
       if($filename){
           $this->_content = $filename;
       }else{
           $this->_content = Bootstrap::$_controllerName.'/'.Bootstrap::$_actionName ;
       }
    }

    // set layout
    public function setLayout($layout='layout',$file='')
    {
        $this->setContent($file);
        $this->render($layout);
    }

    // for layout content
    public function content(){
        if($this->_content){
            $file = Bootstrap::$_moduleCurPath.'views/'.$this->_content.'.php';
            if(file_exists($file)){
                include_once $file;
            }
        }
    }

    // create pic url
    public function photoUrl($img_content,$w=200,$h=200){
        return IMAGE_SERVER.$w.'/'.$h.'/'.$img_content;
    }


    public function loadFile($filename){
        $file = ROOT_PATH. $filename.'.php';
        if(file_exists($file)){
            include_once $file;
        }
    }

}
