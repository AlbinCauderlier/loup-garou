<?php   
    //Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
    if(!isset($_SESSION['isConnected']) || empty($_SESSION['isConnected']) || !isset($_SESSION['login']) || empty($_SESSION['login'])){
        // Inclusion des paramètres d'authentication
        require_once("controller/authentication/config.php");
        
        header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
        exit;
    }

    $village = json_decode(callAPI('GET',API_URL.'/api/villages/'.$_GET['p2'].'/'), true);

    $all_habitants = json_decode(callAPI('GET',API_URL.'/api/habitants/'), true);

    $habitants = [];
    $storytellers = [];

    $is_a_storyteller = false;
    $is_alive = true;

    foreach( $all_habitants as $habitant ){
        if( $habitant['habitant-village'] === $_GET['p2']){
            if( $habitant['habitant-card'] === "storyteller"){
                $storytellers[] = $habitant;

                if( $user_data['user-id'] == $habitant['habitant-user']){
                    $is_a_storyteller = true;
                }
            }
            else{
                $habitants[] = $habitant;

                if( $user_data['user-id'] == $habitant['habitant-user'] && !empty( $habitant['habitant-card-displayed'] ) ){
                    $is_alive = false;
                }
            }
        }
    }
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
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-outline-light rounded-pill px-3 py-2 mr-2">
                            <i data-feather="refresh-cw" class="mr-1"></i> Rafraichir la page
                        </a>
                        <a href="/village/new-habitant/<?=$_GET['p2']?>/" class="btn btn-outline-light rounded-pill px-3 py-2">
                            <i data-feather="user-plus" class="mr-1"></i> Ajouter un habitant
                        </a>
                    </div>
                </div>
            </div>
        </section>
		<section>
			<div class="container pt-4">
                <div class="row">
                    <div class="col-4">
                        <h2>Conteur</h2>
                        <?php
                            foreach( $storytellers as $storyteller ){
                                echo('<label>'.$storyteller['habitant-user'].'</label><br/>');
                            }
                        ?>
                    </div>
                    <div class="col-4">
                        <h2>Visio des Habitants</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>" target="_blanck"><?= $village['village-jitsi-link'] ?></a>
                    </div>
                    <div class="col-4">
                        <h2>Visio des Loups Garous</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>loupsgarous" target="_blanck"><?= $village['village-jitsi-link'] ?>loupsgarous</a>
                    </div>
                </div>
                <hr class="my-5"/>
                <h2>Habitants</h2>
                <div class="row">
                    <?php
                        foreach( $habitants as $habitant ){
                            if( empty( $habitant['habitant-card-displayed'] ) && $habitant['habitant-card'] !== "storyteller" ){
                                echo('<div class="col-md-3 text-center mb-3">');
                                    echo('<h3>');
                                        echo($habitant['habitant-user'].' ');
                                        if( !empty( $habitant['habitant-is-the-mayor'] ) ){
                                            echo('<img src="/images/cards/mayor.jpg" width="30px"/>');
                                        }
                                    echo('</h3>');

                                    // Si le joueur est le conteur : Voir les cartes
                                    if( $is_a_storyteller ){
                                        show_the_card( $habitant );
                                    }

                                    // Le joueur peut voir sa carte
                                    elseif( $habitant['habitant-user'] === $user_data['user-id']){
                                        show_the_card( $habitant );
                                    }

                                    // Si le joueur est mort : Voir les cartes
                                    elseif( !$is_alive ){
                                        show_the_card( $habitant );   
                                    }

                                    else{
                                        echo('<img src="/images/cards/back.png" width="150px"/>');
                                    }
                                echo('</div>');
                            }
                        }
                    ?>
                </div>
                <hr class="my-5"/>
                <h2>Cimetière</h2>
                <div class="row">
                    <?php
                        foreach( $habitants as $habitant ){
                            if( !empty( $habitant['habitant-card-displayed'] ) && $habitant['habitant-card'] !== "storyteller"  ){
                                echo('<div class="col-md-3 text-center">');
                                    echo('<h3>'.$habitant['habitant-user'].'</h3>');
                                    show_the_card( $habitant );
                                echo('</div>');
                            }
                        }
                    ?>
                </div>
                <hr class="my-5"/>
                <?php
                    // phpinfo();
                ?>
                <hr class="my-5"/>
                <?php
                    // print_r( $user_data );
                ?>
                <hr class="my-5"/>
                <?php
                    // print_r( $storytellers );
                ?>
                <hr class="my-5"/>
                <?php
                    // print_r( $habitants );
                ?>
			</div>
		</section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>