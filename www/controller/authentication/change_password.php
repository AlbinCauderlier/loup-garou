<?php

require_once('../../configuration.php');
require_once("../../includes/functions.php");

// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
ini_set("session.cookie_domain",COOKIE_URL);
session_set_cookie_params(3000,"/",COOKIE_URL);
session_start();

// Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
if(!isset($_SESSION['isConnected']) || empty($_SESSION['isConnected']) || !isset($_SESSION['login']) || empty($_SESSION['login'])){
	// Inclusion des paramètres d'authentication
	require_once("controller/authentication/config.php");
	$_SESSION['error_message'].='Connexion expired';
	header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
	exit;
}

// Détection de la langue à utiliser (utile si session a expiré suite à une page non utilisée depuis plusieurs minutes)
if(!isset($_SESSION["lang"]) || empty($_SESSION["lang"])){
	// Utilise la fonction de détection de la langue.
	require_once("./../../lib/lang/php/lang.php");
	$_SESSION["lang"] = detectLang();
}

// Inclusion des paramètres d"authentication
require_once('config.php');
require_once('../../lib/auth/php/authentication.php');

// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
require_once('../../lib/auth/lang/'.$_SESSION['lang'].'.lang.php');

// Réinitialisation des messages d"erreurs
reset_messages();
$user_id = get_user_id( $_SESSION["login"] );


if($_SERVER['REQUEST_METHOD']!=='POST'){
	$_SESSION['error_message'].='Invalid request - Post';
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - INVALID FORM METHOD");
	header('Location: '.AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}

// 1. VERIFICATION DE LA PROVENANCE DU FORMULAIRE
// I.1. Vérification de la provenance des données
$sites=array(SECURITY_PAGE, SECURITY_PAGE."/", AUTHENTICATION_CHANGE_PASSWORD_PAGE, AUTHENTICATION_CHANGE_PASSWORD_PAGE."/");
if(isset($_SERVER["HTTP_REFERER"]) && !in_array($_SERVER["HTTP_REFERER"],$sites)){
	$_SESSION["error_message"] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE." - Invalid page request<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - INVALID HTTP_REFERER");
	header("Location: ".AUTHENTICATION_LOGOUT_PAGE);
	exit;
}


// I.2. Vérification du contenu des champs
require_once("../../lib/form/php/check_form.php");

// Field current-password
if(!checkPostMandatory("current-password")){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD." - Invalid current password<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - MISSING CURRENT PASSWORD");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$_POST["current-password"]=trim($_POST["current-password"]);
if(!checkLenght($_POST["current-password"],intval(AUTHENTICATION_PASSWORD_MIN_LENGHT),intval(AUTHENTICATION_PASSWORD_MAX_LENGHT))){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - CURRENT PASSWORD LENGHT");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$current_password = md5($_POST["current-password"]);


// Field password_1
if(!checkPostMandatory("new-password1")){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - PASSWORD 1 MISSING");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$_POST["new-password1"]=trim($_POST["new-password1"]);
if(!checkLenght($_POST["new-password1"],intval(AUTHENTICATION_PASSWORD_MIN_LENGHT),intval(AUTHENTICATION_PASSWORD_MAX_LENGHT))){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - PASSWORD 1 LENGHT");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$new_password1=md5($_POST["new-password1"]);


// Field password_2
if(!checkPostMandatory("new-password2")){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - PASSWORD 2 MISSING");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$_POST["new-password2"]=trim($_POST["new-password2"]);
if(!checkLenght($_POST["new-password2"],intval(AUTHENTICATION_PASSWORD_MIN_LENGHT),intval(AUTHENTICATION_PASSWORD_MAX_LENGHT)) ){
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - PASSWORD 2 LENGHT");
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_INVALID_PASSWORD."<br/>";
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}
$new_password2 = md5($_POST["new-password2"]);




// VERIFICATION QUE LES DEUX MOTS DE PASSE SONT IDENTIQUES
if( $new_password1 !== $new_password2 ){
	$_SESSION["error_message"].=DIFFERENT_NEW_PASSWORDS_ERROR_MESSAGE."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - DIFFERENT PASSWORD 1 AND 2");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}

// VERIFICATION QUE LES NOUVEAUW PASSWORD NE SONT PAS IDENTIQUE A L'ACTUEL
if( $new_password1 === $current_password ){
	$_SESSION["error_message"].="The new password is the same than the current one.<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - IDENTIQUE NEW AND CURRENT PASSWORDS");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}


// Vérification que l"ancien mot de passe est le bon !!!!


//TODO - Tout ce qui est avant cette ligne, doit être déjà vérifié dans la partie VIEWS ! (via un .js ?)



// RECUPERATION ET VERIFICATION DU PASSWORD
/**
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si absent, alors redirection vers "registration" avec message de demande d"enregistrement de l"adresse e-mail.
 *  II.3. Si un password existe déjà, retour à registration avec indication qu"il s"agit d"un changement de mot de passe.
 */
// II.1. VERIFICATION EN BdD QUE LE LOGIN EST ENREGISTRE AVEC UNE ADRESSE E-MAIL VALIDEE
if(!is_an_user_recorded($_SESSION["login"])){
	$_SESSION["error_message"].=UNKNOWN_EMAIL_ERROR_MESSAGE."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - UNKNOWN USER");
	header("Location: ".AUTHENTICATION_LOGOUT_PAGE);
	exit;
}

// II.2. VERIFICATION EN BdD DU PASSWORD
if(!is_password_valid( $user_id ,$current_password )){
	$_SESSION["error_message"].=AUTHENTICATION_ERROR_WRONG_PASSWORD."<br/>";
	db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD FAILED - INVALID CURRENT PASSWORD ");
	header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
	exit;
}


// CHANGEMENT DU PASSWORD ET RETOUR A LA PAGE LOGIN
change_password( $user_id, $current_password, $new_password1 );

// Enregistrement de l'action dans l'historique de l'user
db_log( $user_id." - ".$_SESSION['login'] , "AUTH - CHANGE PASSWORD SUCCEED");


//Réinitialisation du message d"erreur
$_SESSION["success_message"]=AUTHENTICATION_PASSWORD_CHANGED_OK;

// RETOUR A LA PAGE DU FORMULAIRE
header("Location: ".AUTHENTICATION_CHANGE_PASSWORD_PAGE);
exit;

?>