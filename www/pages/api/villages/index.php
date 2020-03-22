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

    // S'il n'y a qu'un seul user recherché.
    if( isset($url[1]) && !empty($url[1])){
        $query = "SELECT * FROM `villages` WHERE `village-id` = '".$url[1]."' LIMIT 1;";

        $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"loups-garous-users");
        if (!$conn) {
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "DB connexion failed" ) );
            echo( json_encode($result) );
            return;
        }

        $users = mysqli_query($conn, $query);
        $user = mysqli_fetch_array($users,MYSQLI_ASSOC);

        if (empty($user)) {
            header("HTTP/1.1 404 Not Found");
            $result=array_merge($result,array("success" => false ) );
            $result=array_merge($result,array("message" => "Unknown user in DB" ) );
            $result=array_merge($result,array("SQL Request" => $query ) );
            echo( json_encode($result) );
            return;
        }

        $users->close();
        mysqli_close($conn);

        $result=array();
        $result=array_merge($result,array("success" => true ) );
        $result=array_merge($result,$user );
        echo( json_encode($result) );
        return;
    }

    $query = 'SELECT * FROM `villages`';
    $path = explode( "/villages/", utf8_decode(urldecode($_SERVER['REQUEST_URI'])) );

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
        echo(json_encode( array("error" => "DB connexion failed") ));
        return;
    }

    $villages = mysqli_query($conn, $query);

    if (empty($villages)) {
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "Incorrect Parameters" ) );
        $result=array_merge($result,array("SQL Request" => $query ) );
        echo( json_encode($result) );
        return;
    }

    echo( json_encode( mysqli_fetch_all($villages,MYSQLI_ASSOC) ) );

    $villages->close();
    mysqli_close($conn);
?>
