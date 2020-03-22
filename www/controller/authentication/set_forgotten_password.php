<?php  


/**
 *	ALGORITHME
 *
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance
 *
 * 	I.2. Vérification du contenu
 * 		I.2.a) Vérification du format du 1er mot de passe
 * 		I.2.b) Vérification du format du 2e mot de passe
 * 		I.2.c) Vérification que les mots de passe sont identiques
 * 		I.2.c) Si erreur, retour à la page "set_password".
 *
 *
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si absent, alors redirection vers "registration" avec message de demande d'enregistrement de l'adresse e-mail.
 *  II.3. Si un password existe déjà, retour à registration avec indication qu'il s'agit d'un changement de mot de passe.
 *
 *
 * III. ACTIONS SUR LA BdD
 * 	III.1. Enregistrement du mot de passe en BdD + indication des autres données
 *
 *
 * IV. CONFIRMATIONS
 *	IV.1. Envoi d'un e-mail de confirmation que le mot de passe est maintenant enregistré.
 *
 * V. INSCRIPTION DE L'UTILISATEUR
 * V.1. Inscription de l'utilisateur dans la BdD User.
 *
 * VI. REDIRECTION
 * 	VI.1. Redirection vers la page de paramétrage.
 *
 */


require_once("../../configuration.php");

// Si utilisateur loggué, prolonger la durée de la session.
// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
ini_set("session.cookie_domain",COOKIE_URL);
session_set_cookie_params(9000,"/",COOKIE_URL);
session_start();


// Détection de la langue à utiliser (utile si session a expiré suite à une page non utilisée depuis plusieurs minutes)
if(!isset($_SESSION["lang"]) || empty($_SESSION["lang"])){
	// Utilise la fonction de détection de la langue.
	require_once(ROOT_PATH."/lib/lang/php/lang.php");
	$_SESSION['lang']=detectLang();
}

// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
require_once(ROOT_PATH.'/lib/authentication/lang/'.$_SESSION['lang'].'.lang.php');


// Inclusion des paramètres d'authentication
require_once("config/config.php");
require_once(ROOT_PATH."/controller/user/config/config.php");

// Réinitialisation des messages d'erreurs
require_once(ROOT_PATH."/lib/authentication/php/authentication.php");
reset_authentication_messages();
$_SESSION['set_password_form_error_message']="";


// TODO - Adapter la redirection vers param.

/**
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance
 *
 * 	I.2. Vérification du contenu
 * 		I.2.a) Vérification du format du 1er mot de passe
 * 		I.2.b) Vérification du format du 2e mot de passe
 * 		I.2.c) Vérification que les mots de passe sont identiques
 * 		I.2.c) Si erreur, retour à la page "set_password".
 */
// I.1. Vérification de la provenance des données
$sites=array(	AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE.'/','https://ar.'.COOKIE_URL.'/forgotten_password/','https://de.'.COOKIE_URL.'/forgotten_password/','https://en.'.COOKIE_URL.'/forgotten_password/','https://es.'.COOKIE_URL.'/forgotten_password/','https://fr.'.COOKIE_URL.'/forgotten_password/','https://it.'.COOKIE_URL.'/forgotten_password/','https://ru.'.COOKIE_URL.'/forgotten_password/',
				AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE    ,'https://ar.'.COOKIE_URL.'/forgotten_password' ,'https://de.'.COOKIE_URL.'/forgotten_password' ,'https://en.'.COOKIE_URL.'/forgotten_password' ,'https://es.'.COOKIE_URL.'/forgotten_password' ,'https://fr.'.COOKIE_URL.'/forgotten_password' ,'https://it.'.COOKIE_URL.'/forgotten_password' ,'https://ru.'.COOKIE_URL.'/forgotten_password');

if(isset($_SERVER["HTTP_REFERER"]) && !in_array($_SERVER['HTTP_REFERER'],$sites) ){
	$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE;
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}


// I.2. Vérification du contenu des champs
require_once(ROOT_PATH."/lib/form/php/check_form.php");


// Field password_1
if(!checkPostMandatory('new_password_1')){
	$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_INVALID_PASSWORD;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;	
}
$_POST["new_password_1"]=trim($_POST["new_password_1"]);
if(!checkLenght($_POST['new_password_1'],intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT))){
	$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_INVALID_PASSWORD;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;	
}
$_POST['new_password_1']=md5($_POST['new_password_1']);


// Field password_2
if(!checkPostMandatory('new_password_2')){
	$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_INVALID_PASSWORD;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
$_POST["new_password_2"]=trim($_POST["new_password_2"]);
if(!checkLenght($_POST['new_password_2'],intval(AUTHENTICATION_PASSWORD_MIN_LENGHT), intval(AUTHENTICATION_PASSWORD_MAX_LENGHT))){
	$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_INVALID_PASSWORD;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;	
}
$_POST['new_password_2']=md5($_POST['new_password_2']);


// VERIFICATION QUE LES DEUX MOTS DE PASSE SONT IDENTIQUES
if(strcmp($_POST['new_password_1'],$_POST['new_password_2'])!==0){
	$_SESSION['set_password_form_error_message'].=DIFFERENT_NEW_PASSWORDS_ERROR_MESSAGE;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;	
}


/**
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si absent, alors redirection vers "registration" avec message de demande d'enregistrement de l'adresse e-mail.
 *  II.3. Si un password existe déjà, retour à registration avec indication qu'il s'agit d'un changement de mot de passe.
 */
// II.1. VERIFICATION EN BdD QUE LE LOGIN EST ENREGISTRE AVEC UNE ADRESSE E-MAIL VALIDEE
if(!is_login_recorded($_SESSION['login'])){
	$_SESSION['set_password_form_error_message'].=UNKNOWN_EMAIL_ERROR_MESSAGE;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}

// II.2. VERIFICATION EN BDD QUE L'ADRESSE E-MAIL A DEJA ETE VALIDEE.
if(!is_login_confirmed($_SESSION['login'])){
	$_SESSION['set_password_form_error_message'].=UNCONFIRMED_EMAIL_ERROR_MESSAGE;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}




/**
 * III. ACTIONS SUR LA BdD
 * 	III.1. Enregistrement du mot de passe en BdD + indication des autres données
 *
 */
// SET PASSWORD
mysql_myquery(SQL_AUTHENTICATION_BASE,authentication_set_password_get_sql_request($_SESSION['login'],$_POST['new_password_1']));

// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"SET_FORGOTTEN_PASSWORD");





/** 
 * IV. CONFIRMATIONS
 *	IV.1. Envoi d'un e-mail de confirmation que le mot de passe est maintenant enregistré.
 *
 */
//TODO - Envoi d'un e-mail de confirmation du mot de passe avec lien pour "si vous n'êtes pas à l'origine de ce changement, merci de client sur le lien ci-dessous pour blocage de votre compte".






/** 
 * VI. REDIRECTION
 * 	VI.1. Redirection vers la page de paramétrage.
 */
$_SESSION['isConnected']=true;

header('Location: '.AUTHENTICATION_FIRST_LOGIN_PAGE);
exit;

?>