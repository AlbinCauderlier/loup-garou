<?php  

/**
 *	ALGORITHME
 *
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance (vide ici)
 *
 * 	I.2. Vérification du contenu
 * 		I.2.a) Vérification du format de l'adresse e-mail
 * 		I.2.b) Vérification du format de l'OTP (taille et caractères utilisés)
 * 		I.2.c) Si erreur, retour à la page "registration".
 *
 *
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si absent, alors redirection vers "registration" avec message de demande d'enregistrement de l'adresse e-mail.
 *  II.3. Si login est déjà actif,
 * 	II.4. Si OTP différent, ...
 *
 *
 * III. ACTIONS SUR LA BdD
 * 	III.1. Activation du compte
 * 	III.2. Suppression de l'OTP
 *
 *
 * IV. CONFIRMATIONS
 *	IV.1. Envoi d'un e-mail de confirmation de l'adresse
 *
 *
 * V. REDIRECTION
 * 	V.1. Redirection vers la page de saisie du mot de passe.
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

// Inclusion des paramètres d'authentication
require_once('config/config.php');

// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
require_once('../../lib/authentication/lang/'.$_SESSION['lang'].'.lang.php');

// Réinitialisation des messages d'erreurs
require_once(ROOT_PATH."/lib/authentication/php/authentication.php");
reset_authentication_messages();
$_SESSION['confirmation_error_message']="";


/**
 *
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification du contenu
 * 		I.1.a) Vérification du format de l'adresse e-mail
 * 		I.1.b) Vérification du format de l'OTP (taille et caractères utilisés)
 * 		I.1.c) Si erreur, retour à la page "registration".
 *
 */
// I.1. VERIFICATION DU CONTENU DES CHAMPS DU FORMULAIRE
require_once('../../lib/form/php/check_form.php');

// I.1.a) Field e-mail_address
if(!checkGetMandatory('login') || !checkLenght($_GET['login'],6,intval(AUTHENTICATION_EMAIL_MAX_LENGHT)) ){			// ||  filter_var($email, FILTER_VALIDATE_EMAIL) ){
	$_SESSION['confirmation_error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}
if(!checkEmail($_GET['login'])){
	$_SESSION['confirmation_error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}

// I.1.b) Field OTP
if(!checkGetMandatory('OTP')){
	// TODO - Faire un message d'erreur !
	$_SESSION['confirmation_error_message'].=AUTHENTICATION_ERROR_INVALID_OTP;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}
$_GET['OTP']=trim($_GET['OTP']);
// TODO - Vérification des caractères qu'utilise l'OTP.
if(!checkLenght($_GET['OTP'],RANDOMKEY_LENGHT,intval(RANDOMKEY_LENGHT))){
	$_SESSION['confirmation_error_message'].=AUTHENTICATION_ERROR_INVALID_OTP;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}

$_SESSION['login']=$_GET['login'];





/**
 * II. RECHERCHE EN BdD
 *  II.1. Si le login est absent de la BdD, alors redirection vers "registration" avec message de demande d'enregistrement de l'adresse e-mail.
 *  II.2. Si login est déjà actif, alors redirection vers "registration" avec une demande d'authentification.
 * 	II.3. Si OTP différent, alors redirection vers "registration" avec indication qu'un e-mail a été envoyé pour confirmation.
 */
// II.1. Si l'adresse e-mail n'est pas enregistrée, alors retour à l'inscription.
if(!is_login_recorded($_SESSION['login'])){
	$_SESSION['confirmation_error_message'].=UNKNOWN_EMAIL_ERROR_MESSAGE;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}

// II.2. Si un utilisateur s'inscrit, ne reçoit le 1er e-mail mais fait la demande de forgotten_password
if(!is_login_confirmed($_SESSION['login']) && is_password_empty($_SESSION['login'])){
	// TODO - Vérifier que ce test est nécessaire, et l'action à mener en conséquence
	$_SESSION['confirmation_error_message'].=EMPTY_PASSWORD_ERROR_MESSAGE;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}

// II.3. Si le login n'est pas encore actif
if(!is_login_confirmed($_SESSION['login'])){
	// TODO - Vérifier que ce test est nécessaire, et l'action à mener en conséquence
	$_SESSION['confirmation_error_message'].=PLEASE_LOG_IN_ERROR_MESSAGE;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}

// II.4. Si l'OTP est différent de celui enregistré, alors redirection avec indication qu'un e-mail a été envoyé pour confirmation.
if(!is_forgotten_password_OTP_correct($_SESSION['login'],$_GET['OTP'])){
	$_SESSION['confirmation_error_message'].=WRONG_OTP_ERROR_MESSAGE;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}



/**
 * III. ACTIONS SUR LA BdD
 */
// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"FORGOTTEN_PASSWORD_EMAIL_CONFIRMATION");



/**
 * IV. REDIRECTION
 * 	IV.1. Redirection vers la page de saisie du mot de passe.
 */
$_SESSION['confirmation_error_message'].=RESET_PASSWORD_ERROR_MESSAGE;
$_SESSION['forgotten_password']='reset_password';
$_SESSION['forgotten_password_OTP']=$_GET['OTP'];

header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
exit;

?>