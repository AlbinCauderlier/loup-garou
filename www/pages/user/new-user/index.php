<?php   
    //Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
    if(!isset($_SESSION['isConnected']) || empty($_SESSION['isConnected']) || !isset($_SESSION['login']) || empty($_SESSION['login'])){
        // Inclusion des paramètres d'authentication
        require_once("controller/authentication/config.php");
        
        header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Sign up - Loups Garous</title>
	<?php 
		include("common/head.php");
	?>
</head>
<body id="top">
	<?php
        include_once("common/header.php");
    ?>
	<section class="py-5">
		<div class="container py-5">
			<div class="row py-1">
				<div class="col-md-6 mx-auto">
  				    <form id="signup" action="<?=ROOT_URL?>/controller/authentication/registration.php" method="post">
  				    	<h1>New User</h1>
						<?php 
							display_user_messages();
							
							include_once("common/forms/form-sign-up.php");
						?>
				    </form>
				</div>
			</div>
		</div>
	</section>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>