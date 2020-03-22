<?php
	require_once("configuration.php");
	
	// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
	//ini_set('session.cookie_domain',COOKIE_URL);
	session_set_cookie_params(9000,"/",COOKIE_URL);
	session_start();

	// Rapporter toutes les erreurs PHP
	//error_reporting(E_ALL);

	require_once('includes/functions.php');
	
	// Détection de la langue à utiliser
	$_SESSION['lang']='en';
	
	// Get page to display
	$_SESSION['page']=getRequestedUri();
	
	// Call the dispatcher to process the request
	include_once('pages/'.$_SESSION['page'].'/index.php');


	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($vars[$i]);
	}
	unset($vars,$i);
	
	gc_collect_cycles();
?>