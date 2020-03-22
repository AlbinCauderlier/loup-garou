<?php

/**
 *	Initialise les messages d'erreurs et de confirmations
 *
 */


function is_an_user_recorded( $email_address ){
    $query = "SELECT COUNT(*) FROM `users-data` WHERE `user-email-address` = '".$email_address."' LIMIT 1";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");
    $users = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($users);

    $users->close();
    mysqli_close($conn);

	if( $result['COUNT(*)'] == 1){
		return true;
	}
	
	return false;
}



function is_email_url_known($email_address){
	require_once("includes/functions.php");
	$is_email_url_known = json_decode(get_curl_call(AUTH_API_URL.'/api/auth/is_email_url_known/'.$email_address.'/'), true);

	// TODO - Tester les cas d'erreur

	if($is_email_url_known["result"] === "true"){
		return true;
	}
	return false;
}


function get_email_authentication_url($email_address){
	require_once("includes/functions.php");
	$get_email_authentication_url = json_decode(get_curl_call(AUTH_API_URL.'/auth/get_email_authentication_url/'.$email_address.'/'), true);

	// TODO - Tester les cas d'erreur

	return $get_email_authentication_url["result"];
}


function get_email_url($email_address){
	// Split par @
	$param = explode('@',$email_address);
	return $param[1];
}



function is_login_confirmed($email_address){
	require_once("includes/functions.php");
	$is_login_confirmed = json_decode(get_curl_call(AUTH_API_URL.'/auth/is_login_confirmed/'.$email_address.'/'), true);

	// TODO - Tester les cas d'erreur
	if($is_login_confirmed["result"] === "true"){
		return true;
	}
	return false;
}


function is_login_recorded($email_address){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "SELECT COUNT(*) FROM `users-data` WHERE `user-email-address` = '".$email_address."' LIMIT 1";
    $users = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($users);

    $users->close();
    mysqli_close($conn);

	if( $result['COUNT(*)'] == 1){
		return true;
	}
	
	return false;


	// require_once("../../includes/functions.php");
	// $is_login_recorded = json_decode(get_curl_call(AUTH_API_URL.'/auth/is_login_recorded/'.$email_address.'/'), true);

	// // TODO - Tester les cas d'erreur
	// if($is_login_recorded["result"] === "true"){
	// 	return true;
	// }
	// return false;
}



function is_email_otp_correct($user_id, $OTP){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "SELECT COUNT(*) FROM `users-auth` WHERE `user-id` = '".$user_id."' AND `user-email-confirmation-otp` = '".$OTP."' LIMIT 1";
    $users = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($users);

    $users->close();
    mysqli_close($conn);

	if( $result['COUNT(*)'] == 1){
		return true;
	}
	
	return false;

	// require_once("includes/functions.php");
	// $is_OTP_correct = json_decode(get_curl_call(AUTH_API_URL.'/auth/is_OTP_correct/'.$email_address.'/'.$OTP.'/'), true);

	// // TODO - Tester les cas d'erreur
	// if($is_OTP_correct["result"] === "true"){
	// 	return true;
	// }
	// return false;
}


function get_user_authentication_data($email_address){
	require_once("includes/functions.php");
	$get_user_authentication_data = json_decode(get_curl_call(AUTH_API_URL.'/auth/get_user_authentication_data/'.$email_address.'/'), true);

	return $get_user_authentication_data["result"];
}

function get_user_authentication_historic($email_address){
	require_once("includes/functions.php");
	$get_user_authentication_historic = json_decode(get_curl_call(AUTH_API_URL.'/auth/get_user_authentication_historic/'.$email_address.'/'), true);

	return $get_user_authentication_historic["result"];
}



function update_authentication_attempt($user_id){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "UPDATE `users-auth` SET `user-last-connexion-attempt-date` = '".date("Y-m-d hh:mm:ss")."' WHERE `users-auth`.`user-id` = '".$user_id."'";
    mysqli_query($conn, $query);
    mysqli_close($conn);

	// require_once("includes/functions.php");
	// get_curl_call(AUTH_API_URL.'/auth/update_authentication_attempt/'.$email_address.'/');
}

function update_authentication_succeed($user_id){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "UPDATE `users-auth` SET `user-last-connexion-date` = '".date("Y-m-d hh:mm:ss")."' WHERE `users-auth`.`user-id` = '".$user_id."'";
    mysqli_query($conn, $query);
    mysqli_close($conn);

	// require_once("includes/functions.php");
	// get_curl_call(AUTH_API_URL.'/auth/update_authentication_succeed/'.$email_address.'/');
}

function add_authentication_action($email_address, $authentication_action_type){
	require_once("includes/functions.php");
	get_curl_call(AUTH_API_URL.'/auth/add_authentication_action/'.$email_address.'/'.$authentication_action_type.'/');
}


function change_password( $user_id, $current_password, $new_password ){
	$conn_auth = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "UPDATE `users-auth` SET `user-password` = '".$new_password."' WHERE `users-auth`.`user-id` = '".$user_id."' AND `users-auth`.`user-password` = '".$current_password."' LIMIT 1";
    mysqli_query($conn_auth, $query);
    mysqli_close($conn_auth);
}



// function is_password_correct($email_address, $password){
// 	require_once("includes/functions.php");
// 	$is_password_correct = json_decode(get_curl_call(AUTH_API_URL.'/auth/is_password_correct/'.$email_address.'/'.$password.'/'), true);

// 	// TODO - Tester les cas d'erreur
// 	if($is_password_correct["result"] === "true"){
// 		return true;
// 	}
// 	return false;
// }

function is_password_valid($user_id, $password){
    $query = "SELECT COUNT(*) FROM `users-auth` WHERE `user-id` = '".$user_id."' AND `user-password` = '".$password."' LIMIT 1";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");
    $users = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($users);

    $users->close();
    mysqli_close($conn);

	if( $result['COUNT(*)'] == 1){
		return true;
	}
	
	return false;
}


function is_user_email_address_confirmed( $user_id ){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "SELECT COUNT(*) FROM `users-auth` WHERE `user-id` = '".$user_id."' AND `is-user-email-address-confirmed` = '1' AND `user-email-confirmation-otp` IS NULL LIMIT 1";
    $users = mysqli_query($conn, $query);

	$result=mysqli_fetch_array($users);

    $users->close();
    mysqli_close($conn);

	if( $result['COUNT(*)'] == 1){
		return true;
	}
	
	return false;
}

function set_user_email_address_confirmed( $user_id ){
	$conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");

    $query = "UPDATE `users-auth` SET `user-email-confirmation-otp` = NULL, `is-user-email-address-confirmed` = '1' WHERE `users-auth`.`user-id` = '".$user_id."'";
    mysqli_query($conn, $query);
    mysqli_close($conn);
}



function is_forgotten_password_OTP_correct($email_address, $OTP){
	require_once("includes/functions.php");
	$is_forgotten_password_OTP_correct = json_decode(get_curl_call(AUTH_API_URL.'/auth/is_forgotten_password_OTP_correct/'.$email_address.'/'.$OTP.'/'), true);

	// TODO - Tester les cas d'erreur
	if($is_forgotten_password_OTP_correct["result"] === "true"){
		return true;
	}
	return false;
}


function get_user_registration_date($email_address){
	require_once("includes/functions.php");
	$get_user_registration_date = json_decode(get_curl_call(AUTH_API_URL.'/auth/get_user_registration_date/'.$email_address.'/'), true);

	return $get_user_registration_date["result"];
}





/**
 *	Key generator
 *
 *	@param	$nombre_caractere	Longueur souhaité de la clé aléatoire
 *	@param	$chaine				Caractères autorisés
 *									Rq : le zéro est retiré pour éviter qu'il ne soit interprété.
 *
 */
function getRandomKey($nombre_caractere,$chaine){ // = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN123456789-_'){
	$nombre_lettres = strlen($chaine)-1;
	$key = '';

	for($i=0; $i < $nombre_caractere; $i++){
		$key .= $chaine[mt_rand(0, $nombre_lettres)];
	}

	return $key;
}



?>