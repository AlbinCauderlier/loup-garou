<?php 
// Détection de la langue à utiliser (utile si session a expiré suite à une page non utilisée depuis plusieurs minutes)
if(!(isset($_SESSION['lang']) && !(empty($_SESSION['lang'])))){
	// Utilise la fonction de détection de la langue.
	require_once(ROOT_PATH."/lib/lang/php/lang.php");
	$_SESSION['lang'] = detectLang();
}

// Inclusion du fichier de lang
require_once($_SESSION['lang'].'.lang.php');
require_once(ROOT_PATH."/lib/mail/lang/".$_SESSION['lang'].".lang.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?=$_SESSION['lang']?>">
<head>
	<title><?=FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_SUBJECT?></title>
	<meta name="language"       content="<?=$_SESSION['lang']?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?=$_SESSION['lang']?>" />
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f8f8;">
	<div id="header" style="margin:0 auto; max-width:500px; color:#ffffff; background:#009999; background: linear-gradient(to top right, #009999, #33cc99); text-align:center; padding:20px; font-size:25px; font-weight:bold;">
		<img src="<?=CDN_URL?>/images/mubiz/mubiz-logo-white-small.png" alt="Argos Logistic"/>
	</div>
	<div id="main" style="background-color:white; margin:0 auto; max-width:500px; padding:20px;">
		<p class="text-muted"><?=EMAIL_HELLO?>,</p>
		<p class="text-muted"><?=FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_1?></p>
		<a href="<?=CDN_URL?>/controller/authentication/forgotten_password_email_verification.php?login=<?=$_SESSION['login']?>&OTP=<?=$_SESSION['user_change_password_OTP']?>" title="<?=EMAIL_VERIFICATION_BUTTON?>" style="color:white;">
			<div id="address_confirmation_button" style="width:300px; background-color:#009999; text-align:center; margin:0 auto; padding:10px;">
				<?=FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_BUTTON?>
			</div>
		</a>
		<p class="text-muted"><?=FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_2?></p>
		<p class="text-muted">
			<?=EMAIL_CONCLUSION?><br/>
			<?=EMAIL_SIGNATURE?>
		</p>
	</div>
</body>
</html>