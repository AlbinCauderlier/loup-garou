<?php  

/**
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance
 * 		I.1.a) Vérification de l'URL indiquée comme émettrice.
 * 		I.1.b) Si URL non autorisée, retour à "registration" avec indication que l'URL n'est pas autorisée.
 *
 * 	I.2. Vérification du contenu
 * 		I.2.a) Vérification de l'adresse e-mail
 * 		I.2.b) Si erreur, retour à "registration" avec indication que l'adresse e-mail n'est pas valide.
 *
 *
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si login déjà présent :
 *  	II.2.a)	Recherche de 'is_user_login_confirmed'
 *  	II.2.b) Si 'is_user_login_confirmed' = true, alors retour à "registration" avec message que "login" déjà confirmé + demande d'authentification.
 *  	II.2.c) Si 'is_user_login_confirmed' = false, alors suite de la procédure ci-dessous avec mise à jour de l'OTP et envoie d'un nouvel e-mail.
 *
 *
 * III. ACTIONS SUR LA BdD
 * 	III.1. Génération d'un OTP
 * 	III.1. Enregistrement du login, avec l'OTP et 'is_user_login_confirmed'==false
 *
 *
 * IV. CONFIRMATIONS
 * 	IV.1. Envoi de l'e-mail au login indiqué
 *
 *
 * V. REDIRECTION
 * 	V.1. Redirection vers "registration" avec message d'information qu'un e-mail a été envoyé.
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
require_once('config/config.php');

// Inclusion du fichier de lang de la lib authentication à l'aide des données de sessions. (fichier contenant les messages d'erreurs).
require_once(ROOT_PATH.'/lib/authentication/lang/'.$_SESSION['lang'].'.lang.php');

// Réinitialisation des messages d'erreurs
require_once(ROOT_PATH."/lib/authentication/php/authentication.php");
reset_authentication_messages();
$_SESSION['inscription_form_error_message']="";



/**
 * I. VERIFICATION DE LA PROVENANCE ET DU CONTENU DE LA REQUETE
 * 	I.1. Vérification de la provenance
 * 		I.1.a) Vérification de l'URL indiquée comme émettrice.
 * 		I.1.b) Si URL non autorisée, retour à "registration" avec indication que l'URL n'est pas autorisée.
 *
 * 	I.2. Vérification du contenu
 * 		I.2.a) Vérification de l'adresse e-mail
 * 		I.2.b) Si erreur, retour à "registration" avec indication que l'adresse e-mail n'est pas valide.
 */
// I.1. VERIFICATION DE LA PROVENANCE DU FORMULAIRE
// Liste des sites autorisées
//TODO - Mettre cette liste en variable global, déclarée dans le fichier de configuration de la lib
$sites = array(	AUTHENTICATION_FORM_PAGE."/","https://ar.".COOKIE_URL."/","https://de.".COOKIE_URL."/","https://en.".COOKIE_URL."/","https://es.".COOKIE_URL."/","https://fr.".COOKIE_URL."/","https://it.".COOKIE_URL."/","https://ru.".COOKIE_URL."/",
				AUTHENTICATION_FORM_PAGE	,"https://ar.".COOKIE_URL."" ,"https://de.".COOKIE_URL."" ,"https://en.".COOKIE_URL."" ,"https://es.".COOKIE_URL."" ,"https://fr.".COOKIE_URL."" ,"https://it.".COOKIE_URL."" ,"https://ru.".COOKIE_URL."" ,
				AUTHENTICATION_REGISTRATION_PAGE."/","https://ar.".COOKIE_URL."/signup/","https://de.".COOKIE_URL."/signup/","https://en.".COOKIE_URL."/signup/","https://es.".COOKIE_URL."/signup/","https://fr.".COOKIE_URL."/signup/","https://it.".COOKIE_URL."/signup/","https://ru.".COOKIE_URL."/signup/",
				AUTHENTICATION_REGISTRATION_PAGE	,"https://ar.".COOKIE_URL."/signup" ,"https://de.".COOKIE_URL."/signup" ,"https://en.".COOKIE_URL."/signup" ,"https://es.".COOKIE_URL."/signup" ,"https://fr.".COOKIE_URL."/signup" ,"https://it.".COOKIE_URL."/signup" ,"https://ru.".COOKIE_URL."/signup" ,
				AUTHENTICATION_PAGE."/"		,"https://ar.".COOKIE_URL."/authentication/","https://de.".COOKIE_URL."/authentication/","https://en.".COOKIE_URL."/authentication/","https://es.".COOKIE_URL."/authentication/","https://fr.".COOKIE_URL."/authentication/","https://it.".COOKIE_URL."/authentication/","https://ru.".COOKIE_URL."/authentication/",
				AUTHENTICATION_PAGE			,"https://ar.".COOKIE_URL."/authentication/","https://de.".COOKIE_URL."/authentication" ,"https://en.".COOKIE_URL."/authentication" ,"https://es.".COOKIE_URL."/authentication" ,"https://fr.".COOKIE_URL."/authentication" ,"https://it.".COOKIE_URL."/authentication" ,"https://ru.".COOKIE_URL."/authentication" ,
				"https://ar.".COOKIE_URL."/seller/","https://de.".COOKIE_URL."/seller/","https://en.".COOKIE_URL."/seller/","https://es.".COOKIE_URL."/seller/","https://fr.".COOKIE_URL."/seller/","https://it.".COOKIE_URL."/seller/","https://ru.".COOKIE_URL."/seller/",
				"https://ar.".COOKIE_URL."/seller" ,"https://de.".COOKIE_URL."/seller" ,"https://en.".COOKIE_URL."/seller" ,"https://es.".COOKIE_URL."/seller" ,"https://fr.".COOKIE_URL."/seller" ,"https://it.".COOKIE_URL."/seller" ,"https://ru.".COOKIE_URL."/seller" ,
				"https://ar.".COOKIE_URL."/payment/","https://de.".COOKIE_URL."/payment/","https://en.".COOKIE_URL."/payment/","https://es.".COOKIE_URL."/payment/","https://fr.".COOKIE_URL."/payment/","https://it.".COOKIE_URL."/payment/","https://ru.".COOKIE_URL."/payment/",
				"https://ar.".COOKIE_URL."/payment" ,"https://de.".COOKIE_URL."/payment" ,"https://en.".COOKIE_URL."/payment" ,"https://es.".COOKIE_URL."/payment" ,"https://fr.".COOKIE_URL."/payment" ,"https://it.".COOKIE_URL."/payment" ,"https://ru.".COOKIE_URL."/payment" );

if(isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'],$sites)){
	$_SESSION['inscription_form_error_message'] .= AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE."<br/>";
	$_SESSION['registration_step']='';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}

// I.2. VERIFICATION DU CONTENU DES CHAMPS DU FORMULAIRE
require_once(ROOT_PATH."/lib/form/php/check_form.php");

// Field e-mail_adress
if(!checkPostMandatory('e-mail_address')){
	$_SESSION['inscription_form_error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
	$_SESSION['registration_step']='';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}

$_POST['e-mail_address']=trim($_POST['e-mail_address']);
if(!checkLenght($_POST['e-mail_address'],6,intval(AUTHENTICATION_EMAIL_MAX_LENGHT))){
	$_SESSION['inscription_form_error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
	$_SESSION['registration_step']='';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}
if(!checkEmail($_POST['e-mail_address'])){
	$_SESSION['confirmation_error_message'].=AUTHENTICATION_ERROR_INVALID_EMAIL."<br/>";
	$_SESSION['registration_step']='';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}






/**
 * II. RECHERCHE EN BdD
 * 	II.1. Recherche du login = e-mail
 *  II.2. Si login déjà présent :
 *  	II.2.a)	Recherche de 'is_user_login_confirmed'
 *  	II.2.b) Si 'is_user_login_confirmed' = true, alors retour à "registration" avec message que "login" déjà confirmé + demande d'authentification.
 */
// II.1. VERIFICATION EN BDD QUE L'ADRESSE E-MAIL N'A PAS ETE DEJA VALIDEE.
if(is_login_confirmed($_POST['e-mail_address'])){
	$_SESSION["connexion_form_error_message"].=AUTHENTICATION_ERROR_LOGIN_ALREADY_USED;
	header("Location: ".AUTHENTICATION_LOGIN_PAGE."/".$_POST['e-mail_address']."/");
	exit;
}

// II.2. Si le login est déjà présent en BdD (mais non validé)
if(is_login_recorded($_POST['e-mail_address'])){
	// TODO - Ajouter un test sur la valeur is_user_login_confirmed
	$_SESSION['inscription_form_error_message'].=AUTHENTICATION_ERROR_LOGIN_ALREADY_USED."<br/>";
	$_SESSION['registration_step']='';
	header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
	exit;
}





/**
 * III. ACTIONS SUR LA BdD
 * 	III.1. Génération d'un OTP
 * 	III.2. Enregistrement du login, avec l'OTP et 'is_user_login_confirmed'==false
 */
// III.1. GENERATION D'UN OTP POUR ENVOI DANS L'E-MAIL ET ENREGISTREMENT EN BDD
$user_login_OTP = getRandomKey(RANDOMKEY_LENGHT,RANDOMKEY_CARACTERS);

// III.2. INSCRIPTION DE L'UTILISATEUR DANS LA BASE AUTHENTICATION
// Inscription de l'adresse e-mail en BdD, à l'aide de la fonction de rédaction de la requête
mysql_myquery(SQL_AUTHENTICATION_BASE, authentication_inscription_get_sql_request($_POST['e-mail_address'], $user_login_OTP) );

// Mise en session de l'OTP pour intégration dans le lien de l'e-mail
$_SESSION['login_OTP']=$user_login_OTP;
$_SESSION['login']=$_POST['e-mail_address'];

// Enregistrement de l'action dans l'historique de l'user
add_authentication_action($_SESSION["login"],"EMAIL_REGISTRATION");



/**
 * IV. CONFIRMATIONS
 * 	IV.1. Envoi de l'e-mail de demande de cofnirmation à l'adresse e-mail indiqués
 */
try{
	require_once(ROOT_PATH."/lib/mail/config/config.php");
	include_once(ROOT_PATH.'/lib/mail/class.phpmailer.php');
	include_once(ROOT_PATH.'/lib/mail/class.smtp.php');
	require_once(ROOT_PATH."/lib/mail/PHPMailerAutoload.php");
	
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
	$mail->SetFrom(SEND_INFO_EMAIL_ADDRESS,SEND_INFO_EMAIL_ADDRESS_NAME);
	$mail->addReplyTo(SEND_INFO_EMAIL_ADDRESS,SEND_INFO_EMAIL_ADDRESS_NAME);
	$mail->Subject="=?utf-8?Q?".EMAIL_VERIFICATION_SUBJECT."?=";
	
	$mail->MsgHTML(get_include_contents(ROOT_PATH."/lib/authentication/lang/e-mail_verification_demand.php"));
	
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

// TODO - Traiter les cas d'e-mails non reçus => Rejeu ou Suppression de l'inscription ?



// IV - bis. - Si inscription avec panier non vide, alors mise en BdD du panier et redirection vers /delivery
if (isset ( $_SESSION ['cart'] ) && !empty ( $_SESSION ['cart'] )) {
	// Enregistrer la commande en BdD
	require_once(ROOT_PATH."/controller/openbazaar/config/config.php");
	include_once(ROOT_PATH."/controller/openbazaar/save_cart.php");
	save_cart ();
}


/**
 * V. REDIRECTION
 * 	V.1. Redirection vers "registration" avec un message d'information qu'un e-mail a été envoyé.
 */
$_SESSION['inscription_form_error_message'].=AUTHENTICATION_EMAIL_REGISTRATION_OK.$_SESSION['login']."<br/>";
$_SESSION['registration_step']='confirm_email';

header('Location: '.AUTHENTICATION_REGISTRATION_PAGE);
exit;


?>