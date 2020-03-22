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

    error_reporting(0);
    $result=array();

	//Connecting to Redis server on localhost
	$redis = new Redis();
	try{
		$redis->connect('redis', 6379);

		$redis->flushAll();

		$redis->close();
	}
    catch (Exception $e) {
        header("HTTP/1.1 401 - Unauthorized");
        echo(json_encode( array("error" => $e->getMessage()) ));
        return;
    }

    // echo( json_encode( mysqli_fetch_all($clients,MYSQLI_ASSOC) ));
    $result=array_merge($result,array("success" => true ) );
    echo( json_encode( array("success" => true ) ));
?>