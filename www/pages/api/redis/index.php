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

    // $url = explode("/", $_GET['p2']);
    $result=array();

    // // S'il n'y a qu'un seul client recherché.
    // if( isset($url[1]) && !empty($url[1])){
    //     $query = "SELECT * FROM `clients` WHERE `company-abbr` = '".$url[1]."' LIMIT 1;";

    //     $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    //     if (!$conn) {
    //         $result=array_merge($result,array("success" => false ) );
    //         $result=array_merge($result,array("message" => "DB connexion failed" ) );
    //         echo( json_encode($result) );
    //         return;
    //     }

    //     $clients = mysqli_query($conn, $query);
    //     $client = mysqli_fetch_array($clients,MYSQLI_ASSOC);

    //     if( empty( $client ) ){
    //         header("HTTP/1.1 404 Not Found");
    //         $result=array_merge($result,array("success" => false ) );
    //         $result=array_merge($result,array("message" => "Unknown client in DB" ) );
    //         $result=array_merge($result,array("SQL Request" => $query ) );
    //         echo( json_encode($result) );
    //         return;
    //     }

    //     $clients->close();
    //     mysqli_close($conn);

    //     $result=array();
    //     $result=array_merge($result,array("success" => true ) );
    //     $result=array_merge($result,$client );
    //     echo( json_encode($result) );
    //     return;
    // }

    // $query = 'SELECT * FROM `clients`';
    // $path = explode( "/clients/", utf8_decode(urldecode($_SERVER['REQUEST_URI'])) );

    // if( isset( $path[1] ) && !empty( $path[1] ) ){
    //     $parameters_list = explode( "&", $path[1] );
    //     $query .= ' WHERE ';

    //     foreach( $parameters_list as $parameter ){
    //         $tmp = explode("=",$parameter );

    //         if( $tmp[0][0] == "?" ){
    //             $query .= "`".ltrim( $tmp[0], $tmp[0][0])."` LIKE '".$tmp[1]."' ";
    //         }
    //         else{
    //             $query .= " AND `".$tmp[0]."` LIKE '".$tmp[1]."' ";
    //         }
    //     }
    // }
    // $query .= " ;";

    $redis = new Redis();
    try{
        $redis->connect('redis', 6379);

        //check whether server is running or not
        if( $redis->ping() == 1){

            $keyList = $redis->keys("*");
            foreach( $keyList as $key ){
                $result['data'][$key] = $redis->get($key);
            }
        }

        $redis->close();
    }
    catch (Exception $e) {
        header("HTTP/1.1 401 - Unauthorized");
        echo(json_encode( array("error" => $e->getMessage()) ));
        return;
    }

    // $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    // if (!$conn) {
    //     $result=array_merge($result,array("success" => false ) );
    //     $result=array_merge($result,array("message" => "DB connexion failed" ) );
    //     echo( json_encode($result) );
    //     return;
    // }

    // $clients = mysqli_query($conn, $query);

    // if (empty($clients)) {
    //     $result=array_merge($result,array("success" => false ) );
    //     $result=array_merge($result,array("message" => "Incorrect Parameters" ) );
    //     $result=array_merge($result,array("SQL Request" => $query ) );
    //     echo( json_encode($result) );
    //     return;
    // }

    // echo( json_encode( mysqli_fetch_all($clients,MYSQLI_ASSOC) ));
    $result=array_merge($result,array("success" => true ) );
    echo( json_encode( $result ));


    // $clients->close();
    // mysqli_close($conn);
?>
