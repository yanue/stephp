<?php
function add_include_path ($path)
{
    foreach (func_get_args() AS $path)
    {
        if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
        {
            continue;
        }

        $paths = explode(PATH_SEPARATOR, get_include_path());

        if (array_search($path, $paths) === false)
            array_push($paths, $path);

        set_include_path(implode(PATH_SEPARATOR, $paths));
    }
}

echo $p = realpath('/var/www/test/library/..');
add_include_path($p);
add_include_path(dirname(__FILE__));
spl_autoload_register(function($className){
    $fileName = '';
    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileClass = strtolower($fileName) . str_replace('_', DIRECTORY_SEPARATOR, $className);
    include strtolower($fileClass).'.php';
});
use Library1\it\pht;
//new \Library1\Test();
//new pht();

echo get_include_path();
echo '<br>';
$class = '\Library\Test\My\Atest';
if(class_exists($class,true)){
    echo 'hello';
}else{
    echo 'no!!!!!!!!!!!!!!';
}