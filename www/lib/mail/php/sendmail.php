<?php
	require_once("../../../configuration.php");

	// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
	ini_set("session.cookie_domain",COOKIE_URL);
	session_set_cookie_params(9000,"/",COOKIE_URL);
	session_start();
	
	// Rapporter toutes les erreurs PHP
	//error_reporting(E_ALL);
	
	// Détection de la langue à utiliser + Loading language file 
	//require_once(ROOT_PATH."/lib/lang/php/lang.php");
	//$_SESSION["lang"]=detectLang();
	//require_once(ROOT_PATH."/lang/".$_SESSION["lang"].".lang.php");

	// TODO - Vérification que les champs obligatoires sont présents, sinon, retour au formulaire avec un message d'erreur.
	
	include_once('../config/config.php');

	require_once('./../../../lib/mail/config/config.php');
	include_once('./../../../lib/mail/class.phpmailer.php');
	include_once('./../../../lib/mail/class.smtp.php');
	require_once('./../../../lib/mail/PHPMailerAutoload.php');
	
	$mailMubiz=new PHPMailer();
	$mailMubiz->isHTML(true);                                  // Set email format to HTML
	$mailMubiz->CharSet="utf-8";

    $mailMubiz->isSMTP();  // Set mailer to use SMTP
    $mailMubiz->Host = MAILER_SMTP_HOST;  // Specify mailgun SMTP servers
    $mailMubiz->SMTPAuth = true; // Enable SMTP authentication
    $mailMubiz->Username = MAILER_SMTP_USER; // SMTP username from https://mailgun.com/cp/domains
    $mailMubiz->Password = MAILER_SMTP_PASS; // SMTP password from https://mailgun.com/cp/domains
	$mailMubiz->Port= MAILER_SMTP_PORT;

	// ENVOI D'UN MAIL POUR MUBIZ
    $mailMubiz->From = $_POST['adresse_e-mail']; // The FROM field, the address sending the email 
    $mailMubiz->FromName = $_POST['firstname'].' '.$_POST['name']; // The NAME field which will be displayed on arrival by the email client
    $mailMubiz->addAddress(EMAIL_CONTACT_ADDRESS);     // Recipient's email address and optionally a name to identify him

    $mailMubiz->Subject = EMAIL_CONTACT_TITLE.$_POST['title'];
    $mailMubiz->Body    = $_POST["message"];

    if(!$mailMubiz->send())
    {  
        error_reporting( "Confirmation Message hasn't been sent.");
        error_reporting('Mailer Error: '.$mailMubiz->ErrorInfo."\n");
    }

	// ENVOI D'UNE CONFIRMATION AU CLIENT
	$mailClient=new PHPMailer();
	$mailClient->isHTML(true);                                  // Set email format to HTML
	$mailClient->CharSet="utf-8";

    $mailClient->isSMTP();  // Set mailer to use SMTP
    $mailClient->Host = MAILER_SMTP_HOST;  // Specify mailgun SMTP servers
    $mailClient->SMTPAuth = true; // Enable SMTP authentication
    $mailClient->Username = MAILER_SMTP_USER; // SMTP username from https://mailgun.com/cp/domains
    $mailClient->Password = MAILER_SMTP_PASS; // SMTP password from https://mailgun.com/cp/domains
	$mailClient->Port= MAILER_SMTP_PORT;

    $mailClient->From = EMAIL_CONTACT_ADDRESS; // The FROM field, the address sending the email 
    $mailClient->FromName = EMAIL_CONTACT_TITLE.$_POST['title']; // The NAME field which will be displayed on arrival by the email client
    $mailClient->addAddress($_POST['adresse_e-mail']);     // Recipient's email address and optionally a name to identify him

    $mailClient->Subject = 'Mubiz - Contact Confirmation';
    $mailClient->Body    = $_POST["message"];    

    if(!$mailClient->send())
    {  
    	error_reporting( "Confirmation Message hasn't been sent.");
    	error_reporting('Mailer Error: '.$mailClient->ErrorInfo."\n");
    }

	//require_once(ROOT_PATH.'/vendor/autoload.php');
	//use Mailgun\Mailgun;

	// $mg = new Mailgun(MAILGUN_API_KEY);
	// $domain = MAILGUN_DOMAIN;

	// MAILGUN TEST POSTING
	// $mg = new Mailgun(MAILGUN_API_KEY, null, 'bin.mailgun.net');
	// $mg->setApiVersion('634f6fde');
	// $mg->setSslEnabled(false);
	// $domain = 'mubiz.com';

	// ENVOI D'UN MAIL POUR MUBIZ
	// $mg->sendMessage($domain, array('from'    => $_POST['firstname'].' '.$_POST['name'].' <'.$_POST['adresse_e-mail'].'>', 
	//                                 'to'      => EMAIL_CONTACT_ADDRESS, 
	//                                 'subject' => EMAIL_CONTACT_TITLE.$_POST['title'], 
	//                                 'text'    => $_POST['message']));

	// // // ENVOI D'UNE CONFIRMATION AU CLIENT
	// $mg->sendMessage($domain, array('from'    => 'Mubiz <'.NOREPLY_EMAIL_ADDRESS.'>', 
	//                                 'to'      => $_POST['adresse_e-mail'], 
	//                                 'subject' => 'Mubiz - Contact Confirmation', 
	//                                 'text'    => $_POST['message']));

	// TODO - Adapter le message de confirmation de traitement + la langue utilisée en réponse.
	// TODO - Mettre tous les textes dans des fichiers lang !
	// TODO - Mettre le prénom et le nom en session pour pouvoir les intégrer dans le message.
	// TODO - Faire un message en HTML => Bien plus sympa en terme de design. Utiliser les mails de HopWork pour cela.
	//		Mettre un header avec le logo - Mettre un footer avec les mentions légales et les liens vers les réseaux sociaux
	
	// Redirection vers la page du formulaire de prise de contact	
	header('Location: http://'.$_SESSION['lang'].'.'.EMAIL_CONTACT_URL_REDIRECTION);
	exit;
?>