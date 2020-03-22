<?php  
	
/**
 *	ALGORITHME
 *
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance
 *
 *
 * II. ACTIONS SUR LA BdD
 * 	II.1. Désactivation de l'adresse e-mail / effacement du password
 *
 *
 * III. CONFIRMATIONS
 *	III.1. Envoi d'un e-mail de confirmation.
 *
 *
 * IV. REDIRECTION
 * 	IV.1. Redirection vers "home".
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
	$_SESSION['lang'] = detectLang();
}

// Inclusion des paramètres d'authentication
require_once 'config/config.php';

// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
require_once '../../lib/authentication/lang/'.$_SESSION['lang'].'.lang.php';




// 1. VERIFICATION DE LA PROVENANCE DU FORMULAIRE

// Liste des sites autorisées
// TODO - Mettre cette liste en variable global, déclarée dans le fichier de configuration de la lib
$sites = array(AUTHENTICATION_SIGN_OUT_PAGE);

$_SESSION['unsubscribe_error_message']="";
if(isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'],$sites)){
	$_SESSION['unsubscribe_error_message'] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE;
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
	
	
// DESINSCRIPTION DE L'UTILISATEUR

//DESINSCRIPTION DANS LA BASE D'AUTHENTIFICATION
require_once('../../model/authentication/sql_request.php');
require_once('../../model/config.php');
require_once('../../model/php/sql.php');

// TODO - Mettre cette étape dans une fonction de la lib authentication !!
// Rédaction de la requête SQL à l'aide des données des champs
$req_authentication_unsubscribe=authentication_remove_user_get_sql_request($_SESSION['login']);

// Execution de la requête
mysql_myquery(SQL_AUTHENTICATION_BASE,$req_authentication_unsubscribe );
	
	
//DESINSCRIPTION DANS LA BASE D'UTILISATEURS
// Rédaction de la requête SQL à l'aide des données des champs
// TODO - Changer la requête !!!
// TODO - Mettre cette étape dans une fonction spécifique à Mubiz !
// Inclusion de la lib SQL
require_once('model/user_administration/sql_request.php');
$req_user_unsubscription=mysql_user_unsubscription_request($_SESSION['login']);

// Execution de la requête
mysql_myquery(SQL_USERS_BASE,$req_user_unsubscription);


// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"UNSUBSCRIPTION");


/**
 * III. CONFIRMATIONS
 * 	III.1. Envoi de l'e-mail au login indiqué
 */
// TODO - Retirer cette partie du code de la lib Authentication => Déplacer la partie métier dans le site.
// TODO - Mettre ces données dans des fichiers de configuration ou fichier lang.php
define("EMAIL_UNSUBSCRIPTION_CONFIRMATION_ADRESS","Mubiz <noreply@mubiz.com>");
define("EMAIL_UNSUBSCRIPTION_CONFIRMATION_TITLE","Mubiz - Confirmation de désinscription");

require_once('../lib/authentication/authentication.php');
require_once("lib/mail/config/config.php");
require_once("lib/mail/PHPMailerAutoload.php");

// TODO - Ajouter un lien avec : Je ne suis pas à l'origine de cette action.

$headers="From: ".EMAIL_UNSUBSCRIPTION_CONFIRMATION_ADRESS."\r\n";



$mail = new PHPMailer;
$mail->addAddress($_SESSION['login']);
$mail->SetFrom(EMAIL_UNSUBSCRIPTION_CONFIRMATION_ADRESS,SEND_INFO_EMAIL_ADDRESS_NAME);
$mail->addReplyTo(SEND_INFO_EMAIL_ADDRESS,SEND_INFO_EMAIL_ADDRESS_NAME);

$mail->isSMTP();  // Set mailer to use SMTP
$mail->Host = MAILER_SMTP_HOST;  // Specify mailgun SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = MAILER_SMTP_USER; // SMTP username from https://mailgun.com/cp/domains
$mail->Password = MAILER_SMTP_PASS; // SMTP password from https://mailgun.com/cp/domains
$mail->Port= MAILER_SMTP_PORT;

$mail->isHTML(true);                                  // Set email format to HTML
$mail->CharSet="UTF-8";
$mail->Subject="=?UTF-8?Q?".EMAIL_UNSUBSCRIPTION_CONFIRMATION_TITLE."?=";

$mail->MsgHTML(get_include_contents("lib/authentication/lang/unsubscription_confirmation.php"));

// Mettre un message alternatif
//$mail->AltBody="This is the plain text version of the email content";

if(!$mail->send()){
	$_SESSION['inscription_form_error_message']='Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
}


// TODO - Traiter les cas d'e-mails non reçus => Rejeu ou Suppression de l'inscription ?




	
// Redirige vers la page de déconnexion
// TODO - Ajouter la page de déconnexion dans les paramètres.		
header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
exit;
	
?>