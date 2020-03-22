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

    $result=array();
    $field_control = true;

    $drawdown_id = (isset($_POST['drawdown-id']) ? strip_tags($_POST['drawdown-id']) : '');
    $drawdown_bpp_value_date = (isset($_POST['drawdown-bpp-value-date']) ? strip_tags($_POST['drawdown-bpp-value-date']) : '');


    // Sanitize request
    if(empty($_POST['drawdown-id'])){
        $result=array_merge($result,array("drawdown_id" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($_POST['drawdown-bpp-value-date'])){
        $result=array_merge($result,array("drawdown_bpp_value_date" => "Missing and required" ) );
        $field_control = false;
    }



    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }


    /* INSERT LOAD */
    $mysqli_drawdown = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_drawdown->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_drawdown->connect_error);
        return;
    }

    $query_drawdown = "UPDATE `drawdowns` SET   `drawdown-bpp-value-date` = '".$drawdown_bpp_value_date."'
                                        WHERE `drawdowns`.`drawdown-id` = '".$drawdown_id."'";

    if (!$mysqli_drawdown->query($query_drawdown)) {
        printf("Error message: %s\n", $mysqli_drawdown->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }
    $mysqli_drawdown->close();

    $drawdown = json_decode(callAPI('GET',API_URL.'/api/drawdowns/'.$drawdown_id.'/'), true);
    callAPI('GET',API_URL.'/api/get-loan/refresh-cache/'.str_replace("/","-",$drawdown['loan-id']).'/');

    $log_data =  $_POST['drawdown-id'].' , '.$_POST['drawdown-bpp-value-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "SET BPP VALUE DATE - DRAWDOWN", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Set bpp value date has been added" ) );

    echo( json_encode($result) );
    return;
?>
