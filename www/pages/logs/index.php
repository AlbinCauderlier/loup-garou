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
	<title>Logs - Loups Garous</title>
    <?php
        include_once("common/head.php");
    ?>
</head>
<body id="top">
    <?php
        include_once("common/header.php");
    ?>
    <main class="mt-0">
        <section class="bg-gradient my-4 pt-5">
            <div class="container py-3">
                <h1 class="text-white">Logs</h1>
            </div>
        </section>
		<section>
            <div class="container py-2">
                <table class="table table-hover table-striped border-bottom mb-4">
                    <thead>
                        <tr class="text-center">
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody class="table-borderless">
                    <?php
                        $logs = json_decode( callAPI('GET',API_URL.'/api/logs/'), true);

                        foreach( $logs as $log ){
                            echo '<tr>';
                                echo '<td>'.$log['log-timestamp'].'</td>';
                                echo '<td>'.$log['log-user'].'</td>';
                                echo '<td>'.$log['log-action'].'</td>';
                                echo '<td>'.$log['log-data'].'</td>';
                            echo '</tr>';
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