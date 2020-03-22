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

    $fund_abbr_from = (isset($_POST['fund-abbr-from']) ? strip_tags($_POST['fund-abbr-from']) : '');
    if(empty($fund_abbr_from)){
        $result=array_merge($result,array("fund-abbr-from" => "Missing and required" ) );
        $field_control = false;
    }
    $fund_from_currency = (isset($_POST['fund-from-currency']) ? strip_tags($_POST['fund-from-currency']) : '');
    if(empty($fund_from_currency)){
        $result=array_merge($result,array("fund-from-currency" => "Missing and required" ) );
        $field_control = false;
    }

    $fund_abbr_to = (isset($_POST['fund-abbr-to']) ? strip_tags($_POST['fund-abbr-to']) : '');
    if(empty($fund_abbr_to)){
        $result=array_merge($result,array("fund-abbr-to" => "Missing and required" ) );
        $field_control = false;
    }
    $fund_to_currency = (isset($_POST['fund-to-currency']) ? strip_tags($_POST['fund-to-currency']) : '');
    if(empty($fund_to_currency)){
        $result=array_merge($result,array("fund-to-currency" => "Missing and required" ) );
        $field_control = false;
    }

    $interfund_amount = (isset($_POST['interfund-amount']) ? strip_tags($_POST['interfund-amount']) : '');
    if(empty($interfund_amount)){
        $result=array_merge($result,array("interfund-amount" => "Missing and required" ) );
        $field_control = false;
    }
    $interfund_currency = (isset($_POST['interfund-currency']) ? strip_tags($_POST['interfund-currency']) : '');
    if(empty($interfund_currency)){
        $result=array_merge($result,array("interfund-currency" => "Missing and required" ) );
        $field_control = false;
    }

    $interfund_interest_rate = (isset($_POST['interfund-interest-rate']) ? strip_tags($_POST['interfund-interest-rate']) : '');
    if(empty($interfund_interest_rate)){
        $result=array_merge($result,array("interfund-interest-rate" => "Missing and required" ) );
        $field_control = false;
    }

    $interfund_value_date = (isset($_POST['interfund-value-date']) ? strip_tags($_POST['interfund-value-date']) : '');
    if(empty($interfund_value_date)){
        $result=array_merge($result,array("interfund-value-date" => "Missing and required" ) );
        $field_control = false;
    }
    $interfund_trade_date = (isset($_POST['interfund-trade-date']) ? strip_tags($_POST['interfund-trade-date']) : '');
    if(empty($interfund_trade_date)){
        $result=array_merge($result,array("interfund-trade-date" => "Missing and required" ) );
        $field_control = false;
    }

    if( !$field_control ){
        header("HTTP/1.1 422 - Unprocessable Entity");
        $result=array_merge($result,array("result" => "error" ) );
        echo( json_encode($result) );
        return;
    }

    $mysqli_interfunds = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if ($mysqli_interfunds->connect_errno) {
        echo(json_encode( array("error" => "DB connexion failed") ));
        printf("Connect failed: %s\n", $mysqli_interfunds->connect_error);
        return;
    }

    $query_interfunds = "INSERT INTO `interfunds` (`interfund-id`,`fund-abbr-from`,`fund-from-currency`,`fund-abbr-to`,`fund-to-currency`,`interfund-amount`,`interfund-currency`,`interfund-interest-rate`,`interfund-value-date`,`interfund-trade-date`)
                                    VALUE       (NULL,'".$fund_abbr_from."','".$fund_from_currency."','".$fund_abbr_to."','".$fund_to_currency."','".$interfund_amount."','".$interfund_currency."','".$interfund_interest_rate."','".$interfund_value_date."','".$interfund_trade_date."')";

    if (!$mysqli_interfunds->query($query_interfunds)) {
        printf("Error message: %s\n", $mysqli_interfunds->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
    }
    $mysqli_interfunds->close();

    $log_data =  $fund_abbr_to." (".$fund_from_currency.") => ".$fund_abbr_to." (".$fund_to_currency.") / ".$interfund_amount." ".$interfund_currency." / ".$interfund_interest_rate." % / ".$interfund_value_date." / ".$interfund_trade_date;

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE NEW INTERFUNDS OPERATION", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Interfund operation has been added" ) );

    echo( json_encode($result) );
?>
