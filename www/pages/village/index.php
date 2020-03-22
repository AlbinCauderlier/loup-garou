<?php   
    //Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
    if(!isset($_SESSION['isConnected']) || empty($_SESSION['isConnected']) || !isset($_SESSION['login']) || empty($_SESSION['login'])){
        // Inclusion des paramètres d'authentication
        require_once("controller/authentication/config.php");
        
        header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
        exit;
    }

    $village = json_decode(callAPI('GET',API_URL.'/api/villages/'.$_GET['p2'].'/'), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Village - Loups Garous</title>
    <?php
        include_once("common/head.php");
    ?>
</head>
<body id="top" data-spy="scroll" data-target="#toc" data-offset="130">
    <?php
        include_once("common/header.php");
    ?>
    <main class="mt-0">
        <section class="bg-gradient my-4 pt-5">
            <div class="container py-3">
                <div class="row">
                    <div class="col">
                        <h1 class="text-white">
                            <i data-feather="home"></i> <?= $village['village-name'] ?>
                        </h1>
                    </div>
                    <div class="col-auto">
                        <a href="/village/new-habitant/" class="btn btn-outline-light rounded-pill px-3 py-2">
                            <i data-feather="user-plus" class="mr-1"></i> Ajouter un habitant
                        </a>
                    </div>
                </div>
            </div>
        </section>
		<section>
			<div class="container pt-4">
                Bienvenue !
			</div>
		</section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>