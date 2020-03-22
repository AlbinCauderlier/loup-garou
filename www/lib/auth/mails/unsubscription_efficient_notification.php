<?php 
// Détection de la langue à utiliser (utile si session a expiré suite à une page non utilisée depuis plusieurs minutes)
if(!( isset($_SESSION['lang']) && !(empty($_SESSION['lang'])) )) {
	// Utilise la fonction de détection de la langue.
	require_once("../../lib/lang/php/lang.php");
	$_SESSION['lang'] = detectLang();
}

// Inclusion du fichier de lang
require_once $_SESSION['lang'].'.lang.php';
require_once(ROOT_PATH."/lib/mail/lang/".$_SESSION['lang'].".lang.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?=$_SESSION['lang']?>">
<head>
	<title><?=UNSUBSCRIPTION_CONFIRMATION_TITLE?></title>
	<meta name="rating"         content="safe for kids"/>
	<meta name="language"       content="<?=$_SESSION['lang']?>"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Language" content="<?=$_SESSION['lang']?>"/>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f8f8;">
	<div id="header" style="margin:0 auto; max-width:700px; color:#ffffff; background:#009999; background: linear-gradient(to top right, #009999, #33cc99); text-align:center; padding:10px; font-size:25px; font-weight:bold;">
		<img src="<?=CDN_URL?>/images/mubiz/mubiz-logo-white-small.png" alt="Argos Logistic"/>
	</div>
	<div id="main" style="background-color:white; margin:0 auto; max-width:700px; padding:10px 10px;">
		<p class="text-muted"><?=UNSUBSCRPITION_CONFIRMATION_HELLO?>,</p>
		<p class="text-muted"><?=UNSUBSCRPITION_CONFIRMATION_DETAILS?></p>
		<p class="text-muted"><?=UNSUBSCRPITION_CONFIRMATION_CONCLUSION?></p>
		<p class="text-muted"><?=UNSUBSCRPITION_CONFIRMATION_SIGNATURE?></p>
	</div>
	<div id="footer" style="margin:0 auto; max-width:800px; padding:10px 10px; text-align:center;">
		<p class="text-muted"><?=EMAIL_FOOTER?></p>
	</div>
</body>
</html>
