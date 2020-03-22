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



	$drawdown_value = (isset($_POST['drawdown-value']) ? strip_tags($_POST['drawdown-value']) : '');
	if(empty($drawdown_value)){
		$result=array_merge($result,array("drawdown_value" => "Missing and required" ) );
	    $field_control = false;
	}
	if( filter_var($drawdown_value, FILTER_VALIDATE_FLOAT) === false ) {
		$result=array_merge($result,array("drawdown_value" => "drawdown_value must be a float value") );
        $field_control = false;
	}


	$drawdown_currency =  strtoupper((isset($_POST['drawdown-currency']) ? strip_tags($_POST['drawdown-currency']) : ''));
	if(empty($drawdown_currency)){
		$result=array_merge($result,array("drawdown_currency" => "Missing and required" ) );
	    $field_control = false;
	}
	if(strlen($drawdown_currency) > 3){
		$result=array_merge($result,array("drawdown_currency" => "drawdown_currency is max 3 characters") );
        $field_control = false;
	}

	$drawdown_date = (isset($_POST['drawdown-date']) ? strip_tags($_POST['drawdown-date']) : '');
	if(empty($drawdown_date)){
		$result=array_merge($result,array("drawdown_date" => "Missing and required" ) );
	    $field_control = false;
	}




	if( !$field_control ){
		header("HTTP/1.1 422 - Unprocessable Entity");
		$result=array_merge($result,array("result" => "error" ) );
		echo( json_encode($result) );
	    return;
	}



	new_drawdown( $loan_id, $drawdown_value, $drawdown_currency, $drawdown_date );

	callAPI('GET',API_URL.'/api/get-loan/refresh-cache/'.str_replace("/","-",$loan_id).'/');

    $log_data =  $_POST['loan-id'].' , '.$_POST['drawdown-value'].' , '.$_POST['drawdown-currency'].' , '.$_POST['drawdown-date'].' , '.$_POST['credit-limit-amount'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE DRAWDOWN", $log_data);

	$result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Drawdown has been added" ) );

    echo( json_encode($result) );
    return;
?>
