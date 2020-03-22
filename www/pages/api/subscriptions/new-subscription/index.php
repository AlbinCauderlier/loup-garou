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
    // Validate inputs

    $field_control = true;
    $result=array();



    $subscription_id = (isset($_POST['subscription-id']) ? strip_tags($_POST['subscription-id']) : '');
    if(empty($subscription_id)){
        $result=array_merge($result,array("subscription-id" => "Missing and required" ) );
        $field_control = false;
    }

    $fund_abbr = (isset($_POST['fund-abbr']) ? strip_tags($_POST['fund-abbr']) : '');
    if(empty($fund_abbr)){
        $result=array_merge($result,array("fund-abbr" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_amount = (isset($_POST['subscription-amount']) ? strip_tags($_POST['subscription-amount']) : '');
    if(empty($subscription_amount)){
        $result=array_merge($result,array("subscription-amount" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_price = (isset($_POST['subscription-price']) ? strip_tags($_POST['subscription-price']) : '');
    if(empty($subscription_price)){
        $result=array_merge($result,array("subscription-price" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_currency =  strtoupper((isset($_POST['subscription-currency']) ? strip_tags($_POST['subscription-currency']) : ''));
    if(empty($subscription_currency)){
        $result=array_merge($result,array("subscription-currency" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_interest_rate =  strtoupper((isset($_POST['subscription-interest-rate']) ? strip_tags($_POST['subscription-interest-rate']) : ''));
    if(empty($subscription_interest_rate)){
        $result=array_merge($result,array("subscription-interest-rate" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_value_date = (isset($_POST['subscription-value-date']) ? strip_tags($_POST['subscription-value-date']) : '');
    if(empty($subscription_value_date)){
        $result=array_merge($result,array("subscription-value-date" => "Missing and required" ) );
        $field_control = false;
    }

    $subscription_trade_date = (isset($_POST['subscription-trade-date']) ? strip_tags($_POST['subscription-trade-date']) : '');
    if(empty($subscription_trade_date)){
        $subscription_trade_date = '1970-01-01';
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_subscription = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_subscription->connect_errno) {
        printf("Connect failed: %s\n", $mysqli_subscription->connect_error);
        exit();
    }

    $query_subscription = "INSERT INTO  `private_placements_subscriptions`  (`subscription-id`,
                                                                            `fund-abbr`,
                                                                            `subscription-amount`,
                                                                            `subscription-price`,
                                                                            `subscription-currency`,
                                                                            `subscription-interest-rate`,
                                                                            `subscription-trade-date`,
                                                                            `subscription-value-date`)
                                    VALUE       ('".$subscription_id."','".$fund_abbr."','".$subscription_amount."','".$subscription_price."','".$subscription_currency."','".$subscription_interest_rate."','".$subscription_trade_date."','".$subscription_value_date."')";

    if (!$mysqli_subscription->query($query_subscription)) {
        printf("Error message: %s\n", $mysqli_subscription->error);
    }

    $mysqli_subscription->close();


    $fund = json_decode( callAPI('GET',API_URL.'/api/funds/?fund-abbr='.$fund_abbr), true);
    $fund = $fund[0];
    $invoice_id = $fund['pp-client'].'_'.$fund['fund-invoice-abbr'].'_'.$fund['fund-currencies'].'_'. date("Y_m_t", strtotime($subscription_value_date));

    if( !does_an_invoice_exists( $invoice_id ) ){
        $query = "INSERT INTO   `invoices`  (`invoice-id`,`invoice-date`)
                                VALUE       ('".$invoice_id."','".date("Y-m-t", strtotime($subscription_value_date))."')";
        $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
        mysqli_query($conn, $query);
        mysqli_close($conn);
    }

    create_an_invoice_line( $subscription_id, "Interest", $subscription_amount, $subscription_currency, $subscription_value_date, "NULL", $subscription_interest_rate, $invoice_id );


    $log_data =  $_POST['subscription-id'].' , '.$_POST['fund-abbr'].' , '.$_POST['subscription-amount'].' , '.$_POST['subscription-currency'].' , '.$_POST['subscription-price'].' , '.$_POST['subscription-value-date'].' , '.$_POST['subscription-trade-date'].' , '.$_POST['interest-rate'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE SUBSCRIPTION", $log_data);


    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Subscription has been added" ) );

    echo( json_encode($result) );
?>
