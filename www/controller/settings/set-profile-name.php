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
  
	if(!defined("PHP_EOL")){
		define("PHP_EOL", "\r\n");	
	}

	// Sanitize request
	$user_firstname = (isset($_POST['user-firstname']) ? strip_tags($_POST['user-firstname']) : '');
	$user_lastname = (isset($_POST['user-lastname']) ? strip_tags($_POST['user-lastname']) : '');


	// Validate inputs
	if(empty($user_firstname)){
		exit('user_firstname required');
	}
	if(empty($user_lastname)){
		exit('user_lastname required');
	}


	/* UPDATE USER */
    $query_user = "UPDATE `users_data`	SET		`user-firstname` = '".$user_firstname."',
      											`user-lastname` = '".$user_lastname."' 
										WHERE 	`users_data`.`user-email-address` = '".$_POST['user-email-address']."'";

	$mysqli_users = new mysqli(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");
	if ($mysqli_users->connect_errno) {
	    printf("Connect failed: %s\n", $mysqli_users->connect_error);
	    exit();
	}
	if (!$mysqli_users->query($query_user)) {
	    printf("Errormessage: %s\n", $mysqli_users->error);
	}
	$mysqli_users->close();


	/* INSERT LOG */
    $data = $_POST['user-email-address']."-".$user_firstname."-".$user_lastname;
	db_log( $_POST['user-email-address'], "UPDATE USER" , $data );

	header("location: /user/settings/");
	exit();
?>