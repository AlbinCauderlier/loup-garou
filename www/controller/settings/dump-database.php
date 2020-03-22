<?php
	require_once("../../configuration.php");

	$filename = "backup_iris_" . date("Y_m_d") . ".sql";
	$mime = "application/sql";

	header( "Content-Type: " . $mime );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

	$cmd = "mysqldump -u ".DB_USER." -p".DB_PASSWORD." ".DB_URL." --all-databases";

	passthru( $cmd );

    exit(0);
?>