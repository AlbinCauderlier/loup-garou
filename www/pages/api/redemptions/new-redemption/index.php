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



    $redemption_id = (isset($_POST['redemption-id']) ? strip_tags($_POST['redemption-id']) : '');
    if(empty($redemption_id)){
        $result=array_merge($result,array("redemption-id" => "Missing and required" ) );
        $field_control = false;
    }

    $fund_abbr = (isset($_POST['fund-abbr']) ? strip_tags($_POST['fund-abbr']) : '');
    if(empty($fund_abbr)){
        $result=array_merge($result,array("fund-abbr" => "Missing and required" ) );
        $field_control = false;
    }

    $redemption_amount = (isset($_POST['redemption-amount']) ? strip_tags($_POST['redemption-amount']) : '');
    if(empty($redemption_amount)){
        $result=array_merge($result,array("redemption-amount" => "Missing and required" ) );
        $field_control = false;
    }

    $redemption_price = (isset($_POST['redemption-price']) ? strip_tags($_POST['redemption-price']) : '');
    if(empty($redemption_price)){
        $result=array_merge($result,array("redemption-price" => "Missing and required" ) );
        $field_control = false;
    }

    $redemption_currency =  strtoupper((isset($_POST['redemption-currency']) ? strip_tags($_POST['redemption-currency']) : ''));
    if(empty($redemption_currency)){
        $result=array_merge($result,array("redemption-currency" => "Missing and required" ) );
        $field_control = false;
    }

    $redemption_interest_rate =  strtoupper((isset($_POST['redemption-interest-rate']) ? strip_tags($_POST['redemption-interest-rate']) : 0));
    // if(empty($redemption_interest_rate)){
    //     // $result=array_merge($result,array("redemption-interest-rate" => "Missing and required" ) );
    //     // $field_control = false;
    // }

    $redemption_value_date = (isset($_POST['redemption-value-date']) ? strip_tags($_POST['redemption-value-date']) : '');
    if(empty($redemption_value_date)){
        $result=array_merge($result,array("redemption-value-date" => "Missing and required" ) );
        $field_control = false;
    }

    $redemption_trade_date = (isset($_POST['redemption-trade-date']) ? strip_tags($_POST['redemption-trade-date']) : '');
    if(empty($redemption_trade_date)){
        $redemption_trade_date = '1970-01-01';
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_redemption = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_redemption->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_redemption->connect_error);
        exit();
    }

        $query_redemption = "INSERT INTO    `private_placements_redemptions`    (`redemption-id`,
                                                                                `fund-abbr`,
                                                                                `redemption-amount`,
                                                                                `redemption-price`,
                                                                                `redemption-currency`,
                                                                                `redemption-trade-date`,
                                                                                `redemption-value-date`)
                                        VALUE       ('".$redemption_id."','".$fund_abbr."','".$redemption_amount."','".$redemption_price."','".$redemption_currency."','".$redemption_trade_date."','".$redemption_value_date."')";

        if (!$mysqli_redemption->query($query_redemption)) {
            printf("Errormessage: %s\n", $mysqli_redemption->error);
            echo(json_encode( array("error" => "DB request failed") ));
        }

    $log_data =  $_POST['redemption-id'].' , '.$_POST['fund-abbr'].' , '.$_POST['redemption-amount'].' , '.$_POST['redemption-currency'].' , '.$_POST['redemption-price'].' , '.$_POST['redemption-value-date'].' , '.$_POST['redemption-trade-date'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE REDEMPTION", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Redemption has been added" ) );

    echo( json_encode($result) );
?>
