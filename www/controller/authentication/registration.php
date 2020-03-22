<?php
	require_once("../../configuration.php");
	
	// Initialisation de la session avec le même domaine pour l'ensemble des sous-domaines.
	ini_set('session.cookie_domain',COOKIE_URL);
	session_set_cookie_params(9000,"/",COOKIE_URL);
	session_start();

	if($_SERVER['REQUEST_METHOD']!=='POST'){
		exit('Invalid request');
	}

	$sites = array(	ROOT_URL."/signup/", ROOT_URL."/", ROOT_URL  );

	if(isset($_SERVER['HTTP_REFERER']) && !in_array($_SERVER['HTTP_REFERER'],$sites)){
		exit('Request not coming from the website');
	}

	if(!defined("PHP_EOL")){
		define("PHP_EOL", "\r\n");	
	}

	// Sanitize request
	$firstname = (isset($_POST['firstname']) ? strip_tags($_POST['firstname']) : '');
	$lastname = (isset($_POST['lastname']) ? strip_tags($_POST['lastname']) : '');
	$email = (isset($_POST['email']) ? strip_tags($_POST['email']) : '');
	$company_name = (isset($_POST['company_name']) ? strip_tags($_POST['company_name']) : '');
	$company_country = (isset($_POST['company_country']) ? strip_tags($_POST['company_country']) : '');
	$password1 = (isset($_POST['password']) ? strip_tags($_POST['password']) : '');
	$password2 = (isset($_POST['password_confirmation']) ? strip_tags($_POST['password_confirmation']) : '');


	// Validate inputs
	if( !isset($_POST['firstname']) || empty($_POST['firstname']) ){
		exit('Fistname required');
	}
	if(empty($lastname)){
		exit('Name required');
	}
	if(empty($email)){
		exit('Email required');
	}
	if(empty($company_name)){
		exit('Company Name required');
	}
	if(empty($company_country)){
		exit('Company Country required');
	}
	if(empty($password1)){
		exit('Password1 required');
	}
	if(empty($password2)){
		exit('Password2 required');
	}


	// Contrôle des contenus
	if($password1!==$password2) {
		exit('Same passwords required');
	}
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		exit('Email is not a validated email address');
	}


	// Configuration option.
	// You can change this if you feel that you need to.
	// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.	
	$subject = "Loups Garous - Registration confirmation of ".$firstname." ".$lastname;

	$msg  = 'Welcome '.$firstname.' '.$lastname.', Your account '.$email.' has been registered for the company '.$company_name.' - '.$company_country.PHP_EOL.PHP_EOL;
	$msg .= 'You can now access the service on :<a href="'.ROOT_URL.'/user/" title="Loups Garous">Argos Logisitic</a>';

	$_SESSION["email"]=$email;

	try{
		require_once("./../../vendors/mail/config/config.php");
		include_once('./../../vendors/mail/class.phpmailer.php');
		include_once('./../../vendors/mail/class.smtp.php');
		require_once("./../../vendors/mail/PHPMailerAutoload.php");
		
		$mail=new PHPMailer();
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->CharSet="utf-8";

	    $mail->isSMTP();  					// Set mailer to use SMTP
	    $mail->Host = MAILER_SMTP_HOST;  	// Specify mailgun SMTP servers
	    $mail->SMTPAuth = true; 			// Enable SMTP authentication
	    $mail->Username = MAILER_SMTP_USER; // SMTP username from https://mailgun.com/cp/domains
	    $mail->Password = MAILER_SMTP_PASS; // SMTP password from https://mailgun.com/cp/domains
		$mail->Port= MAILER_SMTP_PORT;
		
		$mail->addAddress($email);
		$mail->SetFrom(MAILER_FROM,MAILER_FROM_NAME);
		$mail->addReplyTo(MAILER_FROM,MAILER_FROM_NAME);
		$mail->Subject="=?utf-8?Q?".$subject."?=";
		
		//$mail->MsgHTML(get_include_contents(ROOT_PATH."/lib/authentication/lang/e-mail_verification_demand.php"));
		$mail->MsgHTML( $msg );
		
		// Mettre un message alternatif
		//$mail->AltBody="This is the plain text version of the email content";
		
		$mail->Send();

	}
	catch (phpmailerException $e){
		$_SESSION['inscription_form_error_message']='Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
		header("location: /signup/");
		exit();
	}
	catch (Exception $e){
		$_SESSION['inscription_form_error_message']='Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
		header("location: /signup/");
		exit();
	}

	header("location: /signup-confirmation/");
	exit();
?>