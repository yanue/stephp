<?php
$str = 'm.html';

$a = strripos($str,'.html');
if($a!==false && $a==strlen($str)-strlen()){
    echo $a;
}else{
    echo "asd";;

}