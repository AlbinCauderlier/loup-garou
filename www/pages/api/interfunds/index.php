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

    $url = explode("/", $_GET['p2']);
    $result=array();

    // S'il n'y a qu'un seul interfund recherché.
    if( isset($url[1]) && !empty($url[1])){
        $query = "SELECT * FROM `interfunds` WHERE `interfund-id` = '".$url[1]."' LIMIT 1;";

        $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
        if (!$conn) {
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "DB connexion failed" ) );
            echo( json_encode($result) );
            return;
        }

        $interfunds = mysqli_query($conn, $query);
        $interfund = mysqli_fetch_array($interfunds,MYSQLI_ASSOC);

        if( empty($interfund) ) {
            header("HTTP/1.1 404 Not Found");
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "Unknown interfund in DB" ) );
            $result=array_merge($result,array("SQL Request" => $query ) );
            echo( json_encode($result) );
            return;
        }

        $interfunds->close();
        mysqli_close($conn);

        $result=array();
        $result=array_merge($result,array("success" => true ) );
        $result=array_merge($result,$interfund );
        echo( json_encode($result) );
        return;
    }

    $query = 'SELECT * FROM `interfunds`';
    $path = explode( "/interfunds/", utf8_decode(urldecode($_SERVER['REQUEST_URI'])) );

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters_list = explode( "&", $path[1] );
        $query .= ' WHERE ';

        foreach( $parameters_list as $parameter ){
            $tmp = explode("=",$parameter );

            if( $tmp[0][0] == "?" ){
                $query .= "`".ltrim( $tmp[0], $tmp[0][0])."` LIKE '".$tmp[1]."' ";
            }
            else{
                $query .= " AND `".$tmp[0]."` LIKE '".$tmp[1]."' ";
            }
        }
    }
    $query .= " ;";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if (!$conn) {
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "DB connexion failed" ) );
        echo( json_encode($result) );
        return;
    }

    $interfunds = mysqli_query($conn, $query);

    if (empty($interfunds)) {
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "Incorrect Parameters" ) );
        $result=array_merge($result,array("SQL Request" => $query ) );
        echo( json_encode($result) );
        return;
    }

    echo( json_encode( mysqli_fetch_all($interfunds,MYSQLI_ASSOC) ) );

    $interfunds->close();
    mysqli_close($conn);
?>
