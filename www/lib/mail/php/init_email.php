<?php

/**
 * 
 */
function get_init_email(){
	require_once("lib/mail/config/config.php");
	include_once('class.phpmailer.php');
	require_once("lib/mail/PHPMailerAutoload.php");

	include_once("lib/mail/lang/".$_SESSION['lang'].".lang.php");
	
	$mail=new PHPMailer();
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->CharSet="utf-8";
	
	return $mail;
}

?>