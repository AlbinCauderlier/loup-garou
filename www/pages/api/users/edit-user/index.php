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
    $url = explode("/", $_GET['p2']);
    $result=array();
    $field_control = true;


    // echo($_POST['user-firstname']);
    // Sanitize requests
    if(empty($_POST['user-firstname'])){
        $result=array_merge($result,array("user-firstname" => "Missing and required" ) );
        $field_control = false;
    }
    else {
        $user_first_name = $_POST['user-firstname'];
    }

    if(empty($_POST['user-lastname'])){
        $result=array_merge($result,array("user-lastname" => "Missing and required" ) );
        $field_control = false;
    }
    else {
        $user_last_name = $_POST['user-lastname'];
    }
    if(empty($_POST['user-email-address'])){
        $result=array_merge($result,array("user-email-address" => "Missing and required" ) );
        $field_control = false;
    }
    else {
        $user_email_address = $_POST['user-email-address'];
    }
    if(empty($_POST['user-profile'])){
        $result=array_merge($result,array("user-profile" => "Missing and required" ) );
        $field_control = false;
    }
    else {
        $user_profile = $_POST['user-profile'];
    }


    $query_user = "UPDATE `users-data` SET `user-firstname`='".$user_first_name."',`user-lastname`='".$user_last_name."',`user-email-address`='".$user_email_address."',`user-profile`='".$user_profile."' WHERE `user-id` = '".$url[2]."' LIMIT 1;";



    $mysqli_user= new mysqli(DB_URL,DB_USER,DB_PASSWORD,"iris_users");
    if ($mysqli_user->connect_errno) {
        echo(json_encode( array("error" => "DB connection failed") ));
        printf("Connect failed: %s\n", $mysqli_user->connect_error);
        return;
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }


    $mysqli_logs = new mysqli(DB_URL,DB_USER,DB_PASSWORD,"iris_logs");
    if ($mysqli_logs->connect_errno) {
        printf("Connect failed: %s\n", $mysqli_logs->connect_error);
        exit();
    }

    if (!$mysqli_user->query($query_user)) {
        printf("Error message: %s\n", $mysqli_user->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_user->close();

    $result=array_merge($result,array("result" => "success" ) );
    // $result=array_merge($result,array("message" => "User ".$user_id." has been added" ) );
    // echo($result);

    echo( json_encode($result) );
    return;


?>
