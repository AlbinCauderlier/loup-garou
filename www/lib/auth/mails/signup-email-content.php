<?php
	require_once("./../../configuration.php");

	// Inclusion du fichier de lang
	require_once("./../../lib/auth/lang/".$_SESSION['lang'].".lang.php");
	require_once("./../../lib/mail/lang/".$_SESSION['lang'].".lang.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?=$_SESSION['lang']?>">
<head>
	<title>Email address confirmation</title>
	<meta name="rating"         content="safe for kids"/>
	<meta name="language"       content="<?=$_SESSION['lang']?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?=$_SESSION['lang']?>" />
</head>
<body style="font-family: Nunito, Arial, sans-serif; background-color: #f8f8f8;">
	<div id="header" style="margin: 20px auto 0 auto;
							max-width: 600px;
							color: #ffffff;
							background: #55aaee;
							text-align: center;
							padding: 20px 20px;">
		<img src="https://argoslogistic.com/images/logo-argos-logistic-white.png" alt="Argos Logitic" style="margin:10px auto; max-width:400px; padding: 20px; text-align: center;" /> <h3 style="color:white;"></h3>
	</div>
	<div id="main" style="background-color:white; margin:0 auto; max-width:600px; padding:20px;">
		<p class="text-muted"><?=EMAIL_HELLO?> <?=$_SESSION['alias']?>,</p>
		<p class="text-muted"><?=EMAIL_VERIFICATION_CONSIGNE_1?></p>
		<a href="<?=ROOT_URL?>/controller/authentication/email_confirmation.php?login=<?=$_SESSION['login']?>&OTP=<?=$_SESSION['email_address_OTP']?>" title="<?=EMAIL_VERIFICATION_BUTTON?>" style="color:white;">
			<div id="address_confirmation_button" style="width:300px; background-color:#55aaee; border-radius:5px;text-align:center; margin:0 auto; padding:10px;">
				<?=EMAIL_VERIFICATION_BUTTON?>
			</div>
		</a>
		<p class="text-muted"><?=EMAIL_VERIFICATION_CONSIGNE_2?></p>
		<p class="text-muted">
			<?=EMAIL_CONCLUSION?><br/>
			<?=EMAIL_SIGNATURE?>
		</p>
	</div>
	<div id="footer" style="margin:0 auto; max-width:500px; padding:10px; text-align:center;">
		<p class="text-muted"><?=EMAIL_FOOTER?></p>
	</div>
</body>
</html>