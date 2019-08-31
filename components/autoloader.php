<?php
spl_autoload_register(function($class){
    require_once $_SERVER['DOCUMENT_ROOT']."/components/classes/class_$class.php";
});
?>