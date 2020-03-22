<?php
	require_once("../../configuration.php");
	require_once("../../includes/functions.php");
	
	// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
	ini_set('session.cookie_domain',COOKIE_URL);
	session_set_cookie_params(9000,"/",COOKIE_URL);
	session_start();

	if($_SERVER['REQUEST_METHOD']!=='POST'){
		exit('Invalid request');
	}

	$sites = array(	ROOT_URL."/signup/", ROOT_URL."/", ROOT_URL  );

	if(isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'],$sites)){
		exit('Request not coming from the website');
	}

	if(!defined("PHP_EOL")){
		define("PHP_EOL", "\r\n");	
	}

	// Sanitize request
	$firstname = (isset($_POST['firstname']) ? strip_tags($_POST['firstname']) : '');
	$lastname = (isset($_POST['lastname']) ? strip_tags($_POST['lastname']) : '');
	$email = (isset($_POST['email']) ? strip_tags($_POST['email']) : '');
	$company_name = (isset($_POST['company_name']) ? strip_tags($_POST['company_name']) : '');
	$company_country = (isset($_POST['company_country']) ? strip_tags($_POST['company_country']) : '');
	$password1 = (isset($_POST['password']) ? strip_tags($_POST['password']) : '');
	$password2 = (isset($_POST['password_confirmation']) ? strip_tags($_POST['password_confirmation']) : '');


	// Validate inputs
	if( !isset($_POST['firstname']) || empty($_POST['firstname']) ){
		exit('Fistname required');
	}
	if(empty($lastname)){
		exit('Name required');
	}
	if(empty($email)){
		exit('Email required');
	}
	if(empty($company_name)){
		exit('Company Name required');
	}
	if(empty($company_country)){
		exit('Company Country required');
	}
	if(empty($password1)){
		exit('Password1 required');
	}
	if(empty($password2)){
		exit('Password2 required');
	}


	// Contrôle des contenus
	if($password1!==$password2) {
		exit('Same passwords required');
	}
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		exit('Email is not a validated email address');
	}



	header("location: /signup-confirmation/");
	exit();
?>