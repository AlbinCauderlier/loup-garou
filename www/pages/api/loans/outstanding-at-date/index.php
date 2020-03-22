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

    // S'il n'y a qu'un seul loan recherché.
    if( !isset($url[2]) || empty($url[2])){
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "Missing date" ) );
        echo( json_encode($result) );
        return;
    }

    $date = explode("-", $url[2] );

    // TODO - Controler qu'il s'agit bien d'une date + date après 2018-01-01 ... sinon, Incorrect Parameter
    // if (empty($loans)) {
    //     $result=array_merge($result,array("success" => false ) );
    //     $result=array_merge($result,array("message" => "Incorrect Parameters" ) );
    //     $result=array_merge($result,array("SQL Request" => $query ) );
    //     echo( json_encode($result) );
    //     return;
    // }


    $query = "SELECT * FROM `loans`";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    if (!$conn) {
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "DB connexion failed" ) );
        echo( json_encode($result) );
        return;
    }

    $loans_result = mysqli_query($conn, $query);

    if (empty($loans_result)) {
        header("HTTP/1.1 404 Not Found");
        $result=array_merge($result,array("success" => false ) );
        $result=array_merge($result,array("message" => "No loan in DB" ) );
        $result=array_merge($result,array("SQL Request" => $query ) );
        echo( json_encode($result) );
        return;
    }

    $loans = mysqli_fetch_all($loans_result,MYSQLI_ASSOC);
    $loans_result->close();

    foreach( $loans as $loan ){
        // Compare l'année de la date avec celle de l'ID du Loan
        $loan_id_array = explode("/", $loan['loan-id'] );
        if( $loan_id_array[3] > $date[0] ){
            continue;
        }

        if( strpos( $loan['loan-id'], " ") !== false ){
            continue;
        }

        $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace("/","-",$loan['loan-id']).'/'), true);

        if( direct_date_compare( $loan_status['loan-first-drawdown-date'] , $url[2] ) > 0 ){
            continue;
        }

        $query_drawdowns = "SELECT SUM(`drawdown-value`) FROM `drawdowns` WHERE `drawdowns`.`loan-id`='".$loan['loan-id']."'
                                                                            AND `drawdowns`.`drawdown-date` <= '".$url[2]."'";
        $query_repaids = "SELECT SUM(`repaid-value`) FROM `repaids` WHERE `repaids`.`loan-id`='".$loan['loan-id']."'
                                                                        AND `repaids`.`repaid-date` <= '".$url[2]."'";

        // Récupérer la somme des Drawdowns
        $drawdowns = mysqli_query($conn, $query_drawdowns);
        $sum_drawdowns = mysqli_fetch_array($drawdowns,MYSQLI_ASSOC);
        $drawdowns->close();

        // Récupérer la somme des Repaids
        $repaids = mysqli_query($conn, $query_repaids);
        $sum_repaids = mysqli_fetch_array($repaids,MYSQLI_ASSOC);
        $repaids->close();


        $loan_outstanding_at_date = $sum_drawdowns['SUM(`drawdown-value`)'] - $sum_repaids['SUM(`repaid-value`)'];


        if( $loan_outstanding_at_date == 0 ){
            continue;
        }

        $loan['first_drawdown_date'] = $loan_status['loan-first-drawdown-date'];
        $loan['loan_outstanding_amount_at_date'] = $loan_outstanding_at_date;

        $result[] = $loan;
    }

    mysqli_close($conn);

    echo( json_encode($result) );
    return;
?>
