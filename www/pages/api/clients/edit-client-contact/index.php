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


    $company_name = (isset($_POST['company-name']) ? strip_tags($_POST['company-name']) : '');
    if(empty($company_name)){
        $result=array_merge($result,array("company_name" => "Company Name is required") );
        $field_control = false;
    }
    if(strlen($company_name) > 80){
        $result=array_merge($result,array("company_name" => "company_name is max 80 characters") );
        $field_control = false;
    }


    // POSTAL ADDRESS
    $company_address_line_1 = (isset($_POST['company-address-line-1']) ? strip_tags($_POST['company-address-line-1']) : '');
    if(empty($company_address_line_1)){
        $result=array_merge($result,array("company_address_line_1" => "company_address_line_1 is required") );
        $field_control = false;
    }
    if(strlen($company_address_line_1) > 124){
        $result=array_merge($result,array("company_address_line_1" => "company_address_line_1 is max 124 characters") );
        $field_control = false;
    }

    $company_address_line_2 = (isset($_POST['company-address-line-2']) ? strip_tags($_POST['company-address-line-2']) : '');
    if(strlen($company_address_line_2) > 124){
        $result=array_merge($result,array("company_address_line_2" => "company_address_line_2 is max 124 characters") );
        $field_control = false;
    }

    $company_postal_code = (isset($_POST['company-postal-code']) ? strip_tags($_POST['company-postal-code']) : '');
    if(empty($company_postal_code)){
        $result=array_merge($result,array("company_postal_code" => "company_postal_code is required") );
        $field_control = false;
    }
    if(strlen($company_postal_code) > 10){
        $result=array_merge($result,array("company_postal_code" => "company_postal_code is max 10 characters") );
        $field_control = false;
    }

    $company_city = (isset($_POST['company-city']) ? strip_tags($_POST['company-city']) : '');
    if(empty($company_city)){
        $result=array_merge($result,array("company_city" => "company_city is required") );
        $field_control = false;
    }
    if(strlen($company_city) > 124){
        $result=array_merge($result,array("company_city" => "company_city is max 124 characters") );
        $field_control = false;
    }

    $company_country = (isset($_POST['company-country']) ? strip_tags($_POST['company-country']) : '');
    if(empty($company_country)){
        $result=array_merge($result,array("company_country" => "company_country is required") );
        $field_control = false;
    }
    if(strlen($company_country) > 2){
        $result=array_merge($result,array("company_country" => "company_country is max 2 characters") );
        $field_control = false;
    }


    // CONTACT
    $company_website = (isset($_POST['company-website']) ? strip_tags($_POST['company-website']) : '');
    if(strlen($company_website) > 80){
        $result=array_merge($result,array("company_website" => "company_website is max 80 characters") );
        $field_control = false;
    }

    $company_phone_number = (isset($_POST['company-phone-number']) ? strip_tags($_POST['company-phone-number']) : '');
    if(strlen($company_phone_number) > 20){
        $result=array_merge($result,array("company_phone_number" => "company_phone_number is max 20 characters") );
        $field_control = false;
    }

    $company_fiscal_id = (isset($_POST['company-fiscal-id']) ? strip_tags($_POST['company-fiscal-id']) : '');
    if(strlen($company_fiscal_id) > 80){
        $result=array_merge($result,array("company_fiscal_id" => "company_fiscal_id is max 80 characters") );
        $field_control = false;
    }

    $company_zefix_link = (isset($_POST['company-zefix-link']) ? strip_tags($_POST['company-zefix-link']) : '');
    if(strlen($company_zefix_link) > 256){
        $result=array_merge($result,array("company_zefix_link" => "company_zefix_link is max 256 characters") );
        $field_control = false;
    }


    $company_relation_manager = (isset($_POST['company-relation-manager']) ? strip_tags($_POST['company-relation-manager']) : '');
    if(empty($company_relation_manager)){
        $result=array_merge($result,array("company_relation_manager" => "company_relation_manager required") );
        $field_control = false;
    }
    if(strlen($company_relation_manager) > 80){
        $result=array_merge($result,array("company_relation_manager" => "company_relation_manager is max 80 characters") );
        $field_control = false;
    }

    $company_account_operator = (isset($_POST['company-account-operator']) ? strip_tags($_POST['company-account-operator']) : '');
    if(empty($company_account_operator)){
        $result=array_merge($result,array("company_account_operator" => "company_account_operator required") );
        $field_control = false;
    }
    if(strlen($company_account_operator) > 80){
        $result=array_merge($result,array("company_account_operator" => "company_account_operator is max 80 characters") );
        $field_control = false;
    }

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

    $mysqli_logs = new mysqli(DB_URL,DB_USER,DB_PASSWORD,"iris_logs");
    if ($mysqli_logs->connect_errno) {
        printf("Connect failed: %s\n", $mysqli_logs->connect_error);
        exit();
    }

    $query_client = "UPDATE `clients` SET   `company-name` = '".$company_name."',
                                            `company-address-line-1` = '".$company_address_line_1."',
                                            `company-address-line-2` = '".$company_address_line_2."',
                                            `company-postal-code` = '".$company_postal_code."',
                                            `company-city` = '".$company_city."',
                                            `company-country` = '".$company_country."',
                                            `company-website` = '".$company_website."',
                                            `company-phone-number` = '".$company_phone_number."',
                                            `company-email-address` = '".$company_email_address."',
                                            `company-fiscal-id` = '".$company_fiscal_id."',
                                            `company-zefix-link` = '".$company_zefix_link."',
                                            `company-relation-manager` = '".$company_relation_manager."',
                                            `company-account-operator` = '".$company_account_operator."'
                                    WHERE   `clients`.`company-abbr` = '".$company_abbr."'";

    if (!$mysqli_client->query($query_client)) {
        printf("Error message: %s\n", $mysqli_client->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }

    $mysqli_client->close();

    $log_data =  $_POST['company-name'].' , '.$_POST['company-abbr'].' , '.$_POST['company-address-line-1'].' , '.$_POST['company-abbr'].' , '.$_POST['company-address-line-1'].' , '.$_POST['company-address-line-2'].' , '.$_POST['company-postal-code'].' , '.$_POST['company-city'].' , '.$_POST['company-country'].' , '.$_POST['company-website'].' , '.$_POST['company-phone-number'].' , '.$_POST['company-email-address'].' , '.$_POST['company-fiscal-id'].' , '.$_POST['company-zefix-link'].' , '.$_POST['company-relation-manager'].' , '.$_POST['company-account-operator'];

    db_log( $_SERVER['HTTP_APIKEY'], "EDITED CLIENT CONTACT", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Client has been edited" ) );

    echo( json_encode($result) );
    return;
?>
