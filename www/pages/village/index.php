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
            <div class="container py-3 mt-4">
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
                <?php
                    $storytellers_users = array_column($storytellers, 'habitant-user');
                    if( in_array( $user_data['user-id'], $storytellers_users) ){
                        $is_a_storyteller = true;
                    }

                    // echo('user-data :<br/>');
                    // print_r( $user_data );
                    // echo('<br/><br/>');
                    // echo('storytellers :<br/>');
                    // print_r( $storytellers );
                    // echo('<br/><br/>');
                    // echo('habitants :<br/>');
                    // print_r( $habitants );
                    // echo('<br/><br/>');

                    // if( $is_alive ){
                    //     echo('Alive : Yes<br/>');
                    // }
                    // else{
                    //     echo('Alive : No <br/>');
                    // }

                    if( $is_a_storyteller ){
                        $user_card = 'storyteller'; 
                    }
                    else{
                        foreach( $habitants as $habitant ){
                            if( empty( $habitant['habitant-card-displayed'] ) && $habitant['habitant-card'] !== "storyteller" ){
                                if( $habitant['habitant-user'] === $user_data['user-id'] ){
                                    $user_card = $habitant['habitant-card'];
                                }
                            }
                        }
                    }

                    // echo('<hr class="my-5"/>');
                ?>
                <div class="row">
                    <div class="col-3">
                        <h2>Conteur</h2>
                        <?php
                            foreach( $storytellers as $storyteller ){
                                echo('<label>'.$storyteller['habitant-user'].'</label><br/>');
                            }
                        ?>
                        <hr class="my-5"/>
                        <h2>Personnages</h2>
                        3 Loups-Garous<br/>
                        1 Grand Méchant Loup<br/>
                        ---<br/>
                        3 Villageois<br/>
                        1 Chasseur<br/>
                        1 Sorcière<br/>
                        1 Voyante<br/>
                        1 Petite-Fille<br/>
                        3 Frères<br/>
                        2 Soeurs<br/>
                    </div>
                    <div class="col-9">
                        <div class="jumbotron py-4">
                            <h1>Edition Spéciale Confinement</h1>
                            <p class="lead">
                                Dans cette version spéciale confinement, tous les habitants se retrouvent dans une grande visio.
                            </p>
                            <p class="lead">
                                Durant la journée, chacun exprime depuis son écran son avis, ses votes... en espérant survivre jusqu'au tour suivant.
                            </p>
                            <p class="lead">
                                A la tombée de la nuit,... tous les habitants éteignent leurs caméras et leurs micros... dans l'attente du réveil par le Conteur.
                            </p>
                            <a href="<?= $village['village-jitsi-link'] ?>" target="_blanck" class="btn btn-primary btn-block rounded-pill px-3 py-2">
                                <i data-feather="home" class="mr-1"></i> Rejoindre le Village
                            </a>
                            <hr class="my-4"/>
                            <p class="lead">
                                Alors que le village est endormi, seuls dans leurs chambres, les <b><i>Frères</i></b> et <b><i>Soeurs</i></b> parlent de la journée.
                                <br/>
                                <?php
                                    if( $user_card === "storyteller" || $user_card === "sisters" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'sisters" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2 mr-3">');
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Soeurs');
                                        echo('</a>');
                                    }

                                    if( $user_card === "storyteller" || $user_card === "brothers" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'brothers" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2">');
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Frères');
                                        echo('</a>');
                                    }
                                ?>
                            </p>
                            <hr class="my-4"/>
                            <p class="lead">
                                Pendant ce temps, les <b><i>Loups-Garous</i></b> se retrouvent dans une autre visio pour décider de leur victime.
                                <br/><br/>
                                Une <b><i>Petite-Fille</i></b> les écoute peut-être... sans montrer ni son visage, ni sa voix.
                                <br/><br/>
                                Si la tribu est au grand complet (aucun LG n'a été tué), alors <b><i>Le Grand Méchant Loup</i></b> désigne une 2ème victime qu'il souhaite faire dans le village.
                                <br/><br/>
                                Une fois ces sinistres décisions prises, ... <b><i>les Loups-Garous</i></b> se rendorment de leur visio de <b><i>Loups-Garous</i></b>, ... et retournent à leur sommeil de <b><i>Villageois</i></b>.
                                <?php
                                    if( $user_card === "storyteller" || $user_card === "werewolf" || $user_card === "grand_mechant_loup" || $user_card === "littlegirl" ){
                                        echo('<br/><br/>');
                                        echo('<a href="'.$village['village-jitsi-link'].'werewolfs" target="_blanck" class="btn btn-block btn-secondary rounded-pill px-3 py-2">');
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Loups-Garous<br/><small>(si tu es <b><i>la Petite Fille</i></b>, coupe ton micro et ta caméra avant de rejoindre les Loups Garous... pour ne pas te faire manger)</small>');
                                        echo('</a>');
                                    }
                                ?>
                            </p>
                            <hr class="my-4"/>
                            <p class="lead">
                                Lorsque tous <b><i>les Loups-Garous</i></b> se sont rendormis, le Conteur peut aller retrouver <b><i>la Voyante</i></b>.<br/>
                                Celle-ci lui indique le joueur dont elle aimerait connaitre le rôle.<br/>
                                <b><i>Le Conteur</i></b>, qui voit toutes les cartes, peut alors lui indiquer le rôle du joueur concerné...<br/>
                                ... et <b><i>la Voyante</i></b> se rendort.
                                <br/>
                                <?php
                                    if( $user_card === "storyteller" || $user_card === "soothsayer" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'soothsayer" target="_blanck" class="btn btn-success btn-block rounded-pill px-3 py-2">');
                                            echo('<i data-feather="eye" class="mr-1"></i> Rejoindre la Voyante');
                                        echo('</a>');
                                    }
                                ?>
                            </p>
                            <hr class="my-4"/>
                            <p class="lead">
                                Ensuite, <b><i>le Conteur</i></b> va retrouver <b><i>la Sorcière</i></b> pour lui demander si elle souhaite utiliser ses potions.
                                <br/>
                                <?php
                                    if( $user_card === "storyteller" || $user_card === "witch" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'witch" target="_blanck" class="btn btn-danger btn-block rounded-pill px-3 py-2">');
                                            echo('<i data-feather="zap" class="mr-1"></i> Rejoindre la Sorcière');
                                        echo('</a>');
                                    }
                                ?>                                    
                            </p>
                            <hr class="my-4"/>
                            <p class="lead">
                                <b><i>Le Conteur</i></b> réveille alors le village... avec souvent, de terribles nouvelles.
                            </p>
                        </div>
                    </div>
                </div>
                <hr class="my-5"/>
                <h2>Ta Carte</h2>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php
                            echo('<img src="/images/cards/'.$user_card.'.png" width="150px"/><br/>');
                            echo('<label>'.$user_card.'</label>');
                        ?>
                    </div>
                    <div class="col-md-9">
                        Explication
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
                <div class="row">
                    <div class="col-12 mb-5">
                        <h2>Tous les Habitants</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>" target="_blanck" class="btn btn-primary btn-block rounded-pill px-3 py-2">
                            <i data-feather="home" class="mr-1"></i><br/>Rejoindre le village
                        </a>
                    </div>
                    <div class="col-3 mb-5">
                        <h2>Les Loups Garous <small>et la Petite Fille</small></h2>
                        <a href="<?= $village['village-jitsi-link'] ?>werewolfs" target="_blanck" class="btn btn-secondary btn-block rounded-pill px-3 py-2">
                            <i data-feather="users" class="mr-1"></i><br/>Rejoindre les Loups-Garous
                        </a>
                    </div>
                    <div class="col-3 mb-5">
                        <h2>La Voyante</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>soothsayer" target="_blanck" class="btn btn-success btn-block rounded-pill px-3 py-2">
                            <i data-feather="eye" class="mr-1"></i><br/>Rejoindre la Voyante<br/>(si vous êtes la Voyante...)
                        </a>
                    </div>
                    <div class="col-3 mb-5">
                        <h2>La Sorcière</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>witch" target="_blanck" class="btn btn-danger btn-block rounded-pill px-3 py-2">
                            <i data-feather="zap" class="mr-1"></i><br/>Rejoindre la Sorcière<br/>(si vous êtes la Sorcière...)
                        </a>
                    </div>
                    <div class="col-3 mb-5">
                        <h2>Les Soeurs <br/>et Les Frères</h2>
                        <a href="<?= $village['village-jitsi-link'] ?>sisters" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2 mr-3">
                            <i data-feather="users" class="mr-1"></i><br/>Rejoindre les Soeurs
                        </a>
                        <a href="<?= $village['village-jitsi-link'] ?>brothers" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2">
                            <i data-feather="users" class="mr-1"></i><br/>Rejoindre les Frères
                        </a>
                    </div>
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
			</div>
		</section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>