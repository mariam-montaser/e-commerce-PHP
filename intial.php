<?php

//error reporting
//ini_set('display_error', 'on');
//error_reporting('E_ALL');

$session = '';
 
if (isset($_SESSION['user'])) {

	$session = $_SESSION['user'];
}

include 'admin/connect.php';

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
