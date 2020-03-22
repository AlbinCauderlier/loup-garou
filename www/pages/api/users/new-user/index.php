<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Content-Type: application/json');

    if( $_SERVER['REQUEST_METHOD'] != "POST"){
        header("HTTP/1.1 405 Method Not Allowed");
        echo(json_encode( array("error" => "POST Service asked with a ".$_SERVER['REQUEST_METHOD']." method.") ));
        return;
    }

    if( $_SERVER['HTTP_APIKEY'] != APIKEY ){
        header("HTTP/1.1 401 - Unauthorized");
        echo(json_encode( array("error" => "Wrong API Key") ));
        return;
    }

    error_reporting(0);

    // $url = explode("/", $_GET['p2']);
    $result=array();
    $field_control = true;


    // Sanitize Request
    $user_id = (isset($_POST['user-id']) ? strip_tags($_POST['user-id']) : '');
    if(empty($user_id)){
        $result=array_merge($result,array("user-id" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($user_id) > 80 ){
        $result=array_merge($result,array("user-id" => "User-id is max 80 characters" ) );
        $field_control = false;
    }
    // "user-id": "albin.cauderlier",
    $user_first_name = (isset($_POST['user-firstname']) ? strip_tags($_POST['user-firstname']) : '');
    if(empty($user_first_name)){
        $result=array_merge($result,array("user-firstname" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($user_first_name) > 80 ){
        $result=array_merge($result,array("user-firstname" => "User-firstname is max 80 characters" ) );
        $field_control = false;
    }
    // "user-firstname": "Albin",
    $user_last_name= (isset($_POST['user-lastname']) ? strip_tags($_POST['user-lastname']) : '');
    if(empty($user_last_name)){
        $result=array_merge($result,array("user-lastname" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($user_last_name) > 80 ){
        $result=array_merge($result,array("user-lastname" => "User-lastname is max 80 characters" ) );
        $field_control = false;
    }
    $user_last_name = strtoupper($user_last_name);
    // "user-lastname": "CAUDERLIER",
    $user_email_address = (isset($_POST['user-email-address']) ? strip_tags($_POST['user-email-address']) : '');
    if(empty($user_email_address)){
        $result=array_merge($result,array("user-email-address" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($user_email_address) > 80 ){
        $result=array_merge($result,array("user-email-address" => "user-email-address is max 80 characters" ) );
        $field_control = false;
    }
    // "user-email-address": "ac@sccf.ch",
    $user_profile= (isset($_POST['user-profile']) ? strip_tags($_POST['user-profile']) : '');
    if(empty($user_profile)){
        $result=array_merge($result,array("user-profile" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($user_profile) > 80 ){
        $result=array_merge($result,array("user-profile" => "user-email-address is max 80 characters" ) );
        $field_control = false;
    }
    // "user-profile": "administrator"


    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }


    $mysqli_user= new mysqli(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");
    if ($mysqli_user->connect_errno) {
        echo(json_encode( array("error" => "DB connection failed") ));
        printf("Connect failed: %s\n", $mysqli_user->connect_error);
        return;
    }

    $mysqli_logs = new mysqli(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-logs");
    if ($mysqli_logs->connect_errno) {
        printf("Connect failed: %s\n", $mysqli_logs->connect_error);
        exit();
    }

    $query_users = "INSERT INTO `users-data`
                (`user-id`, `user-firstname`, `user-lastname`, `user-email-address`, `user-profile`)
                VALUE ('".$user_id."','".$user_first_name."','".$user_last_name."','".$user_email_address."','".$user_profile."')";

    if (!$mysqli_user->query($query_users)) {
        printf("Error message: %s\n", $mysqli_user->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_user->close();

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "User ".$user_id." has been added" ) );
    // echo($result);

    echo( json_encode($result) );
    return;

?>
