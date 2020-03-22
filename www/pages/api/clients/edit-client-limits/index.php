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


    $company_abbr = (isset($_POST['company-abbr']) ? strip_tags($_POST['company-abbr']) : '');
    if(empty($company_abbr)){
        $result=array_merge($result,array("company_abbr" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($company_abbr) > 5){
        $result=array_merge($result,array("company_abbr" => "company_abbr is max 5 characters") );
        $field_control = false;
    }
    $company_abbr = strtoupper($company_abbr);

    $credit_limit_amount = (isset($_POST['credit-limit-amount']) ? strip_tags($_POST['credit-limit-amount']) : '');
    if(empty($credit_limit_amount)){
        $result=array_merge($result,array("credit_limit_amount" => "credit_limit_amount required") );
        $field_control = false;
    }
    if( filter_var($credit_limit_amount, FILTER_VALIDATE_FLOAT) === false ) {
        $result=array_merge($result,array("credit_limit_amount" => "credit_limit_amount must be a float value") );
        $field_control = false;
    }

    $previous_credit_limit_amount = (isset($_POST['previous-credit-limit-amount']) ? strip_tags($_POST['previous-credit-limit-amount']) : '');
    $credit_limit_currency = (isset($_POST['credit-limit-currency']) ? strip_tags($_POST['credit-limit-currency']) : '');
    if(empty($credit_limit_currency)){
        $result=array_merge($result,array("credit_limit_currency" => "credit_limit_currency required") );
        $field_control = false;
    }
    if(strlen($credit_limit_currency) > 3){
        $result=array_merge($result,array("credit_limit_currency" => "credit_limit_currency is max 3 characters") );
        $field_control = false;
    }

    $facility_fee = (isset($_POST['facility-fee']) ? strip_tags($_POST['facility-fee']) : 0);
    $facility_fee_amount = (isset($_POST['facility-fee-amount']) ? strip_tags($_POST['facility-fee-amount']) : 0);


    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    /* INSERT Client */
    $mysqli_client = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_client->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_client->connect_error);
        return;
    }

    $query_client = "UPDATE `clients` SET   `credit-limit-amount` = '".$credit_limit_amount."',
                                            `credit-limit-currency` = '".$credit_limit_currency."'
                                    WHERE   `clients`.`company-abbr` = '".$company_abbr."'";

    if (!$mysqli_client->query($query_client)) {
        printf("Error message: %s\n", $mysqli_client->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_client->close();

    $invoice_date = date("Y_m_t");
    $loan_fund = get_loan_fund( $loan_id );
    $invoice_id = $company_abbr."_".$loan_fund."_".$credit_limit_currency."_".$invoice_date;
    $mysqli_iris = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);

    if( !does_an_invoice_exists( $invoice_id ) ){
        // Creation de la facture
        $query_invoice = "INSERT INTO   `invoices`  (`invoice-id`,`invoice-date`)
                                        VALUE       ('".$invoice_id."','".str_replace("_","-",$invoice_date)."')";

        if (!$mysqli_iris->query($query_invoice)) {
                    printf("Error message: %s\n", $mysqli_iris->error);
                    echo(json_encode( array("error" => "DB request failed") ));
                    return;
        }
    }


    // AJOUT DES FACILITY FEES

    $loan_id = $company_abbr."/FacilityAgreement";

    $invoice_line_type = "Facility Fee";
    $invoice_line_currency = $credit_limit_currency;
    $invoice_line_start_date = "NULL";
    $invoice_line_end_date = "NULL";
    $invoice_line_amount = $credit_limit_amount - $previous_credit_limit_amount;
    $invoice_line_interest_rate = $facility_fee;
    $invoice_line_amount_due = $facility_fee_amount;

    $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                    VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."',".$invoice_line_start_date.",".$invoice_line_end_date.",'".$invoice_line_amount."',".$invoice_line_interest_rate.",'".$invoice_line_amount_due."','".$invoice_id."')";

    if (!$mysqli_iris->query($query_invoice_lines)) {
        printf("Error message: %s\n", $mysqli_iris->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_iris->close();

    $log_data =  $_POST['company-name'].' , '.$_POST['company-abbr'].' , '.$_POST['previous-credit-limit-amount'].' , '.$_POST['credit-limit-amount'].' , '.$_POST['credit-limit-currency'].' , '.$_POST['facility-fee'].' , '.$_POST['facility-fee-amount'];

    db_log( $_SERVER['HTTP_APIKEY'], "EDITED CLIENT LIMITS", $log_data);


    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Client-limit has been edited" ) );

    echo( json_encode($result) );
    return;
?>
