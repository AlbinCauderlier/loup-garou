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
	<title>Villages - Loups Garous</title>
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
                    <i data-feather="home"></i> Liste des villages
                </h1>
            </div>
        </section>
		<section>
			<div class="container pt-4">
                <table class="table table-hover table-striped border-bottom mb-4 dynamic-datatable">
                    <thead>
                        <tr class="text-center">
                            <th>Village Name</th>
                            <th>Nombre d'habitants</th>
                            <th>Visio des Habitants</th>
                            <th>Visio des Loups Garous</th>
                            <th>Etat</th>
                        </tr>
                    </thead>
                    <tbody class="table-borderless text-nowrap">
                    <?php
                        $villages = json_decode(callAPI('GET',API_URL.'/api/villages/'), true);

                        if( !empty($villages) ){
                            foreach( $villages as $village ){

                                echo('<tr>');
                                    echo('<td><a href="/village/'.$village['village-id'].'/">'.$village['village-name'].'</a></td>');
                                    echo('<td></td>');
                                    echo('<td><a href="'.$village['village-jitsi-link'].'" target="_blank">'.$village['village-jitsi-link'].'</a></td>');
                                    echo('<td><a href="'.$village['village-jitsi-link'].'loupsgarous" target="_blank">'.$village['village-jitsi-link'].'loupsgarous</a></td>');
                                    echo('<td>'.$village['village-state'].'</td>');
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