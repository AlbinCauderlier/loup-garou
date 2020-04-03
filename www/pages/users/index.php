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
	<title>Joueurs</title>
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
                    <i data-feather="user"></i> Les Joueurs
                </h1>
            </div>
        </section>
		<section>
			<div class="container pt-4">
                <table class="table table-hover table-striped border-bottom mb-4 dynamic-datatable">
                    <thead>
                        <tr class="text-center">
                            <th>Joueur</th>
                            <th>ID</th>
                            <th>Alias</th>
                            <th>Inscrit depuis...</th>
                            <th>Actuellement connecté ?</th>
                        </tr>
                    </thead>
                    <tbody class="table-borderless text-nowrap">
                    <?php
                        $users = json_decode(callAPI('GET',API_URL.'/api/users/'), true);

                        if( !empty($users) ){
                            foreach( $users as $user ){

                                echo('<tr>');
                                    echo('<td><a href="/user/'.$user['user-id'].'/">'.$user['user-firstname'].' '.$user['user-lastname'].'</a></td>');
                                    echo('<td>'.$user['user-id'].'</td>');
                                    echo('<td>'.$user['user-alias'].'</td>');
                                    echo('<td>'.$user['village-state'].'</td>');
                                    echo('<td></td>');
                                echo('</tr>');
                            }
                        }
                    ?>
                    </tbody>
                </table>
			</div>
		</section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>