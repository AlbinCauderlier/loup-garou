<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
	header('Content-Type: application/json');

    if( $_SERVER['REQUEST_METHOD'] != "GET"){
        header("HTTP/1.1 405 Method Not Allowed");
        echo(json_encode( array("error" => "GET Service asked with a ".$_SERVER['REQUEST_METHOD']." method.") ));
        return;
    }

    if( $_SERVER['HTTP_APIKEY'] != APIKEY ){
        header("HTTP/1.1 401 - Unauthorized");
        echo(json_encode( array("error" => "Wrong API Key") ));
        return;
    }
    
    $url = explode("/", $_GET['p2']);
    $result=array();

    if( !isset($url[2]) || empty($url[2])){
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "loan_id is missing" ) );
        echo( json_encode($result) );
        return;
    }

    $loan_id = str_replace("-","/",$url[2]);

    $redis = new Redis(); 
    $redis->connect('redis', 6379);

    if( $redis->exists( $loan_id ) ){
    	echo( $redis->get( $loan_id ) );
    	$redis->close();
    	return;
    }


    $drawdowns = json_decode(callAPI('GET',API_URL.'/api/drawdowns/?loan-id='.$loan_id), true);
    $repaids = json_decode(callAPI('GET',API_URL.'/api/repaids/?loan-id='.$loan_id), true);


    $first_drawndown_date = "1970-01-01";
    $first_drawndown_id = 0;
    $sum_drawdowns = 0;
    $sum_drawdowns_value_date = 0;

    foreach( $drawdowns as $drawdown ){
        if( direct_date_compare( $first_drawndown_date, $drawdown['drawdown-date'] ) < 0 ){
            $first_drawndown_date = $drawdown['drawdown-date'];
            $first_drawndown_id = $drawdown['drawdown-id'];
        }
        $sum_drawdowns += $drawdown['drawdown-value'];

        if( isset( $drawdown['drawdown-bpp-value-date'] ) ){
            $sum_drawdowns_value_date += $drawdown['drawdown-value'];
        }
    }

    $last_repaid_date = "1970-01-01";
    $last_repaid_id = 0;
    $total_repaid = 0;
    $total_repaid_value_date = 0;

    if( !$repaids ){
        $last_repaid_date = NULL;
        $last_repaid_date_string = "NULL";
    }
    else{
        foreach( $repaids as $repaid ){
            if( direct_date_compare( $last_repaid_date, $repaid['repaid-date'] ) < 0 ){
                $last_repaid_date = $repaid['repaid-date'];
                $last_repaid_id = $repaid['repaid-id'];
            }

            $total_repaid += $repaid['repaid-value'];

            if( isset( $repaid['repaid-bpp-value-date'] ) ){
                $total_repaid_value_date += $repaid['repaid-value'];
            }
        }
    
        $last_repaid_date_string = $last_repaid_date;
    }

    $loan_current_outstanding = round($sum_drawdowns - $total_repaid,2);
    $loan_current_outstanding_value_date = round($sum_drawdowns_value_date - $total_repaid_value_date,2);


    // AJOUT DANS REDIS
	$string = '{'.
		'"loan-outstanding-amount": "'.$loan_current_outstanding.'",'.
        '"loan-value-date-outstanding-amount": "'.$loan_current_outstanding_value_date.'",'.
		'"loan-first-drawdown-date": "'.$first_drawndown_date.'",'.
        '"loan-first-drawdown-id": "'.$first_drawndown_id.'",'.
		'"loan-last-repaid-date": "'.$last_repaid_date_string.'",'.
        '"loan-last-repaid-id": "'.$last_repaid_id.'",'.
		'"loan-sum-drawdowns": "'.$sum_drawdowns.'",'.
        '"loan-sum-drawdowns-value-date": "'.$sum_drawdowns_value_date.'",'.
		'"loan-sum-repaids": "'.$total_repaid.'",'.
        '"loan-sum-repaids-value-date": "'.$total_repaid_value_date.'"'
		.'}';

	$redis->set($loan_id, $string);
	$redis->close();

 
    $result=array_merge($result,array("success" => true ) );
    $result=array_merge($result,array("loan-id" => $loan_id ) );
    $result=array_merge($result,array("loan-outstanding-amount" => $loan_current_outstanding ) );
    $result=array_merge($result,array("loan-value-date-outstanding-amount" => $loan_current_outstanding_value_date ) );
    $result=array_merge($result,array("loan-first-drawdown-date" => $first_drawndown_date ));
    $result=array_merge($result,array("loan-first-drawdown-id" => $first_drawndown_id ));
    $result=array_merge($result,array("loan-last-repaid-date" => $last_repaid_date ));
    $result=array_merge($result,array("loan-last-repaid-id" => $last_repaid_id ));
    $result=array_merge($result,array("loan-sum-drawdowns" => $sum_drawdowns ) );
    $result=array_merge($result,array("loan-sum-drawdowns-value-date" => $sum_drawdowns_value_date ) );
    $result=array_merge($result,array("loan-sum-repaids" => $total_repaid ) );
    $result=array_merge($result,array("loan-sum-repaids-value-date" => $total_repaid_value_date ) );

    echo( json_encode($result) );
?>