<?php   
    //Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
    if(isset($_SESSION['isConnected']) && !empty($_SESSION['isConnected']) && isset($_SESSION['login']) && !empty($_SESSION['login'])){
        // Inclusion des paramètres d'authentication
        require_once("controller/authentication/config.php");
        
        header('Location: '.AUTHENTICATION_FIRST_PAGE);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login - Loups Garous</title>
	<?php
		include_once("common/head.php");
	?>
</head>
<body id="top">
	<section class="py-5">
		<div class="container py-5 text-center">
			<h2 class="pb-4">
				<img src="<?=ROOT_URL?>/images/loups-garous-logo.png" style="max-width: 400px;" alt="Loups Garous"/>
			</h2>
			<div class="row mb-5">
				<div class="col-md-4 mx-auto">
		            <div class="card shadow p-5">
		                <?php
		                	display_user_messages();

		                	include_once("lib/auth/forms/form-login.php");
		                ?>
		            </div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="/vendors/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="/vendors/bootstrap-4.3.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="/js/feather.min.js"></script>

	<script type="text/javascript" src="/js/home.js"></script>
</body>
</html>
