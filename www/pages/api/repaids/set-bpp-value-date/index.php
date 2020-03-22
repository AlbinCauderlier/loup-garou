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

    // Validate inputs

    $repaid_id = (isset($_POST['repaid-id']) ? strip_tags($_POST['repaid-id']) : '');
    if(empty($repaid_id)){
        $result=array_merge($result,array("repaid_id " => "Missing and required" ) );
        $field_control = false;
    }

    $repaid_date = (isset($_POST['repaid-bpp-value-date']) ? strip_tags($_POST['repaid-bpp-value-date']) : '');
    if(empty($repaid_date)){
        $result=array_merge($result,array("repaid-date" => "Missing and required" ) );
        $field_control = false;
    }


    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_repaid = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_repaid->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_repaid->connect_error);
        return;
    }

    $query_repaid = "UPDATE `repaids` SET `repaid-bpp-value-date` = '".$repaid_date."'
                                        WHERE `repaids`.`repaid-id` = '".$repaid_id."'";

    if (!$mysqli_repaid->query($query_repaid)) {
        printf("Error message: %s\n", $mysqli_repaid->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_repaid->close();

    $repaid = json_decode(callAPI('GET',API_URL.'/api/repaids/'.$repaid_id.'/'), true);
    callAPI('GET',API_URL.'/api/get-loan/refresh-cache/'.str_replace("/","-",$repaid['loan-id']).'/');

    $log_data =  $_POST['repaid-id'].' , '.$_POST['repaid-bpp-value-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "SET BPP VALUE DATE - REPAID", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Repaid bpp date has been added" ) );

    echo( json_encode($result) );
    return;
?>
