<?php 

include 'connect.php';

//routes

$tpl='includes/templates/'; //templates directory
$lang = 'includes/languages/';// languages directory
$func = 'includes/functions/';// functions directory
$css='layout/css/'; //css directory
$js='layout/js/'; //js directory


// include important files

include $func . 'function.php';
include $lang . 'english.php';
include $tpl . 'header.php';

// include navber in all pages except which have $nonavbar variable

if(!isset($noNavbar)){
	include $tpl . 'navbar.php';
}