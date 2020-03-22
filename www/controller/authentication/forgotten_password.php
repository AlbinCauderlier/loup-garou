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
require_once('lib/authentication/lang/'.$_SESSION['lang'].'.lang.php');

// Inclusion des paramètres d'authentication
require_once("config/config.php");

// Réinitialisation des messages d'erreurs
require_once("/lib/authentication/php/authentication.php");
reset_authentication_messages();
$_SESSION['error_message']="";
$_SESSION['confirmation_message']="";



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

if(isset($_SERVER["HTTP_REFERER"])){
	if(!in_array($_SERVER['HTTP_REFERER'],$sites)){
		$_SESSION['error_message'].=AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE;
		header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
		exit;
	}
}


// I.2. Vérification du contenu des champs
require_once("lib/form/php/check_form.php");

// Field 'adresse_e-mail'
if(!checkPostMandatory('adresse_e-mail')){
	$_SESSION['error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL;
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}



/**
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si absent, alors redirection vers "registration" avec message de demande d'enregistrement de l'adresse e-mail.
 *  II.3. Si un password existe déjà, retour à registration avec indication qu'il s'agit d'un changement de mot de passe.
 */

// II.1. VERIFICATION EN BdD QUE LE LOGIN EST ENREGISTRE AVEC UNE ADRESSE E-MAIL VALIDEE
if(!is_login_recorded($_POST['adresse_e-mail'])){
	$_SESSION['error_message'].=AUTHENTICATION_ERROR_UNKNOWN_LOGIN;
	$_SESSION['registration_step']='set_password';
	header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
	exit;
}




/**
 * III. ACTIONS SUR LA BdD
 * 	III.1. Enregistrement du mot de passe en BdD + indication des autres données
 *
 */
// III.1. GENERATION D'UN OTP POUR ENVOI DANS L'E-MAIL ET ENREGISTREMENT EN BDD
$user_change_password_OTP=getRandomKey(RANDOMKEY_LENGHT,RANDOMKEY_CARACTERS);

// Mise en session de l'OTP pour intégration dans le lien de l'e-mail
$_SESSION['user_change_password_OTP']=$user_change_password_OTP;
$_SESSION['login']=$_POST['adresse_e-mail'];

// III.2. ENREGISTREMENT DU JETON EN BDD
mysql_myquery(SQL_AUTHENTICATION_BASE,authentication_record_forgotten_password_otp_sql_request($_POST['adresse_e-mail'],$user_change_password_OTP));


// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"REQUEST_FORGOTTEN_PASSWORD");







/** 
 * IV. CONFIRMATIONS
 *	IV.1. Envoi d'un e-mail contenant un lien avec l'OTP
 *
 */
//TODO - Envoi d'un e-mail de confirmation du mot de passe avec lien pour "si vous n'êtes pas à l'origine de ce changement, merci de client sur le lien ci-dessous pour blocage de votre compte".
try{
	require_once("lib/mail/config/config.php");
	require_once("lib/mail/PHPMailerAutoload.php");

	$mail=new PHPMailer();
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->CharSet="utf-8";

    $mail->isSMTP();  // Set mailer to use SMTP
    $mail->Host = MAILER_SMTP_HOST;  // Specify mailgun SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = MAILER_SMTP_USER; // SMTP username from https://mailgun.com/cp/domains
    $mail->Password = MAILER_SMTP_PASS; // SMTP password from https://mailgun.com/cp/domains
	$mail->Port= MAILER_SMTP_PORT;

	$mail->addAddress($_SESSION["login"]);
	$mail->SetFrom(FORGOTTEN_PASSWORD_EMAIL_ADDRESS,FORGOTTEN_PASSWORD_EMAIL_ADDRESS_NAME);
	$mail->Subject="=?utf-8?Q?".FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_SUBJECT."?=";

	$mail->MsgHTML(get_include_contents("lib/authentication/lang/forgotten_password_e-mail_verification.php"));

	// Mettre un message alternatif
	//$mail->AltBody="This is the plain text version of the email content";

	$mail->Send();

} catch (phpmailerException $e){
	$_SESSION['inscription_form_error_message']='Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
	error_log("EMAIL ERROR - EMAIL_REGISTRATION - Echec lors de l'envoi à ".$_SESSION["login"]);
	error_log("EMAIL ERROR : ".$e->errorMessage());
} catch (Exception $e){
	$_SESSION['inscription_form_error_message']='Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
	error_log("EMAIL ERROR - EMAIL_REGISTRATION - Echec lors de l'envoi à ".$_SESSION["login"]);
	error_log("EMAIL ERROR : ".$e->getMessage());
}
error_log("EMAIL NOTIFICATION - EMAIL_REGISTRATION - Envoi a ".$_SESSION["login"]);





/**
 * VI. REDIRECTION
 */
$_SESSION['confirmation_message'].=AUTHENTICATION_PASSWORD_RESET_OK;
$_SESSION['password_reset_confirmation_message'].=AUTHENTICATION_EMAIL_REGISTRATION_OK.$_POST['adresse_e-mail'];
$_SESSION['forgotten_password']='email_sent';

header('Location: '.AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE);
exit;

?>