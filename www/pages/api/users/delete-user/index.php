<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Content-Type: application/json');

    if( $_SERVER['REQUEST_METHOD'] != "DELETE"){
        header("HTTP/1.1 405 Method Not Allowed");
        echo(json_encode( array("error" => "DELETE Service asked with a ".$_SERVER['REQUEST_METHOD']." method.") ));
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
    $user_id = $url[2];


    $query_user = "DELETE FROM `users-data` WHERE `user-id` = '".$user_id."' LIMIT 1;";


    $mysqli_user = new mysqli(DB_URL,DB_USER,DB_PASSWORD,"iris_users");
    if ($mysqli_user->connect_errno) {
        echo(json_encode( array("error" => "DB connection failed") ));
        printf("Connect failed: %s\n", $mysqli_user->connect_error);
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
    $result=array_merge($result,array("message" => "User ".$user_id." has been deleted" ) );

    echo( json_encode($result) );
    return;


?>
