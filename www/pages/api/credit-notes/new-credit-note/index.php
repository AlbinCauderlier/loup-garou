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
    $invoice_id = (isset($_POST['invoice-id']) ? strip_tags($_POST['invoice-id']) : '');
    $loan_id = (isset($_POST['loan-id']) ? strip_tags($_POST['loan-id']) : '');
    $amount_due = (isset($_POST['credit-note-amount-due']) ? strip_tags($_POST['credit-note-amount-due']) : '');
    $credit_note_currency = (isset($_POST['credit-note-currency']) ? strip_tags($_POST['credit-note-currency']) : '');
    $credit_note_type = (isset($_POST['credit-note-type']) ? strip_tags($_POST['credit-note-type']) : '');

    error_reporting(0);
    $result = array();
    $field_control = true;



    // Validate inputs
    if(empty($credit_note_type)){
        $result=array_merge($result,array("credit-note-type" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($invoice_id)){
        $result=array_merge($result,array("invoice-id" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($loan_id)){
        $result=array_merge($result,array("loan-id" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($amount_due)){
        $result=array_merge($result,array("amount-due" => "Missing and required" ) );
        $field_control = false;
    }
    if(empty($credit_note_currency)){
        $result=array_merge($result,array("credit-note-currency" => "Missing and required" ) );
        $field_control = false;    }

    $amount_due_neg = $amount_due * -1;

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_credit = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_credit->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_credit->connect_error);
        return;
    }

    $query_credit = "INSERT INTO   `credit_notes` (`credit-note-id`,`loan-id`,`invoice-id`,`credit-note-type`,`credit-note-amount`,`credit-note-currency`)
                                    VALUE       (NULL,'".$loan_id."','".$invoice_id."','".$credit_note_type."','".$amount_due."','".$credit_note_currency."')";
    if (!$mysqli_credit->query($query_credit)) {
        printf("Error message: %s\n", $mysqli_credit->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_credit->close();

    $log_data =  $_POST['invoice-id'].' , '.$_POST['loan-id'].' , '.$_POST['company-address-line-1'].' , '.$_POST['invoice-line-type'].' , '.$_POST['invoice-line-currency'].' , '.$_POST['credit-note-amount-due'].' , '.$_POST['credit-note-currency'].' , '.$_POST['credit-note-type'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE CREDIT NOTE", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Credit Note has been added" ) );

    echo( json_encode($result) );
    return;
?>
