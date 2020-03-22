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

    // Sanitize request
    $loan_id = (isset($_POST['loan-id']) ? strip_tags($_POST['loan-id']) : '');
    $invoice_line_type = (isset($_POST['invoice-line-type']) ? strip_tags($_POST['invoice-line-type']) : '');
    $invoice_line_amount = (isset($_POST['invoice-line-amount']) ? strip_tags($_POST['invoice-line-amount']) : '');
    $invoice_line_currency = (isset($_POST['invoice-line-currency']) ? strip_tags($_POST['invoice-line-currency']) : '');
    $invoice_line_start_date = (isset($_POST['invoice-line-start-date']) ? strip_tags($_POST['invoice-line-start-date']) : '');
    $invoice_line_end_date = (isset($_POST['invoice-line-end-date']) ? strip_tags($_POST['invoice-line-end-date']) : '');
    $invoice_line_interest_rate = (isset($_POST['invoice-line-interest-rate']) ? strip_tags($_POST['invoice-line-interest-rate']) : '');
    $invoice_line_amount_due = (isset($_POST['invoice-line-amount-due']) ? strip_tags($_POST['invoice-line-amount-due']) : '');
    $invoice_id = (isset($_POST['invoice-id']) ? strip_tags($_POST['invoice-id']) : '');

    // Validate inputs
    if(empty($loan_id)){
        $result=array_merge($result,array("loan-id" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($invoice_line_type)){
        $result=array_merge($result,array("invoice-line-type" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($invoice_line_currency)){
        $result=array_merge($result,array("invoice-line-currency" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($invoice_line_amount_due)){
        $result=array_merge($result,array("invoice_line_amount_due" => "Missing and required" ) );
        $field_control = false;
    }

    if(empty($invoice_id)){
        $result=array_merge($result,array("invoice-id" => "Missing and required" ) );
        $field_control = false;
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_invoice_lines = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_invoice_lines->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_invoice_lines->connect_error);
        return;
    }

    $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                    VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."','".$invoice_line_start_date."','".$invoice_line_end_date."','".$invoice_line_amount."','".$invoice_line_interest_rate."','".$invoice_line_amount_due."','".$invoice_id."')";

    if (!$mysqli_invoice_lines->query($query_invoice_lines)) {
        printf("Error message: %s\n", $mysqli_invoice_lines->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }
    $mysqli_invoice_lines->close();

    $log_data =  $_POST['invoice-line-type'].' , '.$_POST['loan-id'].' , '.$_POST['invoice-line-currency'].' , '.$_POST['invoice-line-amount'].' , '.$_POST['invoice-line-start-date'].' , '.$_POST['invoice-line-end-date'].' , '.$_POST['invoice-line-interest-rate'].' , '.$_POST['invoice-line-amount-due'].' , '.$_POST['invoice-id'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE INVOICE LINE", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Invoice-line has been added" ) );

    echo( json_encode($result) );
?>
