<?php
	require_once('../../configuration.php');
	require_once("../../includes/functions.php");
	require_once("../../lib/auth/php/authentication.php");

	// Si utilisateur loggué, prolonger la durée de la session.
	// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
	ini_set('session.cookie_domain',COOKIE_URL);
	session_set_cookie_params(9000,"/",COOKIE_URL);
	session_start();

	// Détection de la langue à utiliser (utile si session a expiré suite à une page non utilisée depuis plusieurs minutes)
	if(!isset($_SESSION["lang"]) || empty($_SESSION["lang"])){
		// Utilise la fonction de détection de la langue.
		require_once("./../../lib/lang/php/lang.php");
		$_SESSION['lang']=detectLang();
	}

	// Inclusion des paramètres d'authentication
	require_once('config.php');

	// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
	require_once('./../../lib/auth/lang/'.$_SESSION['lang'].'.lang.php');

	// Réinitialisation des messages d'erreurs
	reset_messages();

	// Contrôler présence des champs
	// Sanitize request
	$email = (isset($_POST['email-address']) ? strip_tags($_POST['email-address']) : '');
	$password = (isset($_POST['password']) ? strip_tags($_POST['password']) : '');

	// TODO - Contrôler que le login est bien une adresse e-mail valide
	require_once('./../../lib/form/php/check_form.php');

	// I.1.a) Field e-mail_address
	if( !checkPostMandatory('email-address') ){
		$_SESSION['error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL." - Missing";
		header('Location: '.AUTHENTICATION_LOGIN_PAGE);
		exit;
	}
	if( !checkEmail($_POST['email-address']) ){
		$_SESSION['error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL." - Unvalid";
		header('Location: '.AUTHENTICATION_LOGIN_PAGE);
		exit;
	}
	if( !checkPostMandatory('password') || empty($password) ){
		$_SESSION['error_message'].=AUTHENTICATION_ERROR_INVALID_PASSWORD;
		header('Location: '.AUTHENTICATION_LOGIN_PAGE);
		exit;
	}


	// Contrôle que le login est connu en base
	if( !is_an_user_recorded( $_POST['email-address'] ) ){
		db_log( $_SESSION['login'] , "AUTH LOGIN FAILED - UNKNOWN ACCOUNT");
		$_SESSION['error_message'] = AUTHENTICATION_ERROR_UNKNOWN_LOGIN;
		$_SESSION['isConnected'] = false;
		header("location: ".AUTHENTICATION_LOGIN_PAGE);
		exit;
	}

	update_authentication_attempt( $user_id );
	$user_id = get_user_id($_POST['email-address']);
	$password = md5($_POST['password']);	

	// Contrôle du password
	if( !is_password_valid( $user_id , $password ) ){
		db_log( $_SESSION['login'] , "AUTH LOGIN FAILED - INVALID PASSWORD");
		$_SESSION['error_message'] = AUTHENTICATION_ERROR_WRONG_PASSWORD;
		$_SESSION['isConnected'] = false;
		header("location: ".AUTHENTICATION_LOGIN_PAGE);
		exit;
	}

	update_authentication_succeed( $user_id );

	$_SESSION['user-email-address'] = $_SESSION['login'] = $_POST['email-address'];
	$_SESSION['isConnected'] = true;

	db_log( $user_id." - ".$_SESSION['login'] , "AUTH LOGIN SUCCESS");

	header("location: ".AUTHENTICATION_FIRST_PAGE);
	exit;
?>