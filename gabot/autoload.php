<?php
spl_autoload_register(function($class){
    $root_directory = dirname(__DIR__).'/';
    $file = $root_directory.str_replace('\\', '/', $class). '.php';
    if(file_exists($file)){
        require $file;
    }
});
?>