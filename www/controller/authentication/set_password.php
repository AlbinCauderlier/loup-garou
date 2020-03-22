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
require_once(ROOT_PATH.'/lib/user/lang/'.$_SESSION['lang'].'.lang.php');


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
$sites=array(	AUTHENTICATION_REGISTRATION_PAGE.'/','https://ar.'.COOKIE_URL.'/signup/','https://de.'.COOKIE_URL.'/signup/','https://en.'.COOKIE_URL.'/signup/','https://es.'.COOKIE_URL.'/signup/','https://fr.'.COOKIE_URL.'/signup/','https://it.'.COOKIE_URL.'/signup/','https://ru.'.COOKIE_URL.'/signup/',
				AUTHENTICATION_REGISTRATION_PAGE    ,'https://ar.'.COOKIE_URL.'/signup' ,'https://de.'.COOKIE_URL.'/signup' ,'https://en.'.COOKIE_URL.'/signup' ,'https://es.'.COOKIE_URL.'/signup' ,'https://fr.'.COOKIE_URL.'/signup' ,'https://it.'.COOKIE_URL.'/signup' ,'https://ru.'.COOKIE_URL.'/signup' );

if(isset($_SERVER["HTTP_REFERER"])){
	if(!in_array($_SERVER['HTTP_REFERER'],$sites)){
		$_SESSION['set_password_form_error_message'].=AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE;
		header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
		exit;
	}
}


// I.2. Vérification du contenu des champs
require_once(ROOT_PATH."/lib/form/php/check_form.php");

// Field 'user_name'
if(!checkPostMandatory('user_name')){
	$_SESSION['set_user_name_error_message'].="Aucun nom fourni.";
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
$_POST['user_name']=trim($_POST['user_name']);
if(!checkCaracteresSpeciaux($_POST['user_name'])){
	$_SESSION['set_user_name_error_message'].="Le nom fourni a été refusé car il contient des caractères spéciaux.";
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
if(!checkLenght($_POST['user_name'],intval(NAME_MIN_LENGHT),intval(NAME_MAX_LENGHT))){
	$_SESSION['set_user_name_error_message'].="Le nom doit être composé de 3 à 64 caractères.";
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}


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

// II.2. VERIFICATION EN BDD QUE L'ADRESSE E-MAIL N'A PAS DEJA ETE VALIDEE.
if(!is_login_confirmed($_SESSION['login'])){
	$_SESSION['set_password_form_error_message'].=UNCONFIRMED_EMAIL_ERROR_MESSAGE;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}

// II.3. VERIFICATION QU'IL N'Y A PAS DEJA DE PASSWORD
if(!is_password_empty($_SESSION['login'])){
	// Normalement, cette partie est impossible... A supprimer ?
	$_SESSION['set_password_form_error_message'].="Vous avez déjà enregistré un mot de passe. Il s'agit d'un changement de mot de passe.";
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

require_once(MUBIZ_API_ROOT_PATH+"/controller/lib/lang/php/lang.php");
set_authentification_lang($_SESSION['login'],$_SESSION['lang']);

// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"SET_PASSWORD");




/** 
 * IV. CONFIRMATIONS
 *	IV.1. Envoi d'un e-mail de confirmation que le mot de passe est maintenant enregistré.
 *
 */
//TODO - Envoi d'un e-mail de confirmation du mot de passe avec lien pour "si vous n'êtes pas à l'origine de ce changement, merci de client sur le lien ci-dessous pour blocage de votre compte".




/**
 * V. CREATION DE L'UTILISATEUR
 *	V.1. Création de l'utilisateur en BdD Utilisateur
 *  V.2. Création de l'utilisateur en BdD Social
 */
// V.1. Création du compte dans la table user
require_once(MUBIZ_API_ROOT_PATH+"/controller/lib/user/user.php");
create_user($_SESSION['login']);

// V.2. Création du compte dans la table user_social
require_once(MUBIZ_API_ROOT_PATH+"/controller/lib/social/social.php");
create_user_social($_SESSION['login']);



// SET USER NAME
require_once(MUBIZ_API_ROOT_PATH+"/controller/lib/user/user.php");

// Met la première lettre en Majuscule
$_POST['user_name']=ucfirst($_POST['user_name']);
set_username($_SESSION['login'],$_POST['user_name']);


// SET USER URL
// CONSTRUCTION DE LA USER_URL A PARTIR DE USER_NAME
require_once(ROOT_PATH."/includes/functions.php");
$user_url=str2url($_POST['user_name']);

// Vérification que le nom demandé n'est pas déjà enregistré.
if(is_user_url_recorded($user_url)){
	$_SESSION['set_user_name_error_message'].=USER_URL_NOT_AVAILABLE."<br/>";
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
set_user_url($_SESSION['login'],$user_url);


/** 
 * VI. REDIRECTION
 * 	VI.1. Redirection vers la page de paramétrage.
 */
$_SESSION['isConnected'] = true;

header('Location: '.AUTHENTICATION_FIRST_LOGIN_PAGE);
exit;

?>