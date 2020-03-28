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
                        <h2>Personnages possibles</h2>
                        Loup-Garou (werewolf)<br/>
                        Grand Méchant Loup (grand_mechant_loup)<br/>
                        ---<br/>
                        Villageois (citizen)<br/>
                        Chasseur (hunter)<br/>
                        Sorcière (witch)<br/>
                        Voyante (soothsayer)<br/>
                        Petite-Fille (littlegirl)<br/>
                        Frères (brothers)<br/>
                        Soeurs (sisters)<br/>
                        <hr class="my-5"/>
                        <h2>Personnages dans cette partie</h2>
                        <?php
                            foreach( array_column($habitants, 'habitant-card') as $card ){
                                echo($card.'<br/>');
                            }
                        ?>
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
                                <i data-feather="home" class="mr-1"></i> Rejoindre le Village<br/><small>(Ouvrir dans un nouvel onglet)</small>
                            </a>
                            <hr class="my-4"/>
                            <p class="lead">
                                Alors que le village est endormi, seuls dans leurs chambres, les <b><i>Frères</i></b> et <b><i>Soeurs</i></b> parlent de la journée.
                                <br/>
                                <?php
                                    if( $user_card === "storyteller" || $user_card === "sisters" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'sisters" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2 mr-3">');
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Soeurs<br/><small>(Ouvrir dans un nouvel onglet)</small>');
                                        echo('</a>');
                                    }

                                    if( $user_card === "storyteller" || $user_card === "brothers" ){
                                        echo('<a href="'.$village['village-jitsi-link'].'brothers" target="_blanck" class="btn btn-dark btn-block rounded-pill px-3 py-2">');
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Frères<br/><small>(Ouvrir dans un nouvel onglet)</small>');
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
                                            echo('<i data-feather="users" class="mr-1"></i> Rejoindre les Loups-Garous<br/><small>(si tu es <b><i>la Petite Fille</i></b>, coupe ton micro et ta caméra avant de rejoindre les Loups Garous... pour ne pas te faire manger)</small><br/><small>(Ouvrir dans un nouvel onglet)</small>');
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
                                            echo('<i data-feather="eye" class="mr-1"></i> Rejoindre la Voyante<br/><small>(Ouvrir dans un nouvel onglet)</small>)');
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
                                            echo('<i data-feather="zap" class="mr-1"></i> Rejoindre la Sorcière<br/><small>(Ouvrir dans un nouvel onglet)</small>');
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
                        <p>
                        <?php
                            if( $user_card === "storyteller"){
                                echo('Le Conteur ne joue pas mais dirige la partie. Il distribue à chaque joueur 1 carte personnage face cachéee.<br/>
                                    Le Conteur endort le village et dit «  C’est la nuit tout le village s’endort, les joueurs ferment les yeux »<br/>
                                    Tous les personnages coupent leurs micros et leurs caméras.');
                            }

                            elseif( $user_card === "werewolf"){
                                echo('<h4>LE LOUP-GAROU</h4>');
                                echo('Chaque nuit, les Loups Garous se retrouvent pour dévorer un Villageois.<br/>
                                    Le jour, au milieu des autres villageois, ils essaient de masquer leur identité nocturne pour échapper à la vindicte populaire.<br/>');
                                echo('<label>Conseil :</label> Un stratagème efficace pour se dédouaner d’une accusation, est de voter contre son partenaire. Encore faut-il que les Villageois le remarque.');
                            }

                            elseif( $user_card === "citizen"){
                                echo('<h4>LE VILLAGEOIS</h4>');
                                echo('Pas de compétence particulière.<br/>Ses seules armes sont la capacité d’analyse des comportements pour identifier les
                            Loups-Garous et la force de conviction pour empêcher l’exécution de l’innocent qu’il est.');
                            }

                            elseif( $user_card === "soothsayer"){
                                echo('<h4>LA VOYANTE</h4>');
                                echo('Chaque nuit, elle découvre la vraie personnalité d’un joueur de son choix. Elle doit aider les autres villageois, mais
                            reste discrète pour ne pas être démasquée par les Loups-Garous.<br/>');
                                echo('<label>Conseil :</label> Attention, si vous avez découvert un Loup-Garou, cela vaut peut-être la peine de vous dévoiler pour accuser le joueur mais pas trop tôt !');
                            }

                            elseif( $user_card === "hunter"){
                                echo('<h4>LE CHASSEUR</h4>');
                                echo('S’il se fait dévorer pas les Loups Garous ou exécuter malencontreusement par les joueurs, le Chasseur a le pouvoir
                            de répliquer avant de rendre l’âme, en éliminant immédiatement n’importe quel autre joueur de son choix.<br/>');
                                echo('<label>Conseil :</label> Il est toujours bon en cas d’accusation de se faire passer pour le Chasseur');
                            }

                            elseif( $user_card === "witch"){
                                echo('<h4>LA SORCIERE</h4>');
                                echo('Elle sait concocter 2 potions extrêmement puissantes :<br/>');
                                echo('- Une potion de guérison, pour ressusciter le joueur tué par les Loups-Garous,<br/>
                                        - Une potion d’empoissonnement, utilisée la nuit pour éliminer un joueur.<br/>');
                                echo('La sorcière ne peut utiliser chaque potion qu’une seule fois dans la partie. Elle peut se servir de ses 2 potions dans la même nuit.<br/>');
                                echo('Le matin suivant, elle pourra, suivant l’usage de ce pouvoir, y avoir 0,1 ou 2 morts. La sorcière peut utiliser les
                            potions à son profit, et donc se guérir elle-même. Si au bout d’un certain nombre de parties, vous trouvez ce
                            personnage trop puissant, limitez ses pouvoirs à une seule potion pour la partie.<br/>');
                                echo('<label>Conseil :</label> Ce personnage devient plus puissant en fin de partie, ne gaspillez pas vos sorts.');
                            }

                            elseif( $user_card === "littlegirl"){
                                echo('<h4>LA PETITE FILLE</h4>');
                                echo('Elle peut, en entrouvrant les yeux, espionner les Loups Garous pendant leur réveil. Si elle se fait surprendre par l’un
                            d’eux, elle meurt immédiatement (en silence), à la place de la victime désignée. La Petite Fille ne peut espionner que
                            la nuit, durant le tour d’éveil des Loups-Garous.<br/>');
                                echo('<label>Conseil :</label> Personnage très puissant, mais très angoissant à jouer. N’hésitez pas à espionner. Cela fait peur mais il faut en profiter rapidement avant d’être éliminé.');
                            }

                            elseif( $user_card === "grand_mechant_loup"){
                                echo('<h4>LE GRAND MECHANT LOUP</h4>');
                                echo('En plus de son rôle de Loup-Garou, le Grand Méchant Loup peut désigner, chaque nuit, une 2ème victime parmis les villageois, tant que tous les Loups-Garous sont encore en vie.<br/>');
                                echo('<label>Conseil :</label> Le Grand Méchant Loup a tout intérêt à ce que les Loups Garous soient solidaires pendant la journée.');
                            }

                            else{
                                echo('Votre personnage est décrit dans les explications ci-dessus.<br/>');
                                echo('Si besoin, vous pouvez demander au Conteur les explications de votre rôle.');
                            }
                        ?>
                        </p>
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
                    <!-- <div class="col-3 mb-5">
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
                    </div> -->
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