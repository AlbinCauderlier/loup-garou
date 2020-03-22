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
    if(empty($loan_id)){
        $result=array_merge($result,array("loan_id" => "Missing and required" ) );
        $field_control = false;
    }
    if(strlen($loan_id) > 80){
        $result=array_merge($result,array("loan_id" => "loan_id is max 80 characters") );
        $field_control = false;
    }

    $loan_facility_type = (isset($_POST['facility-type']) ? strip_tags($_POST['facility-type']) : '');
    if(empty($loan_facility_type)){
        $result=array_merge($result,array("loan_facility_type" => "Missing and required" ) );
        $field_control = false;
    }



    $loan_initial_due_date = (isset($_POST['loan-initial-due-date']) ? strip_tags($_POST['loan-initial-due-date']) : '');
    if(empty($loan_initial_due_date)){
        $result=array_merge($result,array("loan_initial_due_date" => "Missing and required" ) );
        $field_control = false;
    }


    $loan_value = (isset($_POST['loan-amount']) ? strip_tags($_POST['loan-amount']) : '');
    if(empty($loan_value)){
        $result=array_merge($result,array("loan_value" => "Missing and required" ) );
        $field_control = false;
    }

    $interest_rate = (isset($_POST['interest-rate']) ? strip_tags($_POST['interest-rate']) : '');
    if(empty($interest_rate)){
        $result=array_merge($result,array("interest_rate" => "Missing and required" ) );
        $field_control = false;
    }

    $drawdown_fee = (isset($_POST['drawdown-fee']) ? strip_tags($_POST['drawdown-fee']) : 'NULL');
    $drawdown_fee_rate = (isset($_POST['drawdown-fee-rate']) ? strip_tags($_POST['drawdown-fee-rate']) : 'NULL');
    $minimum_facility_fee = (isset($_POST['minimum-facility-fee']) ? strip_tags($_POST['minimum-facility-fee']) : 'NULL');

    $banking_fee = (isset($_POST['banking-fee']) ? strip_tags($_POST['banking-fee']) : '');
    if(empty($banking_fee)){
        $result=array_merge($result,array("banking_fee" => "Missing and required" ) );
        $field_control = false;
    }

    $penality_fee = (isset($_POST['penality-fee']) ? strip_tags($_POST['penality-fee']) : 'NULL');
    $success_fee = (isset($_POST['success-fee']) ? strip_tags($_POST['success-fee']) : 'NULL');




    $loan_buyer_name = (isset($_POST['loan-buyer-name']) ? strip_tags($_POST['loan-buyer-name']) : '');
    if(empty($loan_buyer_name)){
        $result=array_merge($result,array("loan_buyer_name" => "Missing and required" ) );
        $field_control = false;
    }

    $loan_buyer_country = (isset($_POST['loan-buyer-country']) ? strip_tags($_POST['loan-buyer-country']) : '');

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    /* INSERT LOAD */
    $mysqli_loan = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_loan->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_loan->connect_error);
        return;
    }

    $query_loan = "UPDATE `loans` SET   `loan-facility-type` = '".$loan_facility_type."',
                                        `loan-initial-due-date` = '".$loan_initial_due_date."',
                                        `loan-value` = '".$loan_value."',
                                        `loan-interest-rate` = '".$interest_rate."',
                                        `loan-drawdown-fee` = '".$drawdown_fee."',
                                        `loan-drawdown-fee-rate` = '".$drawdown_fee_rate."',
                                        `loan-minimum-facility-fee` = '".$minimum_facility_fee."',
                                        `loan-banking-fee` = '".$banking_fee."',
                                        `loan-penality-fee` = '".$penality_fee."',
                                        `loan-success-fee` = '".$success_fee."',
                                        `loan-buyer` = '".$loan_buyer_name."'
                                    WHERE `loan-id` = '".$loan_id."'";

    if (!$mysqli_loan->query($query_loan)) {
        printf("Error message: %s\n", $mysqli_loan->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }
    $mysqli_loan->close();


    $log_data =  $_POST['loan-id'].' , '.$_POST['facility-type'].' , '.$_POST['loan-initial-due-date'].' , '.$_POST['loan-amount'].' , '.$_POST['interest-rate'].' , '.$_POST['invoice-id'].' , '.$_POST['banking-fee'];

    db_log( $_SERVER['HTTP_APIKEY'], "EDIT LOAN", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Loan has been added" ) );

    echo( json_encode($result) );

?>
