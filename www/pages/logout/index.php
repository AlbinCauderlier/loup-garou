<?php
	// Si l'utilisateur est toujours connecté, le déconnecter
	if (isset($_SESSION['isConnected']) && !empty($_SESSION['isConnected'])) {
		// Destruction des variables de la session
		session_unset();

		// Réinitialisation du tableau de session - On le vide int�gralement
		$_SESSION = array();

		// Destruction de la session
		session_destroy();

		// Destruction du tableau de session
		unset($_SESSION);

		db_log( $_SESSION['login'] , "AUTH LOGOUT");
	}

	header('Location: /');
	exit;
?>