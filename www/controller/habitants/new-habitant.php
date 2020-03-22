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

	// $sites = array(	ROOT_URL."/signup/", ROOT_URL."/", ROOT_URL  );

	// if(isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'],$sites)){
	// 	exit('Request not coming from the website');
	// }

	if(!defined("PHP_EOL")){
		define("PHP_EOL", "\r\n");	
	}

	// Sanitize request
	$village_id = (isset($_POST['village-id']) ? strip_tags($_POST['village-id']) : '');
	$user_id = (isset($_POST['user-id']) ? strip_tags($_POST['user-id']) : '');
	$card_name = (isset($_POST['card-name']) ? strip_tags($_POST['card-name']) : '');

	if( !isset($_POST['village-id']) || empty($_POST['village-id']) ){
		echo('village-id required');
	}

	if( !isset($_POST['user-id']) || empty($_POST['user-id']) ){
		echo('user-id required');
	}

	if( !isset($_POST['card-name']) || empty($_POST['card-name']) ){
		echo('card-name required');
	}

	// CREATION DE L'UTILISATEUR
	$query_new_habitant = "INSERT INTO `habitants` (`habitant-id`, `habitant-village`, `habitant-user`, `habitant-card`, `habitant-card-displayed`, `habitant-is-the-mayor`) 
											VALUES (NULL, '".$village_id."', '".$user_id."', '".$card_name."', NULL, NULL);";

	$conn_habitant = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);

	mysqli_query($conn_habitant, $query_new_habitant);
	mysqli_close($conn_habitant);

	header("location: /village/".$village_id."/");
	exit();
?>