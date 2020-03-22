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
    $result = array();
    $field_control = true;

    // Sanitize request
    $event_label = (isset($_POST['event-label']) ? strip_tags($_POST['event-label']) : '');
    $loan_id = (isset($_POST['loan-id']) ? strip_tags($_POST['loan-id']) : '');
    $modification_value = (isset($_POST['modification-value']) ? strip_tags($_POST['modification-value']) : '');
    $new_due_date = (isset($_POST['new-due-date']) ? strip_tags($_POST['new-due-date']) : '');
    $starting_date = (isset($_POST['starting-date']) ? strip_tags($_POST['starting-date']) : '');



    // Validate inputs
    if(empty($event_label)){
        $result=array_merge($result,array("event-label" => "Missing and required" ) );
        $field_control = false;
    }

    if(empty($loan_id)){
        $result=array_merge($result,array("loan-id" => "Missing and required" ) );
        $field_control = false;
    }
    $loan_id = str_replace("/", "-", $loan_id);

    if(empty($starting_date)){
        $result=array_merge($result,array("starting-date" => "Missing and required" ) );
        $field_control = false;
    }

    if( $event_label==="INTEREST_RATE" && empty($_POST['modification-value']) ){
        $result=array_merge($result,array("modification-value" => "Missing and required" ) );
        $field_control = false;
    }

    if( $event_label==="DUE_DATE" && empty($_POST['new-due-date'])){
        $result=array_merge($result,array("new-due-date" => "Missing and required" ) );
        $field_control = false;
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }


    $mysqli_events = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_events->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_events->connect_error);
        return;
    }

    if( $event_label==="INTEREST_RATE" ){
        $query_loan = "INSERT INTO `events` (`event-id`, `event-label`, `loan-id`, `event-date`, `event-new-value`) VALUES (NULL, '".$event_label."', '".$loan_id."', '".$starting_date."', '".$modification_value."');";
    }
    elseif( $event_label==="DUE_DATE" ){
        $query_loan = "INSERT INTO `events` (`event-id`, `event-label`, `loan-id`, `event-date`, `event-new-due-date`) VALUES (NULL, '".$event_label."', '".$loan_id."', '".$starting_date."', '".$new_due_date."');";
    }

    if (!$mysqli_events->query($query_loan)) {
        printf("Error message: %s\n", $mysqli_events->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }
    $mysqli_events->close();

    $log_data =  $_POST['event-label'].' , '.$_POST['loan-id'].' , '.$_POST['starting-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE LOAN EVENT", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Event ".$_POST['event-label']." has been added" ) );

    echo( json_encode($result) );
    return;
?>
