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
        $result=array_merge($result,array("message" => "invoice_id is missing" ) );
        echo( json_encode($result) );
        return;
    }

    // CONTROLER QUE L'IDENTIFIANT EST BIEN PRESENT DANS LA TABLE !!!!!

    $redis = new Redis(); 
    $redis->connect('redis', 6379);

    if( $redis->exists( $url[2] ) ){
    	echo( $redis->get( $url[2] ) );
    	$redis->close();
    	return;
    }

    // CALCUL
    $query_invoice_lines = "SELECT `invoice-line-amount-due` FROM invoice_lines WHERE `invoice-id`='".$url[2]."' AND `invoice-line-amount-due` != '0' ";

    $conn_invoice_lines = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    $invoice_lines = mysqli_query($conn_invoice_lines, $query_invoice_lines);

    $invoice_total_amount_due=0;
    while($line = $invoice_lines->fetch_assoc()) {
        $invoice_total_amount_due += $line['invoice-line-amount-due'];
    }

    $invoice_lines->close();
    mysqli_close($conn_invoice_lines);


    // AJOUT DANS REDIS
	$string = '{'.
		'"invoice-id": "'.$url[2].'",'.
        '"invoice-total-amount-due": "'.$invoice_total_amount_due.'"'
		.'}';

	$redis->set($url[2], $string);
	$redis->close();

    $result=array_merge($result,array("success" => true ) );
    $result=array_merge($result,array("invoice-id" => $url[2] ) );
    $result=array_merge($result,array("invoice-total-amount-due" => $invoice_total_amount_due ) );

    echo( json_encode($result) );
?>