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
	$village_name = (isset($_POST['village-name']) ? strip_tags($_POST['village-name']) : '');

	if( !isset($_POST['village-name']) || empty($_POST['village-name']) ){
		// header("location: /games/");
		// exit();
		echo('village-name required');
	}

	// CREATION DE L'UTILISATEUR
	$query_new_village = "INSERT INTO `villages` (`village-id`, `village-name`, `village-jitsi-link`, `village-state`) 
										VALUES (NULL, '".$village_name."', 'https://jitsi.with-love.fr/".$village_name."', 'WAITING')";

	$conn_village = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);

	mysqli_query($conn_village, $query_new_village);
	mysqli_close($conn_village);

	header("location: /villages/");
	exit();
?>