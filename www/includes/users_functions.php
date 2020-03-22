<?php

function get_user_id( $email_address ){
    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"iris_users");

    $query = "SELECT `user-id` FROM `users-data` WHERE `users-data`.`user-email-address` = '".$email_address."' LIMIT 1";
    $line = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($line);

    $line->close();
    mysqli_close($conn);

    if( !isset($result['user-id']) || empty($result['user-id']) ){
    	return 0;
    }

	return $result['user-id'];
}




?>