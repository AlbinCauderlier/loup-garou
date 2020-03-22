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

    $invoice_id = (isset($_POST['invoice-id']) ? strip_tags($_POST['invoice-id']) : '');
    if(empty($invoice_id)){
        $result=array_merge($result,array("invoice_id " => "Missing and required" ) );
        $field_control = false;
    }

    $invoice_reminder_date = (isset($_POST['invoice-reminder-date']) ? strip_tags($_POST['invoice-reminder-date']) : '');
    if(empty($invoice_reminder_date)){
        $result=array_merge($result,array("invoice-reminder-date" => "Missing and required" ) );
        $field_control = false;
    }


    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_invoices = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_invoices->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_invoices->connect_error);
        return;
    }

    $query_invoice = "UPDATE `invoices` SET    `invoice-reminder-date` = '".$invoice_reminder_date."'
                                        WHERE   `invoices`.`invoice-id` = '".$invoice_id."'";

    if (!$mysqli_invoices->query($query_invoice)) {
        printf("Error message: %s\n", $mysqli_invoices->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_invoices->close();

    $log_data =  $_POST['invoice-id'].' , '.$_POST['invoice-reminder-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "CHECK INVOICE REMINDED", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Invoice is now reminded" ) );

    echo( json_encode($result) );
    return;
?>
