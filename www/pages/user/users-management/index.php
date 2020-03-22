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
	<title>Users Management - Loups Garous</title>
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
                        <h1 class="text-white">Users</h1>
                    </div>
                    <div class="col-auto">
                        <!-- <a href="/redis/flushall/" class="btn btn-primary px-3 py-2 mr-2">
                            <i data-feather="trash" class="mr-1"></i> Flush All
                        </a> -->
                    </div>
                </div>
            </div>
        </section>
		<section>
			<div class="container">
                <table class="table table-hover table-striped mb-4 dynamic-datatable">
                    <thead>
                        <tr class="text-center">
                            <th>User Id</th>
                            <th>Firstname</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Profile</th>
                        </tr>
                    </thead>
                    <tbody class="table-borderless border-bottom">
                    <?php
                        $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,'loups-garous-users');

                        $query = "SELECT * FROM `users-data`";
                        $users = mysqli_query($conn, $query);

                        $total_outstanding_usd = 0;
                        $total_outstanding_eur = 0;

                        while($user = mysqli_fetch_array($users,MYSQLI_ASSOC)) {
                            echo '<tr>';
                                echo '<td>'.$user['user-id'].'</td>';
                                echo '<td>'.$user['user-firstname'].'</td>';
                                echo '<td>'.$user['user-lastname'].'</td>';
                                echo '<td>'.$user['user-email-address'].'</td>';
                                echo '<td>'.$user['user-profile'].'</td>';
                            echo '</tr>';
                        }

                        $users->close();
                        mysqli_close($conn);
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