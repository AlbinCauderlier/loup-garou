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


    // Validate inputs
    $company_abbr = (isset($_POST['company-abbr']) ? strip_tags($_POST['company-abbr']) : '');
    if(empty($company_abbr)){
        $result=array_merge($result,array("company_abbr" => "Missing and required" ) );
        $field_control = false;
    }

    $fund_abbr = (isset($_POST['fund-abbr']) ? strip_tags($_POST['fund-abbr']) : '');
    if(empty($fund_abbr)){
        $result=array_merge($result,array("fund_abbr" => "Missing and required" ) );
        $field_control = false;
    }

    $facility_agreement_name = (isset($_POST['facility-agreement-id']) ? strip_tags($_POST['facility-agreement-id']) : '');
    if(empty($facility_agreement_name)){
        $result=array_merge($result,array("facility_agreement_id" => "Missing and required" ) );
        $field_control = false;
    }

    $facility_agreement_date = (isset($_POST['facility-agreement-date']) ? strip_tags($_POST['facility-agreement-date']) : '');
    if(empty($facility_agreement_date)){
        $result=array_merge($result,array("facility_agreement_date" => "Missing and required" ) );
        $field_control = false;
    }

    $credit_limit_amount = (isset($_POST['credit-limit-amount']) ? strip_tags($_POST['credit-limit-amount']) : '');
    if(empty($credit_limit_amount)){
        $result=array_merge($result,array("credit_limit_amount" => "Missing and required" ) );
        $field_control = false;
    }

    $credit_limit_currency = (isset($_POST['credit-limit-currency']) ? strip_tags($_POST['credit-limit-currency']) : '');
    if(empty($credit_limit_currency)){
        $result=array_merge($result,array("credit_limit_currency" => "Missing and required" ) );
        $field_control = false;
    }

    // Content control
    $company_abbr = strtoupper($company_abbr);

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    /* INSERT facility */
    $mysqli_facility = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_facility->connect_error);
        return;
    }

    $query_facility = "INSERT INTO `facility_agreements`
                (`facility-agreement-id`, `facility-agreement-name`, `facility-agreement-date`, `fund-id`, `facility-agreement-limit`, `facility-agreement-currency`, `company-abbr`)
                VALUES (NULL, '".$facility_agreement_name."','".$facility_agreement_date."','".$fund_abbr."','".$credit_limit_amount."','".$credit_limit_currency."','".$company_abbr."')";

    if (!$mysqli_facility->query($query_facility)) {
        printf("Error message: %s\n", $mysqli_facility->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_facility->close();

    $log_data =  $_POST['company-abbr'].' , '.$_POST['fund-abbr'].' , '.$_POST['facility-agreement-id'].' , '.$_POST['facility-agreement-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE FACILITY AGREEMENT", $log_data);


    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Facility Agreement has been added" ) );

    echo( json_encode($result) );
    return;
?>
