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

    // S'il n'y a qu'une seule redemption recherchée.
    if( isset($url[1]) && !empty($url[1])){
        $query = "SELECT * FROM `private_placements_redemptions` WHERE `redemption-id` = '".str_replace("-", "/", $url[1])."' LIMIT 1;";

        $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
        if (!$conn) {
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "DB connexion failed" ) );
            echo( json_encode($result) );
            return;
        }

        $redemptions = mysqli_query($conn, $query);
        $redemption = mysqli_fetch_array($redemptions,MYSQLI_ASSOC);

        if (empty($redemption)) {
            header("HTTP/1.1 404 Not Found");
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "Unknown redemption in DB" ) );
            $result=array_merge($result,array("SQL Request" => $query ) );
            echo( json_encode($result) );
            return;
        }

        $redemptions->close();
        mysqli_close($conn);

        $result=array();
        $result=array_merge($result,array("success" => true ) );
        $result=array_merge($result,$redemption );
        echo( json_encode($result) );
        return;
    }

    $query = 'SELECT * FROM `private_placements_redemptions`';
    $path = explode( "/redemptions/", utf8_decode(urldecode($_SERVER['REQUEST_URI'])) );

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

    $redemptions = mysqli_query($conn, $query);

    if (empty($redemptions)) {
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "Incorrect Parameters" ) );
        $result=array_merge($result,array("SQL Request" => $query ) );
        $result=array_merge($result,array("SQL Request" => $query ) );
        echo( json_encode($result) );
        return;
    }

    echo( json_encode( mysqli_fetch_all($redemptions,MYSQLI_ASSOC) ));

    $redemptions->close();
    mysqli_close($conn);
?>
