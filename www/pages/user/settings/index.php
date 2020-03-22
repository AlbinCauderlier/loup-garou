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
	<title>Settings - Loups Garous</title>
    <?php
        include_once("common/head.php");
    ?>
</head>
<body id="top" data-spy="scroll" data-target="#toc" data-offset="130">
    <?php
        include_once("common/header.php");
    ?>
    <main>
		<section>
			<div class="container pt-4">
                <div class="row">
                    <aside class="col-md-2">
                        <nav class="p-0" id="toc">
                            <ul class="list-unstyled navbar-list mb-0">
                                <li class="nav-item">
                                    <a href="#profile" title="Change your profile data" class="nav-link border-left py-3">
                                        <i data-feather="user" class="mr-2"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#security" title="Change your password" class="nav-link border-left py-3">
                                        <i data-feather="lock" class="mr-2"></i> Security
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#international" title="Change your international settings" class="nav-link border-left py-3">
                                        <i data-feather="globe" class="mr-2"></i> International
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>
                    <div class="col-md-10">
                        <form id="profile-form" action="" method="post">
                            <h3 id="profile">Profile</h3>
                            <div class="form-row">
                                <div class="col-md-4 mt-2 text-center">
                                    <form id="upload_profile_picture_form" action="/controller/settings/set-profile-picture.php" method="post">
                                        <img src="/images/users/user-dimitri-rusca.png" class="w-75 rounded-circle img-thumbnail" alt="avatar" id="profile-picture"/>
                                        <div class="custom-file mt-3">
                                            <input type="file" class="custom-file-input picture" id="file-path" name="file-path" required/>
                                            <label class="custom-file-label" for="file-path">Choose file...</label>
                                        </div>
                                        <button class="btn btn-gradient p-2 btn-block mt-3" type="submit">
                                            <i data-feather="upload"></i> Upload File
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <?php 
                                        display_user_messages();
                                        
                                        include_once("common/forms/form-profile.php");
                                    ?>
                                </div>
                            </div>
                        </form>
                        <hr class="my-5"/>
                        <h3 id="security">Security</h3>
                        <?php
                            display_user_messages();
                            
                            include_once("lib/auth/forms/form-change-password.php");
                        ?>
                        <hr class="my-5"/>
                        <?php 
                            display_user_messages();

                            include_once("common/forms/form-change-international.php");
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