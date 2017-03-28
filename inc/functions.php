<?php
function debug($variable){
	echo '<pre>' . print_r($variable , true) . '</pre>';
}

function str_random($length){
	$alphabet = "01234556789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
	return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only(){
	if(session_status() == PHP_SESSION_NONE){
		session_start();
	}
	if(!isset($_SESSION['auth'])){
		$_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
		header('location: login.php');
		exit();
	}
}