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


	$loan_fund = (isset($_POST['fund']) ? strip_tags($_POST['fund']) : '');
	if(empty($loan_fund)){
		$result=array_merge($result,array("loan_fund" => "Missing and required" ) );
	    $field_control = false;
	}



	// LOAN TYPE


	$loan_facility_type = (isset($_POST['facility-type']) ? strip_tags($_POST['facility-type']) : '');
	if(empty($loan_facility_type)){
		$result=array_merge($result,array("loan_facility_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_risk_level = (isset($_POST['loan-risk-level']) ? strip_tags($_POST['loan-risk-level']) : 'NULL');
	if(empty($loan_risk_level)){
		$result=array_merge($result,array("loan_risk_level" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_operation_type = (isset($_POST['loan-operation-type']) ? strip_tags($_POST['loan-operation-type']) : 'NULL');
	if(empty($loan_operation_type)){
		$result=array_merge($result,array("loan_operation_type" => "Missing and required" ) );
	    $field_control = false;
	}

	// $loan_full_roll_over = (isset($_POST['loan-full-roll-over']) ? strip_tags($_POST['loan-full-roll-over']) : '');



	// DUE DATE

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

	$loan_currency = (isset($_POST['loan-currency']) ? strip_tags($_POST['loan-currency']) : '');
	if(empty($loan_currency)){
		$result=array_merge($result,array("loan_currency" => "Missing and required" ) );
	    $field_control = false;
	}

	$interest_rate = (isset($_POST['interest-rate']) ? strip_tags($_POST['interest-rate']) : '');
	if(empty($interest_rate)){
		$result=array_merge($result,array("interest_rate" => "Missing and required" ) );
	    $field_control = false;
	}

	$interest_rate_type = (isset($_POST['interest-rate-type']) ? strip_tags($_POST['interest-rate-type']) : '');
	if(empty($interest_rate_type)){
		$result=array_merge($result,array("interest_rate_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$drawdown_fee = $_POST['drawdown-fee'];
	$drawdown_fee_rate = $_POST['drawdown-fee-rate'];
	$minimum_facility_fee = $_POST['minimum-facility-fee'];

	$banking_fee = (isset($_POST['banking-fee']) ? strip_tags($_POST['banking-fee']) : '');
	if(empty($banking_fee)){
		$result=array_merge($result,array("banking_fee" => "Missing and required" ) );
	    $field_control = false;
	}

	$penality_fee = $_POST['penality-fee'];
	$success_fee = $_POST['success-fee'];

	$loan_ltv = (isset($_POST['loan-ltv']) ? strip_tags($_POST['loan-ltv']) : '');
	if(empty($loan_ltv)){
		$result=array_merge($result,array("loan_ltv" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_ltv_type = (isset($_POST['loan-ltv-type']) ? strip_tags($_POST['loan-ltv-type']) : '');
	if(empty($loan_ltv_type)){
		$result=array_merge($result,array("loan_ltv_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$commodity = (isset($_POST['commodity']) ? strip_tags($_POST['commodity']) : '');
	if(empty($commodity)){
		$result=array_merge($result,array("commodity" => "Missing and required" ) );
	    $field_control = false;
	}

	$commodity_type = (isset($_POST['commodity-type']) ? strip_tags($_POST['commodity-type']) : '');
	if(empty($commodity_type)){
		$result=array_merge($result,array("commodity_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_origin_country = (isset($_POST['loan-origin-country']) ? strip_tags($_POST['loan-origin-country']) : '');
	$loan_place_of_storage = (isset($_POST['loan-place-of-storage']) ? strip_tags($_POST['loan-place-of-storage']) : '');
	$loan_destination = (isset($_POST['loan-destination']) ? strip_tags($_POST['loan-destination']) : '');
	$loan_country_of_incorporation = (isset($_POST['loan-country-of-incorporation']) ? strip_tags($_POST['loan-country-of-incorporation']) : '');

	$loan_purchase_incoterm_type = (isset($_POST['loan-purchase-incoterm-type']) ? strip_tags($_POST['loan-purchase-incoterm-type']) : '');
	if(empty($loan_purchase_incoterm_type)){
		$result=array_merge($result,array("loan_purchase_incoterm_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_purchase_incoterm_city = (isset($_POST['loan-purchase-incoterm-city']) ? strip_tags($_POST['loan-purchase-incoterm-city']) : '');
	$loan_purchase_incoterm_country = (isset($_POST['loan-purchase-incoterm-country']) ? strip_tags($_POST['loan-purchase-incoterm-country']) : '');

	$loan_sales_incoterm_type = (isset($_POST['loan-sales-incoterm-type']) ? strip_tags($_POST['loan-sales-incoterm-type']) : '');
	if(empty($loan_sales_incoterm_type)){
		$result=array_merge($result,array("loan_sales_incoterm_type" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_sales_incoterm_city = (isset($_POST['loan-sales-incoterm-city']) ? strip_tags($_POST['loan-sales-incoterm-city']) : '');
	$loan_sales_incoterm_country = (isset($_POST['loan-sales-incoterm-country']) ? strip_tags($_POST['loan-sales-incoterm-country']) : '');

	$loan_purchase_payment_term = (isset($_POST['loan-purchase-payment-term']) ? strip_tags($_POST['loan-purchase-payment-term']) : '');
	if(empty($loan_purchase_payment_term)){
		$result=array_merge($result,array("loan_purchase_payment_term" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_sales_payment_term = (isset($_POST['loan-sales-payment-term']) ? strip_tags($_POST['loan-sales-payment-term']) : '');
	if(empty($loan_sales_payment_term)){
		$result=array_merge($result,array("loan_sales_payment_term" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_supplier_name = (isset($_POST['loan-supplier-name']) ? strip_tags($_POST['loan-supplier-name']) : '');
	if(empty($loan_supplier_name)){
		$result=array_merge($result,array("loan_supplier_name" => "Missing and required" ) );
	    $field_control = false;
	}

	$loan_supplier_country = (isset($_POST['loan-supplier-country']) ? strip_tags($_POST['loan-supplier-country']) : '');



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

    // $query_loan = "INSERT INTO `loans`
    //			VALUE ('".$loan_id."','".$company_abbr."','".$loan_fund."','".$loan_currency."','".$loan_facility_type."','".$loan_value."','".$loan_initial_due_date."','".$interest_rate."','".$interest_rate_type."','".$drawdown_fee."','".$drawdown_fee_rate."','".$minimum_facility_fee."','".$banking_fee."','".$penality_fee."','".$success_fee."','".$loan_ltv."','".$loan_ltv_type."','".$loan_purchase_incoterm_type."','".$loan_purchase_incoterm_city."','".$loan_purchase_incoterm_country."','".$loan_purchase_payment_term."','".$loan_sales_incoterm_type."','".$loan_sales_incoterm_city."','".$loan_sales_incoterm_country."','".$loan_sales_payment_term."','".$commodity."','".$commodity_type."','".$loan_origin_country."','".$loan_place_of_storage."','".$loan_destination."','".$loan_country_of_incorporation."','".$loan_supplier_name."','".$loan_supplier_country."','".$loan_buyer_name."','".$loan_buyer_country."','".$loan_risk_level."','".$loan_operation_type."','".$loan_full_roll_over."')";

    $query_loan = "INSERT INTO `loans`
    			VALUE ('".$loan_id."','".$company_abbr."','".$loan_fund."','".$loan_currency."','".$loan_facility_type."','".$loan_value."','".$loan_initial_due_date."','".$interest_rate."','".$interest_rate_type."','".$drawdown_fee."','".$drawdown_fee_rate."','".$minimum_facility_fee."','".$banking_fee."','".$penality_fee."','".$success_fee."','".$loan_ltv."','".$loan_ltv_type."','".$loan_purchase_incoterm_type."','".$loan_purchase_incoterm_city."','".$loan_purchase_incoterm_country."','".$loan_purchase_payment_term."','".$loan_sales_incoterm_type."','".$loan_sales_incoterm_city."','".$loan_sales_incoterm_country."','".$loan_sales_payment_term."','".$commodity."','".$commodity_type."','".$loan_origin_country."','".$loan_place_of_storage."','".$loan_destination."','".$loan_country_of_incorporation."','".$loan_supplier_name."','".$loan_supplier_country."','".$loan_buyer_name."','".$loan_buyer_country."','".$loan_risk_level."','".$loan_operation_type."')";

	if (!$mysqli_loan->query($query_loan)) {
        printf("Error message: %s\n", $mysqli_loan->error);
        echo(json_encode( array("error" => "DB request failed") ));
        return;
	}
	$mysqli_loan->close();




    $log_data =  $_POST['loan-id'].' , '.$_POST['company-abbr'].' , '.$_POST['fund'].' , '.$_POST['loan-currency'].' , '.$_POST['facility-type'].' , '.$_POST['loan-amount'].' , '.$_POST['loan-initial-due-date'].' , '.$_POST['interest-rate'].' , '.$_POST['interest-rate-type'].' , '.$_POST['minimum-facility-fee'].' , '.$_POST['banking-fee'].' , '.$_POST['penality-fee'].' , '.$_POST['success-fee'].' , '.$_POST['loan-ltv'].' , '.$_POST['loan-ltv-type'].' , '.$_POST['commodity'].' , '.$_POST['commodity-type'].' , '.$_POST['loan-origin-country'].' , '.$_POST['loan-place-of-storage'].' , '.$_POST['loan-destination'].' , '.$_POST['loan-country-of-incorporation'].' , '.$_POST['loan-purchase-incoterm-type'].' , '.$_POST['loan-purchase-incoterm-city'].' , '.$_POST['loan-purchase-incoterm-country'].' , '.$_POST['loan-sales-incoterm-type'].' , '.$_POST['loan-sales-incoterm-city'].' , '.$_POST['loan-sales-incoterm-country'].' , '.$_POST['loan-purchase-payment-term'].' , '.$_POST['loan-sales-payment-term'].' , '.$_POST['loan-supplier-name'].' , '.$_POST['loan-supplier-country'].' , '.$_POST['loan-buyer-name'].' , '.$_POST['loan-buyer-country'];

    db_log( $_SERVER['HTTP_APIKEY'], "CREATE NEW LOAN", $log_data);

    $result=array_merge($result,array("result" => "success" ) );
    $result=array_merge($result,array("message" => "Loan has been added" ) );

    echo( json_encode($result) );

?>
