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
    <title>New Game - Loups Garous</title>
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
                <h1 class="text-white">
                    <i data-feather="home"></i> New village
                </h1>
            </div>
        </section>
        <section>
            <div class="container pt-4">
                <div class="row mb-5">
                    <div class="col-md-4 mx-auto">
                        <?php
                            include_once("common/forms/form-new-village.php");
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>