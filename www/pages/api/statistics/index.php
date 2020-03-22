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
    $result = array();

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);


    $query = 'SELECT COUNT(*) FROM `clients`;';
    $clients = mysqli_query($conn, $query);
    $client_number = mysqli_fetch_assoc($clients);
    $result = array_merge($result,array("success" => true ) );
    $result = array_merge($result, array('clients' => $client_number['COUNT(*)']));
    $clients->close();

    $query = 'SELECT COUNT(*) FROM `drawdowns`;';
    $drawdowns = mysqli_query($conn, $query);
    $drawdown_number= mysqli_fetch_assoc($drawdowns);
    $result = array_merge($result, array('drawdowns' => $drawdown_number['COUNT(*)']));
    $drawdowns->close();

    $query = 'SELECT COUNT(*) FROM `facility_agreements`;';
    $facility_agreements = mysqli_query($conn, $query);
    $facility_agreements_number = mysqli_fetch_assoc($facility_agreements);
    $result = array_merge($result, array('facility-agreements' => $facility_agreements_number['COUNT(*)']));
    $facility_agreements->close();

    $query = 'SELECT COUNT(*) FROM `funds`;';
    $funds = mysqli_query($conn, $query);
    $funds_number = mysqli_fetch_assoc($funds);
    $result = array_merge($result, array('funds' => $funds_number['COUNT(*)']));
    $funds->close();

    $query = 'SELECT COUNT(*) FROM `ibans`;';
    $ibans = mysqli_query($conn, $query);
    $ibans_number = mysqli_fetch_assoc($ibans);
    $result = array_merge($result, array('ibans' => $ibans_number['COUNT(*)']));
    $ibans->close();

    $query = 'SELECT COUNT(*) FROM `invoice_lines`;';
    $invoice_lines = mysqli_query($conn, $query);
    $invoice_lines_number = mysqli_fetch_assoc($invoice_lines);
    $result = array_merge($result, array('invoice-lines' => $invoice_lines_number['COUNT(*)']));
    $invoice_lines->close();

    $query = 'SELECT COUNT(*) FROM `invoices`;';
    $invoices = mysqli_query($conn, $query);
    $invoices_number = mysqli_fetch_assoc($invoices);
    $result = array_merge($result, array('invoices' => $invoices_number['COUNT(*)']));
    $invoices->close();

    $query = 'SELECT COUNT(*) FROM `events`;';
    $events = mysqli_query($conn, $query);
    $events_number = mysqli_fetch_assoc($events);
    $result = array_merge($result, array('events' => $events_number['COUNT(*)']));
    $events->close();

    $query = 'SELECT COUNT(*) FROM `loans`;';
    $loans = mysqli_query($conn, $query);
    $loans_number = mysqli_fetch_assoc($loans);
    $result = array_merge($result, array('loans' => $loans_number['COUNT(*)']));
    $loans->close();

    $query = 'SELECT COUNT(*) FROM `private_placements_redemptions`;';
    $redemptions = mysqli_query($conn, $query);
    $redemptions_number = mysqli_fetch_assoc($redemptions);
    $result = array_merge($result, array('redemptions' => $redemptions_number['COUNT(*)']));
    $redemptions->close();

    $query = 'SELECT COUNT(*) FROM `repaids`;';
    $repaids = mysqli_query($conn, $query);
    $repaids_number = mysqli_fetch_assoc($repaids);
    $result = array_merge($result, array('repaids' => $repaids_number['COUNT(*)']));
    $repaids->close();

    $query = 'SELECT COUNT(*) FROM `private_placements_subscriptions`;';
    $subscriptions = mysqli_query($conn, $query);
    $subscriptions_number = mysqli_fetch_assoc($subscriptions);
    $result = array_merge($result, array('subscriptions' => $subscriptions_number['COUNT(*)']));
    $subscriptions->close();

    $query = 'SELECT COUNT(*) FROM `users-data`;';
    $conn_users = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,'iris_users');
    $users = mysqli_query($conn_users, $query);
    $users_number = mysqli_fetch_assoc($users);
    $result = array_merge($result, array('users' => $users_number['COUNT(*)']));
    $users->close();
    mysqli_close($conn_users);

    $path = explode( "/statistics/", utf8_decode(urldecode($_SERVER['REQUEST_URI'])) );
    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters = explode( "=", $path[1] );
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `repaids` WHERE `loan-id` LIKE '%/".$parameters[1]."' ";
            $repaids = mysqli_query($conn, $query);
            $repaids_number = mysqli_fetch_assoc($repaids);
            $result = array_merge($result, array('repaids' => $repaids_number['COUNT(*)']));
            $repaids->close();
        } else {
            $parameters = explode( "=", $path[1] );
            $days = $parameters[1];
            $duration = (date('Y-m-d', strtotime('-'.$days.' day')));
            $query = 'SELECT COUNT(*) FROM `repaids` WHERE `repaid-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $repaids = mysqli_query($conn, $query);
            $repaids_number = mysqli_fetch_assoc($repaids);
            $result = array_merge($result, array('repaids' => $repaids_number['COUNT(*)']));
            $repaids->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters = explode( "=", $path[1] );
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `loans` WHERE `loan-id` LIKE '%/".$parameters[1]."' ";
            $loans = mysqli_query($conn, $query);
            $loans_number = mysqli_fetch_assoc($loans);
            $result = array_merge($result, array('loans' => $loans_number['COUNT(*)']));
            $loans->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters = explode( "=", $path[1] );
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `drawdowns` WHERE `loan-id` LIKE '%/".$parameters[1]."' ";
            $drawdowns = mysqli_query($conn, $query);
            $drawdowns_number = mysqli_fetch_assoc($drawdowns);
            $result = array_merge($result, array('drawdowns' => $drawdowns_number['COUNT(*)']));
            $drawdowns->close();
        } else {
            $days = $parameters[1];
            $duration = (date('Y-m-d', strtotime('-'.$days.' day')));
            $query = 'SELECT COUNT(*) FROM `drawdowns` WHERE `drawdown-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $drawdowns = mysqli_query($conn, $query);
            $drawdowns_number = mysqli_fetch_assoc($drawdowns);
            $result = array_merge($result, array('drawdowns' => $drawdowns_number['COUNT(*)']));
            $drawdowns->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters = explode( "=", $path[1] );
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `invoices` WHERE `invoice-date` LIKE '".$parameters[1]."-%' ";
            $invoices = mysqli_query($conn, $query);
            $invoices_number = mysqli_fetch_assoc($invoices);
            $result = array_merge($result, array('invoices' => $invoices_number['COUNT(*)']));
            $invoices->close();
        } else {
            $days = $parameters[1];
            $duration = (date('Y-m-d', strtotime('-'.$days.' day')));
            $query = 'SELECT COUNT(*) FROM `invoices` WHERE `invoice-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $invoices = mysqli_query($conn, $query);
            $invoices_number = mysqli_fetch_assoc($invoices);
            $result = array_merge($result, array('invoices' => $invoices_number['COUNT(*)']));
            $invoices->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        $parameters = explode( "=", $path[1] );
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `invoice_lines` WHERE `loan-id` LIKE '%/".$parameters[1]."' ";
            $invoice_lines = mysqli_query($conn, $query);
            $invoice_lines_number = mysqli_fetch_assoc($invoice_lines);
            $result = array_merge($result, array('invoice-lines' => $invoice_lines_number['COUNT(*)']));
            $invoice_lines->close();
        } else {
            $days = $parameters[1];
            $duration = (date('Y-m-d', strtotime('-'.$days.' day')));
            $query = 'SELECT COUNT(*) FROM `invoice_lines` WHERE `invoice-line-end-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $invoice_lines = mysqli_query($conn, $query);
            $invoice_lines_number = mysqli_fetch_assoc($invoice_lines);
            $result = array_merge($result, array('invoice-lines' => $invoice_lines_number['COUNT(*)']));
            $invoice_lines->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `facility_agreements` WHERE `facility-agreement-date` LIKE '".$parameters[1]."-%' ";
            $facility_agreements = mysqli_query($conn, $query);
            $facility_agreements_number = mysqli_fetch_assoc($facility_agreements);
            $result = array_merge($result, array('facility-agreements' => $facility_agreements_number['COUNT(*)']));
            $facility_agreements->close();
        } else {
            $parameters = explode( "=", $path[1] );
            $days = $parameters[1];
            $duration = date('Y-m-d', strtotime('-'.$days.' day'));
            $query = 'SELECT COUNT(*) FROM `facility_agreements` WHERE `facility-agreement-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $facility_agreements = mysqli_query($conn, $query);
            $facility_agreements_number = mysqli_fetch_assoc($facility_agreements);
            $result = array_merge($result, array('facility-agreements' => $facility_agreements_number['COUNT(*)']));
            $facility_agreements->close();
        }
    }

    if( isset( $path[1] ) && !empty( $path[1] ) ){
        if ($parameters[0] == '?year'){
            $query = "SELECT COUNT(*) FROM `private_placements_subscriptions` WHERE `subscription-value-date` LIKE '".$parameters[1]."-%' ";
            $subscriptions = mysqli_query($conn, $query);
            $subscriptions_number = mysqli_fetch_assoc($subscriptions);
            $result = array_merge($result, array('subscriptions' => $subscriptions_number['COUNT(*)']));
            $subscriptions->close();
        } else {
            $parameters = explode( "=", $path[1] );
            $days = $parameters[1];
            $duration = date('Y-m-d', strtotime('-'.$days.' day'));
            $query = 'SELECT COUNT(*) FROM `private_placements_subscriptions` WHERE `subscription-value-date` BETWEEN "'.$duration.'" AND "'.date("Y-m-d").'"';
            $subscriptions = mysqli_query($conn, $query);
            $subscriptions_number = mysqli_fetch_assoc($subscriptions);
            $result = array_merge($result, array('subscriptions' => $subscriptions_number['COUNT(*)']));
            $subscriptions->close();
        }
    }


    // $loans = json_decode(callAPI('GET',API_URL.'/api/loans/'), true);
    // $count = 0;
    // foreach( $loans as $loan ){
    //     $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace("/","-",$loan['loan-id']).'/'), true);
    //     $path = explode( "/statistics/", $_SERVER['REQUEST_URI'] );
    //     if( isset( $path[1] ) && !empty( $path[1] ) ){
    //         $parameters = explode( "=", $path[1] );
    //         $days = $parameters[1];
    //         $duration = (date('Y-m-d', strtotime('-'.$days.' day')));
    //         if (direct_date_compare(date("Y-m-d"), $loan_status['loan-first-drawdown-date'])/60/60/24 <= 30){
    //             $count++;
    //             $result = array_merge($result, array('loans' => $count));
    //         }
    //     }
    // }

    mysqli_close($conn);

    echo(json_encode($result));
?>
