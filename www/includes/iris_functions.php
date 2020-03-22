<?php

function does_an_invoice_exists( $invoice_id ){
    $invoice = json_decode(callAPI('GET',API_URL.'/api/invoices/'.$invoice_id.'/'), true);

    if( $invoice['success'] ){
        return true;
    }
    return false;
}

function get_loan_drawdown_fee_rate( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);

    // Exemple : Loan AFR/HRC/01/2018
    if( $loan['loan-drawdown-fee-rate'] == NULL){
        return 0;
    }
    return $loan['loan-drawdown-fee-rate'];
}

function get_loan_interest_rate_at_date( $loan_id, $date){
    $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);
    $result = $loan['loan-interest-rate'];

    $events = json_decode(callAPI('GET',API_URL.'/api/events/?loan-id='.str_replace('/','-',$loan_id).'&event-label=INTEREST_RATE'), true);

    if( !$events || empty( $events ) ){
        return $result;
    }

    // Trier les événements par date croissante
    usort($events, 'events_date_compare');

    foreach( $events as $event ){
        if( direct_date_compare( $event['event-date'], $date) <= 0 ){
            $result = $event['event-new-value'];
        }
    }

    return $result;
}

function get_loan_due_date( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);
    $result = $loan['loan-initial-due-date'];

    $events = json_decode(callAPI('GET',API_URL.'/api/events/?loan-id='.str_replace('/','-',$loan_id).'&event-label=DUE_DATE'), true);

    if( !$events || empty( $events ) ){
        return $result;
    }

    // Trier les événements par date croissante
    usort($events, 'events_date_compare');

    foreach( $events as $event ){
        $result = $event['event-new-due-date'];
    }

    return $result;
}

function get_loan_fund( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);
    return get_fund_invoice_abbr($loan['loan-fund']);
}

function get_loan_company_abbr( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);
    return $loan['company-abbr'];
}

function get_fund_invoice_abbr( $fund_abbr ){
    $fund = json_decode(callAPI('GET',API_URL.'/api/funds/'.$fund_abbr.'/'), true);
    return $fund['fund-invoice-abbr'];
}

function get_fund_abbr( $fund_invoice_abbr ){
    $funds = json_decode(callAPI('GET',API_URL.'/api/funds/?fund-invoice-abbr='.$fund_invoice_abbr), true);
    return $funds[0]['fund-abbr'];
}

function get_invoice_line_loan_id( $invoice_line_id ){
    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$invoice_line_id.'/'), true);
    return $invoice_line['loan-id'];
}

function get_invoice_line_invoice_id( $invoice_line_id ){
    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$invoice_line_id.'/'), true);
    return $invoice_line['invoice-id'];
}

function get_invoice_date( $invoice_id ){
    $invoice = json_decode(callAPI('GET',API_URL.'/api/invoices/'.$invoice_id.'/'), true);
    return $invoice['invoice-date'];
}

function get_invoice_status( $invoice_id ){
    $invoice = json_decode(callAPI('GET',API_URL.'/api/invoices/'.$invoice_id.'/'), true);
    return $invoice['invoice-status'];
}

function get_loan_total_drawdown( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan_id).'/'), true);
    return $loan['loan-sum-drawdowns'];
}

function get_loan_total_repaid( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan_id).'/'), true);
    return $loan['loan-sum-repaids'];
}

function get_loan_outstanding_amount( $loan_id ){
    $loan = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan_id).'/'), true);
    return $loan['loan-outstanding-amount'];
}

function get_loan_outstanding_amount_at_date( $loan_id, $date ){
    $loan_id = str_replace("-", "/", $loan_id);

    if( !$date ){
        $date = date("Y-m-d");
    }

    $query_drawdowns = "SELECT SUM(`drawdown-value`) FROM `drawdowns` WHERE `drawdowns`.`loan-id`='".$loan_id."'
                                                                        AND `drawdowns`.`drawdown-date` <= '".$date."'";
    $query_repaids = "SELECT SUM(`repaid-value`) FROM `repaids` WHERE `repaids`.`loan-id`='".$loan_id."'
                                                                    AND `repaids`.`repaid-date` <= '".$date."'";
    $conn_outstanding = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);

    // Récupérer la somme des Drawdowns
    $drawdowns = mysqli_query($conn_outstanding, $query_drawdowns);
    $sum_drawdowns = mysqli_fetch_array($drawdowns,MYSQLI_ASSOC);
    $drawdowns->close();

    // Récupérer la somme des Repaids
    $repaids = mysqli_query($conn_outstanding, $query_repaids);
    $sum_repaids = mysqli_fetch_array($repaids,MYSQLI_ASSOC);
    $repaids->close();

    mysqli_close($conn_outstanding);

    return $sum_drawdowns['SUM(`drawdown-value`)'] - $sum_repaids['SUM(`repaid-value`)'];
}

function get_loan_interest_open_lines( $loan_id ){
    $query = "SELECT `invoice-line-id` FROM invoice_lines WHERE `loan-id` = '".$loan_id."' AND `invoice-line-start-date` IS NOT NULL AND `invoice-line-end-date` IS NULL LIMIT 1";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    $line = mysqli_query($conn, $query);

    $result=mysqli_fetch_array($line);

    $line->close();
    mysqli_close($conn);

    if( !isset($result['invoice-line-id']) || empty($result['invoice-line-id']) ){
        return 0;
    }

    return $result['invoice-line-id'];
}


function get_invoice_line_start_date( $invoice_line_id ){
    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$invoice_line_id.'/'), true);

    if( !isset($invoice_line['invoice-line-start-date']) || empty($invoice_line['invoice-line-start-date']) || $invoice_line['invoice-line-start-date'] == NULL ){
        return 0;
    }
    return $invoice_line['invoice-line-start-date'];
}

function get_invoice_line_outstanding_amount( $invoice_line_id ){
    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$invoice_line_id.'/'), true);

    if( !isset($invoice_line['invoice-line-amount']) || empty($invoice_line['invoice-line-amount']) || $invoice_line['invoice-line-amount'] == NULL ){
        return 0;
    }
    return $invoice_line['invoice-line-amount'];
}


function get_invoice_line_interest_rate( $invoice_line_id ){
    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$invoice_line_id.'/'), true);

    if( !isset($invoice_line['invoice-line-interest-rate']) || empty($invoice_line['invoice-line-interest-rate']) || $invoice_line['invoice-line-interest-rate'] == NULL ){
        return 0;
    }
    return $invoice_line['invoice-line-interest-rate'];
}



/* UPDATE INTEREST LINE */
function close_interest_line( $invoice_line_id, $end_date, $end_of_month = false ){

    $amount_due = calcul_amount_due(    get_invoice_line_outstanding_amount($invoice_line_id),
                                        get_invoice_line_start_date($invoice_line_id),
                                        $end_date,
                                        get_invoice_line_interest_rate($invoice_line_id),
                                        $end_of_month
                                    );

    $query_invoice_line = "UPDATE `invoice_lines` SET   `invoice-line-end-date` = '".$end_date."',
                                                        `invoice-line-amount-due` = '".$amount_due."'
                                                WHERE   `invoice_lines`.`invoice-line-id` = '".$invoice_line_id."'";

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    mysqli_query($conn, $query_invoice_line);
    mysqli_close($conn);

    // LOG LA CLOTURE DE LA LIGNE
    $data = "Line_id = ".$invoice_line_id." / End_Date = ".$end_date." / Amount_Due = ".$amount_due;
    db_log( $_SESSION['login'], "CLOSE INTEREST INVOICE LINE" , $data );

    // Refresh the Invoices Cache
    $invoice_id = get_invoice_line_invoice_id( $invoice_line_id );
    json_decode(callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/'), true);
}



function calcul_amount_due( $outstanding_amount, $start_date, $end_date, $interest_rate, $end_of_month = false ){

    $duration = direct_date_compare( $end_date , $start_date )/(60*60*24);

    if( $end_of_month ){
        $duration++;
    }

    if( $duration < 0 ){
        return 0;
    }

    // Le taux annuel est compté, contractuellement, sur 360 jours.
    return round($outstanding_amount * $duration * $interest_rate / ( 360 * 100 ) ,2);
}




function create_new_interest_invoice_line( $loan_id, $currency, $date, $invoice_id, $amount = false, $interest_rate = false ){

    $invoice_line_type = "Interest";
    $invoice_line_currency = $currency;
    $invoice_line_start_date = $date;
    $invoice_line_end_date = "NULL";

    if( !$amount ){
        //$invoice_line_amount = get_loan_outstanding_amount($loan_id);
        $invoice_line_amount = get_loan_outstanding_amount_at_date($loan_id, $date);
    }
    else{
        $invoice_line_amount = $amount;
    }

    if( !$interest_rate ){
        $invoice_line_interest_rate = get_loan_interest_rate_at_date($loan_id, $date);
    }
    else{
        $invoice_line_interest_rate = $interest_rate;
    }

    $invoice_line_amount_due = 0;

    $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                        VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."','".$invoice_line_start_date."',".$invoice_line_end_date.",'".$invoice_line_amount."',".$invoice_line_interest_rate.",'".$invoice_line_amount_due."','".$invoice_id."')";

    $conn_iris = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    mysqli_query($conn_iris, $query_invoice_lines);
    mysqli_close($conn_iris);

    /* LOG DE LA CREATION DE LA LIGNE D'INTERET */
    $data = $loan_id."-".$invoice_line_type."-".$invoice_line_currency."-".$invoice_line_start_date."-".$invoice_line_end_date."-".$invoice_line_amount."-".$invoice_line_interest_rate."-".$invoice_line_amount_due."-".$invoice_id;
    db_log( $_SESSION['login'], "CREATE INVOICE LINE" , $data );

    // Refresh the Invoices Cache
    json_decode(callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/'), true);
}



function create_an_invoice_line( $loan_id, $type, $amount, $currency, $start_date, $end_date, $interest_rate, $invoice_id ){

    $invoice_line_type = $type;
    $invoice_line_currency = $currency;
    $invoice_line_start_date = $start_date;
    $invoice_line_end_date = $end_date;
    $invoice_line_amount = $amount;
    $invoice_line_interest_rate = $interest_rate;
    $invoice_line_amount_due = 0;

    $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                        VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."','".$invoice_line_start_date."',".$invoice_line_end_date.",'".$invoice_line_amount."',".$invoice_line_interest_rate.",'".$invoice_line_amount_due."','".$invoice_id."')";

    $conn_iris = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    mysqli_query($conn_iris, $query_invoice_lines);
    mysqli_close($conn_iris);

    /* LOG DE LA CREATION DE LA LIGNE D'INTERET */
    $data = $loan_id."-".$invoice_line_type."-".$invoice_line_currency."-".$invoice_line_start_date."-".$invoice_line_end_date."-".$invoice_line_amount."-".$invoice_line_interest_rate."-".$invoice_line_amount_due."-".$invoice_id;
    db_log( $_SESSION['login'], "CREATE INVOICE LINE" , $data );

    // Refresh the Invoices Cache
    json_decode(callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/'), true);
}


function get_loan_status( $loan_id ){
    $loan_id = str_replace('/','-',$loan_id);
    $loan = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.$loan_id.'/'), true);

    if( $loan['loan-outstanding-amount'] == 0 && $loan['loan-sum-repaids'] > 0 ){
        return "LOAN REPAID";
    }
    else if( $loan['loan-outstanding-amount'] == 0 && $loan['loan-sum-repaids'] == 0 ){
        return "LOAN NOT STARTED";
    }
    else if( $loan['loan-outstanding-amount'] > 0 ){
        return "LOAN IN PROGRESS";
    }

    return "UNKNOWN";
}



function new_drawdown( $loan_id, $drawdown_value, $drawdown_currency, $drawdown_date, $only_invoices_lines = false ){

    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);

    if( !$only_invoices_lines ){
        /* INSERT DU DRAWDOWN */
        $data = $loan_id.",".$drawdown_value.",".$drawdown_currency.",".$drawdown_date;
        $query_drawdown = "INSERT INTO `drawdowns`  (`drawdown-id`,`loan-id`,`drawdown-value`,`drawdown-currency`,`drawdown-date`)
                                        VALUE       (NULL,'".$loan_id."','".$drawdown_value."','".$drawdown_currency."','".$drawdown_date."')";
        mysqli_query($conn, $query_drawdown);


        /* LOG DU DRAWDOWN */
        $data = $loan_id.",".$drawdown_value.",".$drawdown_currency.",".$drawdown_date;
        db_log( $_SESSION['login'], "CREATE DRAWDOWN", $data );
    }


    // SI PAS DE FACTURE, CREATION D'UNE FACTURE

    $loan_details = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace("/","-",$loan_id).'/'), true);

    $loan = explode("/", $loan_id);
    $invoice_date = date("Y_m_t", strtotime($drawdown_date));
    //$invoice_fund = get_loan_fund($loan_id);
    $invoice_fund = get_fund_invoice_abbr($loan_details['loan-fund']);

    $invoice_id = $loan[0]."_".$invoice_fund."_".$drawdown_currency."_".$invoice_date;

    if( !does_an_invoice_exists( $invoice_id ) ){
        $query = "INSERT INTO   `invoices`  (`invoice-id`,`invoice-date`)
                                VALUE       ('".$invoice_id."','".str_replace("_","-",$invoice_date)."')";
        mysqli_query($conn, $query);

        /* INSERT LOG */
        $data = $invoice_id."','".$invoice_date;
        db_log( $_SESSION['login'], "CREATE INVOICE", $data );
    }

    // AJOUT DES BANKING FEES
    $invoice_line_type = "Banking Fee";
    $invoice_line_currency = $drawdown_currency;
    $invoice_line_start_date = "NULL";
    $invoice_line_end_date = "NULL";
    $invoice_line_amount = $loan_details['loan-banking-fee'];
    $invoice_line_interest_rate = "NULL";
    $invoice_line_amount_due = $loan_details['loan-banking-fee'];


    $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                    VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."',".$invoice_line_start_date.",".$invoice_line_end_date.",'".$invoice_line_amount."',".$invoice_line_interest_rate.",'".$invoice_line_amount_due."','".$invoice_id."')";
    mysqli_query($conn, $query_invoice_lines);

    /* LOG DES BANKING FEES */
    $data = $loan_id."-".$invoice_line_type."-".$invoice_line_currency."-".$invoice_line_start_date."-".$invoice_line_end_date."-".$invoice_line_amount."-".$invoice_line_interest_rate."-".$invoice_line_amount_due."-".$invoice_id;
    db_log( $_SESSION['login'], "CREATE INVOICE LINE", $data );


    // AJOUT DES DRAWDOWN FEES
    $drawdowns = json_decode(callAPI('GET',API_URL.'/api/drawdowns/?loan-id='.$loan_id), true);

    // S'il s'agit du 1er drawdown, alors on ajoute les drawdown fees.
    if( count($drawdowns) == 1 ){
        $loan = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan_id).'/'), true);

        $invoice_line_type = "Drawdown Fee";
        $invoice_line_currency = $drawdown_currency;
        $invoice_line_start_date = "NULL";
        $invoice_line_end_date = "NULL";
        $invoice_line_amount = $loan['loan-value'];
        $invoice_line_interest_rate = get_loan_drawdown_fee_rate($loan_id);
        $invoice_line_amount_due = $loan['loan-drawdown-fee'];

        $query_invoice_lines = "INSERT INTO `invoice_lines` (`invoice-line-id`,`loan-id`,`invoice-line-type`,`invoice-line-currency`,`invoice-line-start-date`,`invoice-line-end-date`,`invoice-line-amount`,`invoice-line-interest-rate`,`invoice-line-amount-due`,`invoice-id`)
                                            VALUE       (NULL,'".$loan_id."','".$invoice_line_type."','".$invoice_line_currency."',".$invoice_line_start_date.",".$invoice_line_end_date.",'".$invoice_line_amount."',".$invoice_line_interest_rate.",'".$invoice_line_amount_due."','".$invoice_id."')";
        mysqli_query($conn, $query_invoice_lines);

        /* LOG DES DRAWDOWN FEES */
        $data = $loan_id."-".$invoice_line_type."-".$invoice_line_currency."-".$invoice_line_start_date."-".$invoice_line_end_date."-".$invoice_line_amount."-".$invoice_line_interest_rate."-".$invoice_line_amount_due."-".$invoice_id;
        db_log( $_SESSION['login'], "CREATE INVOICE LINE", $data);
    }

    mysqli_close($conn);

    // SI N'EST PAS LE PREMIER DRAWDOWN, CLOTURE DE LA LIGNE D'INTERET PRECEDENTE
    if( get_loan_interest_open_lines($loan_id) != 0 ){
        close_interest_line( get_loan_interest_open_lines($loan_id), $drawdown_date);
    }

    // CREATION D'UNE NOUVELLE LIGNE DE FACTURATION D'INTERETS
    create_new_interest_invoice_line( $loan_id, $drawdown_currency, $drawdown_date, $invoice_id);

    // Refresh the Invoices Cache
    callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/');
}



function new_repaid( $loan_id, $repaid_value, $repaid_currency, $repaid_date, $only_invoices_lines = false ){

    if( !$only_invoices_lines ){

        /* INSERT REPAID */
        $mysqli_repaid = new mysqli(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
        if ($mysqli_repaid->connect_errno) {
            printf("Connect failed: %s\n", $mysqli_repaid->connect_error);
            exit();
        }

        $query_repaid = "INSERT INTO `repaids`  (`repaid-id`,`loan-id`,`repaid-value`,`repaid-currency`,`repaid-date`)
                                        VALUE   (NULL,'".$loan_id."','".$repaid_value."','".$repaid_currency."','".$repaid_date."')";

        if (!$mysqli_repaid->query($query_repaid)) {
            printf("Errormessage: %s\n", $mysqli_repaid->error);
        }
        $mysqli_repaid->close();

        /* LOG REPAID*/
        $data = $loan_id.",".$repaid_value.",".$repaid_currency.",".$repaid_date;
        db_log( $_SESSION['login'], "CREATE REPAID", $data);
    }


    // CLOTURE LA LIGNE D'INTERET OUVERTE
    close_interest_line( get_loan_interest_open_lines($loan_id) , $repaid_date );


    // S'IL RESTE DE L'OUTSTANDING, ALORS CREE UNE NOUVELLE LIGNE DE FACTURATION
    if( get_loan_outstanding_amount_at_date( $loan_id, $repaid_date ) > 0 ){
        $loan = explode("/", $loan_id);
        $invoice_date = date("Y_m_t", strtotime($repaid_date));
        $invoice_fund = get_loan_fund($loan_id);
        $invoice_id = $loan[0]."_".$invoice_fund."_".$repaid_currency."_".$invoice_date;

        create_new_interest_invoice_line( $loan_id, $repaid_currency, $repaid_date, $invoice_id );
    }

    // Refresh the Invoices Cache
    callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/');
}



function check_interest_invoices_lines( $loan_id = NULL){
    if( $loan_id == NULL ){
        $query = "SELECT * FROM `invoice_lines` WHERE `invoice_lines`.`invoice-line-start-date` IS NOT NULL AND `invoice_lines`.`invoice-line-end-date` IS NULL";
    }
    else{
        $query = "SELECT * FROM `invoice_lines` WHERE `invoice_lines`.`invoice-line-start-date` IS NOT NULL AND `invoice_lines`.`invoice-line-end-date` IS NULL AND `invoice_lines`.`loan-id` LIKE '".$loan_id."'";
    }


    $conn = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    $invoice_lines = mysqli_query($conn, $query);

    while($line = mysqli_fetch_array($invoice_lines,MYSQLI_ASSOC)) {

        $line_invoice_date = get_invoice_date($line['invoice-id']);

        // Si la date de facturation est passée...
        if( direct_date_compare(date("Y-m-d"),$line_invoice_date ) > 0 ) {

            // Cloture de la ligne à la date de facturation
            close_interest_line( $line['invoice-line-id'], $line_invoice_date , true );

            // S'il n'existe pas encore de facture pour le mois suivant, création d'une nouvelle facture
            $loan = explode("/", $line['loan-id']);
            $invoice_date = date("Y_m_t", strtotime($line_invoice_date)+(24*60*60) );
            if( $loan_id == NULL ){
                $invoice_fund = get_loan_fund( get_invoice_line_loan_id($line['invoice-line-id']) );
            }
            else{
                $invoice_fund = get_loan_fund( $loan_id );
            }
            $invoice_id = $loan[0]."_".$invoice_fund."_".$line['invoice-line-currency']."_".$invoice_date;

            if( !does_an_invoice_exists( $invoice_id ) ){
                $query = "INSERT INTO   `invoices`  (`invoice-id`,`invoice-date`)
                                        VALUE       ('".$invoice_id."','".str_replace("_","-",$invoice_date)."')";
                mysqli_query($conn, $query);

                /* INSERT LOG */
                $data = $invoice_id."','".$invoice_date;
                db_log( $_SESSION['login'], "CREATE INVOICE", $data );
            }

            // Création d'une nouvelle ligne de facturation ouverte au lendemain de la date de facturation
            create_new_interest_invoice_line( $line['loan-id'], $line['invoice-line-currency'], date("Y-m-d",strtotime( $line_invoice_date )+(24*60*60) ), $invoice_id );

            // Refresh the Invoices Cache
            callAPI('GET',API_URL.'/api/invoices/refresh-cache/'.$invoice_id.'/');
        }
    }

    $invoice_lines->close();
    mysqli_close($conn);
}



function change_interest_rate( $loan_id, $event_date ){

    $previous_invoice_line_id = get_loan_interest_open_lines($loan_id);

    // Clôture de la ligne de facturation ouverte, à la date.
    close_interest_line( $previous_invoice_line_id , $event_date );

    $invoice_line = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/'.$previous_invoice_line_id.'/'), true);

    // Création d'une nouvelle ligne de facturation, au nouveau taux, à partir de la date.
    create_new_interest_invoice_line( $loan_id, $invoice_line['invoice-line-currency'], $event_date, $invoice_line['invoice-id'] );
}




function recalculate_invoices_lines( $loan_id ){

    $events = create_loan_events( $loan_id );

    // Tri chronologique des événements
    usort($events, 'date_compare');


    // Suppression des lignes de facturation
    $conn_recalculate = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,DB_NAME);
    $query_invoices_lines = 'DELETE FROM `invoice_lines` WHERE `invoice_lines`.`loan-id` = "'.$loan_id.'" AND
                                                                (`invoice_lines`.`invoice-line-type` = "Interest" OR
                                                                `invoice_lines`.`invoice-line-type` = "Drawdown Fee" OR
                                                                `invoice_lines`.`invoice-line-type` = "Banking Fee")';
    $invoice_lines = mysqli_query($conn_recalculate, $query_invoices_lines);
    mysqli_close($conn_recalculate);

    // Déroulement des événements dans l'ordre chornologique
    foreach($events as $event){
        if($event['type'] === 'drawdown'){
            new_drawdown( $loan_id, $event['value'], $event['currency'], $event['date'], true);
        }
        else if($event['type'] === 'repaid'){
            new_repaid( $loan_id, $event['value'], $event['currency'], $event['date'], true);
        }
        else if($event['type'] === 'new-month'){
            check_interest_invoices_lines( $loan_id );
        }
        else if($event['type'] === 'new-interest-rate'){
            change_interest_rate( $loan_id, $event['date'] );
        }
    }
}






function create_loan_events( $loan_id ){

    // Création de l'array des événements
    $events = [];
    reset($events);

    $drawdowns = json_decode( callAPI('GET',API_URL.'/api/drawdowns/?loan-id='.$loan_id), true);
    if( !empty($drawdowns) ){
        foreach($drawdowns as $drawdown){
            $events[] = array(  'date' => $drawdown['drawdown-date'],
                                'value' => $drawdown['drawdown-value'],
                                'currency' => $drawdown['drawdown-currency'],
                                'type' => 'drawdown');
        }
    }

    $repaids = json_decode( callAPI('GET',API_URL.'/api/repaids/?loan-id='.$loan_id), true);
    if( !empty($repaids) ){
        foreach($repaids as $repaid){
            $events[] = array(  'date' => $repaid['repaid-date'],
                                'value' => $repaid['repaid-value'],
                                'currency' => $repaid['repaid-currency'],
                                'type' => 'repaid');
        }
    }

    $loan_events = json_decode( callAPI('GET',API_URL.'/api/events/?loan-id='.str_replace('/','-',$loan_id)), true);
    if( !empty($loan_events) ){
        foreach( $loan_events as $loan_event ){
            if( $loan_event['event-label'] === "INTEREST_RATE" ){
                $events[] = array(  'date' => $loan_event['event-date'],
                                    'value' => $loan_event['event-new-value'],
                                    'type' => 'new-interest-rate');
            }

            elseif( $loan_event['event-label'] === "DUE_DATE" ){
                $events[] = array(  'date' => $loan_event['event-new-due-date'],
                                    'value' => $loan_event['event-new-due-date'],
                                    'type' => 'new-due-date');
            }
        }
    }

    // Ajout des changements de mois
    usort($events, 'date_compare');
    $update = first_day_of_next_month($events[0]['date']);



    $loan_status = json_decode( callAPI('GET',API_URL.'/api/loan-events/?loan-id='.$loan_id), true);

    // S'il reste de l'outstanding => Le loan est toujours ouvert => Limit = 01 jour du mois du prochain mois.
    if( $loan_status['loan-outstanding-amount']  > 0){
        $limit_date = date("Y-m-t", strtotime( date("Y-m-d") ));
    }
    // Sinon => Loan fermé lors du dernier repaid => Limit = 01 jour du mois après le dernier repaids.
    else{
        $limit_date = date("Y-m-t", strtotime($events[ count($events)-1 ]['date']));
    }
    $limit_date = date('Y-m-d', strtotime('+2 day', strtotime( $limit_date )));

    while( direct_date_compare( $limit_date ,$update) > 0){
        $events[] = array(  'date' => $update,
                            'type' => 'new-month');

        $update = first_day_of_next_month($update);
    }

    usort($events, 'date_compare');

    for( $i = 1; $i < count($events) ; $i++ ){
        if( $events[$i]['type'] === 'new-month' && date_compare( $events[$i], $events[$i-1] ) <= 0 ){
            $temp = $events[$i-1];
            $events[$i-1] = $events[$i];
            $events[$i] = $temp;
        }
    }

    return $events;
}


function create_in_out_loan_events( $loan_id ){
    $drawdowns = json_decode( callAPI('GET',API_URL.'/api/drawdowns/?loan-id='.$loan_id), true);
    $repaids = json_decode( callAPI('GET',API_URL.'/api/repaids/?loan-id='.$loan_id), true);

    // Création de l'array des événements
    $events = [];
    reset($events);

    foreach($drawdowns as $event){
        $events[] = array(  'date' => $event['drawdown-date'],
                'value' => $event['drawdown-value'],
                'currency' => $event['drawdown-currency'],
                'type' => 'drawdown');
    }

    foreach($repaids as $event){
        $events[] = array(  'date' => $event['repaid-date'],
                'value' => $event['repaid-value'],
                'currency' => $event['repaid-currency'],
                'type' => 'repaid');
    }

    return $events;
}


function get_client_total_outstanding( $client_abbr, $fund = false, $currency = 'USD' ){
    $total_outstanding = 0;
    if( !$fund ){
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr), true);
    }
    elseif( !$currency ){
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr.'&loan-fund='.$fund), true);
    }
    else{
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr.'&loan-fund='.$fund.'&loan-currency='.$currency), true);
    }
    foreach($query_loans as $loan){
        $total_outstanding += get_loan_outstanding_amount($loan['loan-id']);
    }
    return $total_outstanding;
}


function get_client_total_overdue( $client_abbr, $fund = false, $currency = 'USD' ){
    $total_overdue = 0;
    if( !$fund ){
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr), true);
    }
    elseif( !$currency ){
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr.'&loan-fund='.$fund), true);
    }
    else{
        $query_loans = json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client_abbr.'&loan-fund='.$fund.'&loan-currency='.$currency), true);
    }
    foreach($query_loans as $loan){
        if( direct_date_compare( date("Y-m-d"), $loan['loan-initial-due-date']) > 0 ){
            $total_overdue += get_loan_outstanding_amount($loan['loan-id']);
        }
    }
    return $total_overdue;
}


function get_invoice_amount( $invoice_id ){
    $invoice = json_decode(callAPI('GET',API_URL.'/api/invoices/status/'.$invoice_id.'/'), true);
    return $invoice['invoice-total-amount-due'];
}


function get_first_drawdown_date( $loan_id ){
    if( strpos( $loan_id, " ") !== false ){
        return "1970-01-01";
    }

    $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace("/","-",$loan_id).'/'), true);
    return $loan_status['loan-first-drawdown-date'];
}


function get_last_repaid_date( $loan_id ){
    if( strpos( $loan_id, " ") !== false ){
        return "1970-01-01";
    }

    $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace("/","-",$loan['loan-id']).'/'), true);
    return $loan_status['loan-last-repaid-date'];
}

function display_loan_details( $loan_id ){

    $loan = json_decode( callAPI('GET',API_URL.'/api/loans/'.str_replace("/","-",$loan_id).'/'), true);

    $events = create_loan_events( $loan['loan-id'] );

    $loan_redis_status = json_decode( callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace("/","-",$loan_id).'/'), true);

    $loan_due_date = get_loan_due_date($loan['loan-id']);

    $loan_status;
    if( $loan_redis_status['loan-outstanding-amount'] == 0 && $loan_redis_status['loan-sum-repaids'] > 0 ){
        $loan_status = "LOAN REPAID";
    }
    else if( $loan_redis_status['loan-outstanding-amount'] == 0 && $loan_redis_status['loan-sum-repaids'] == 0 ){
        $loan_status = "LOAN NOT STARTED";
    }
    else if( $loan_redis_status['loan-outstanding-amount'] > 0 && direct_date_compare( $loan_due_date,date("Y-m-d")) <= 0 ){
        $loan_status = "LOAN OVERDUE";
    }
    else if( $loan_redis_status['loan-outstanding-amount'] > 0 && direct_date_compare( $loan_due_date,date("Y-m-d")) > 0 ){
        $loan_status = "LOAN IN PROGRESS";
    }
    else{
        $loan_status = "UNKNOWN STATUS";
    }

    $events[] = array('date' => $loan['loan-initial-due-date'], 'type' => 'initial-due-date');

    // Tri de $events par date croissante
    usort($events, 'date_compare');

    // Calcul de l'OutStanding
    $date_outstanding = 0.0;
    for($i = 0; $i < count($events); $i++){
        if( $events[$i]['type'] == 'drawdown' ){
            $date_outstanding += floatval($events[$i]['value']);
        }

        if( $events[$i]['type'] == 'repaid' ){
            $date_outstanding -= floatval($events[$i]['value']);

            if( $date_outstanding === 0.00 || floatval($date_outstanding) < 0.01 ){
                $events[] = array('date' => $events[$i]['date'], 'type' => 'reimbursement-date');
                $reimbursement_date = $events[$i]['date'];
            }
        }

        $events[$i]['outstanding'] = $date_outstanding;
    }

    echo('<div class="row">');
        echo('<div class="col">');
            echo('<h2 id="'.strtolower(str_replace('/','-',$loan['loan-id'])).'">Loan '.str_replace('/','-',$loan['loan-id']).'</h2>');
            echo('<h4>');
                echo('<span class="badge badge-info py-2 px-3 mr-2">'.$loan['loan-facility-type'].'</span>');
                echo('<span class="badge badge-primary py-2 px-3 mr-2">'.$loan['loan-currency'].' - '.get_currency_symbol($loan['loan-currency']).'</span>');
                if( isset( $loan['loan-risk-level'] ) ){
                    echo('<span class="badge badge-secondary py-2 px-3">'.$loan['loan-risk-level'].'</span>');
                }
            echo('</h4>');
        echo('</div>');

        if($loan_status == "LOAN REPAID"){
            echo('<div class="col-auto">');
                echo('<div class="badge-pill badge-success text-white py-2 px-5">');
                    echo('<i data-feather="check" class="mr-2"></i> REPAID');
                echo('</div>');
            echo('</div>');
        }
        elseif($loan_status == "LOAN NOT STARTED"){
            echo('<div class="col-auto">');
                echo('<div class="badge-pill badge-info text-white py-2 px-5">');
                    echo('<i data-feather="clock" class="mr-2"></i> WAITING TO START');
                echo('</div>');
            echo('</div>');
        }
        elseif($loan_status == "LOAN IN PROGRESS"){
            echo('<div class="col-auto">');
                echo('<div class="badge-pill badge-warning text-white py-2 px-5 mb-2">');
                    echo('<i data-feather="clock" class="mr-2"></i> IN PROGRESS');
                echo('</div>');
                echo('<a href="/loans/edit-loan/'.str_replace('/','-',$loan['loan-id']).'/" class="btn btn-gradient border-0 btn-block text-white">');
                    echo('<i data-feather="edit-3"></i> Edit Loan');
                echo('</a>');
            echo('</div>');
        }
        elseif($loan_status == "LOAN OVERDUE"){
            echo('<div class="col-auto">');
                echo('<div class="badge-pill badge-danger py-2 px-5 mb-2">');
                    echo('<i data-feather="alert-triangle" class="mr-2"></i> OVERDUE');
                echo('</div>');
                echo('<a href="/loans/edit-loan/'.str_replace('/','-',$loan['loan-id']).'/" class="btn btn-gradient border-0 btn-block text-white">');
                    echo('<i data-feather="edit-3"></i> Edit Loan');
                echo('</a>');
            echo('</div>');
        }
    echo('</div>');
    echo('<div class="row pt-3">');
        echo('<div class="col-md-4">');
            echo('<h4>Finance</h4>');
            echo('<table class="table table-hover table-borderless table-striped table-earning border-bottom">');
                echo('<tbody>');
                    echo('<tr>');
                        echo('<th scope="row">Value</td>');
                        echo('<td class="text-right"><span class="amount">'.$loan['loan-value'].'</span> '.$loan['loan-currency'].'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Sum Drawdowns</td>');
                        echo('<td class="text-right"><span class="amount">'.$loan_redis_status['loan-sum-drawdowns'].'</span> '.$loan['loan-currency'].'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Sum Repaids</td>');
                        echo('<td class="text-right"><span class="amount">'.$loan_redis_status['loan-sum-repaids'].'</span> '.$loan['loan-currency'].'</td>');
                    echo('</tr>');
                echo('</tbody>');
                echo('<tfoot class="font-weight-bold border-top">');
                    echo('<tr>');
                        echo('<td class="text-uppercase">Loan Outstanding</td>');

                        if($loan_status == "LOAN REPAID"){
                            echo('<td class="text-right table-success text-success"><span class="amount">'.$loan_redis_status['loan-outstanding-amount'].'</span> '.$loan['loan-currency'].'</td>');
                        }
                        elseif($loan_status == "LOAN NOT STARTED"){
                            echo('<td class="text-right table-info text-info"><span class="amount">'.$loan_redis_status['loan-outstanding-amount'].'</span> '.$loan['loan-currency'].'</td>');
                        }
                        elseif($loan_status == "LOAN IN PROGRESS"){
                            echo('<td class="text-right table-warning text-warning"><span class="amount">'.$loan_redis_status['loan-outstanding-amount'].'</span> '.$loan['loan-currency'].'</td>');
                        }
                        elseif($loan_status == "LOAN OVERDUE"){
                            echo('<td class="text-right table-danger text-danger"><span class="amount">'.$loan_redis_status['loan-outstanding-amount'].'</span> '.$loan['loan-currency'].'</td>');
                        }
                        else{
                            echo('<td class="text-right"><span class="amount">'.$loan_redis_status['loan-outstanding-amount'].'</span> '.$loan['loan-currency'].'</td>');
                        }
                    echo('</tr>');
                echo('</tfoot>');
            echo('</table>');
        echo('</div>');
        echo('<div class="col-md-4">');
            echo('<h4>Interest Rates (%)</h4>');
            echo('<table class="table table-hover table-borderless table-striped table-earning border-bottom">');
                echo('<tbody>');
                    echo('<tr>');
                        echo('<th scope="row">Initial</td>');
                        echo('<td class="text-right amount">'.$loan['loan-interest-rate'].'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Current</td>');
                        echo('<td class="text-right amount">'.get_loan_interest_rate_at_date( $loan['loan-id'], date("Y-m-d") ).'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Overdue</td>');
                        echo('<td class="text-right amount">'.$loan['loan-interest-rate'].'</td>');
                    echo('</tr>');
                echo('</tbody>');
            echo('</table>');
        echo('</div>');
        echo('<div class="col-md-4">');
            echo('<h4>Dates</h4>');
            echo('<table class="table table-hover table-borderless table-striped table-earning border-bottom">');
                echo('<tbody>');
                    echo('<tr>');
                        echo('<th scope="row">Loan Validation Date</td>');
                        echo('<td class="text-right">Unknown</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">First Drawdown Date</td>');
                        if( $loan_redis_status['loan-sum-drawdowns'] == 0 ){
                            echo('<td class="text-right">No drawdown yet</td>');
                        }
                        else{

                            echo('<td class="text-right">'.$loan_redis_status['loan-first-drawdown-date'].'</td>');
                        }

                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Last Repaid Date</td>');
                        if( $loan_redis_status['loan-sum-repaids'] == 0 ){
                            echo('<td class="text-right">No repaid yet</td>');
                        }
                        else{
                            echo('<td class="text-right">'.$loan_redis_status['loan-last-repaid-date'].'</td>');
                        }
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Initial Due Date</td>');
                        // echo('<td class="text-right">'.date("d/m/Y", strtotime($loan['loan-initial-due-date'])).'</td>');
                        echo('<td class="text-right">'.$loan['loan-initial-due-date'].'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Due Date</td>');
                        // echo('<td class="text-right">'.date("d/m/Y", strtotime( $loan_due_date )).'</td>');
                        echo('<td class="text-right">'.$loan_due_date.'</td>');
                    echo('</tr>');
                    echo('<tr>');
                        echo('<th scope="row">Reimbursement Date</td>');
                        if( $reimbursement_date == NULL ){
                            echo('<td class="text-right">In progress</td>');
                        }
                        else{
                            echo('<td class="text-right">'.date("d/m/Y", strtotime($reimbursement_date)).'</td>');
                        }
                    echo('</tr>');
                echo('</tbody>');
            echo('</table>');
        echo('</div>');
    echo('</div>');

    //echo('<h4>Amount / Time graph</h4>');
    $international = json_decode(file_get_contents("/var/www/html/includes/international.json"),true);
    echo('<h4><i data-feather="award" class="mr-2"></i> Incoterms</h4>');
    echo('<div class="row mb-3">');
        echo('<div class="col-md-6">');
            echo('<label>Purchase incoterm (Type - City - Country)</label>');
            echo('<p>'.$loan['loan-purchase-incoterm-type'].' - '.$loan['loan-purchase-incoterm-city'].' - <img src="/images/flags/'.strtolower($loan['loan-purchase-incoterm-country']).'.png" class="flag mr-1"/> '.get_country_name($loan['loan-purchase-incoterm-country'],$international).'</p>');
        echo('</div>');
        echo('<div class="col-md-6">');
            echo('<label>Sales incoterm (Type - City - Country)</label>');
            echo('<p>'.$loan['loan-sales-incoterm-type'].' - '.$loan['loan-sales-incoterm-city'].' - <img src="/images/flags/'.strtolower($loan['loan-sales-incoterm-country']).'.png" class="flag mr-1"/>'.get_country_name($loan['loan-sales-incoterm-country'],$international).'</p>');
        echo('</div>');
    echo('</div>');
    echo('<h4><i data-feather="package" class="mr-2"></i> Commodity</h4>');
    echo('<div class="row mb-3">');
        echo('<div class="col-md-6">');
            echo('<label>Commodity</label>');
            echo('<p>'.$loan['loan-commodity'].'</p>');
        echo('</div>');
        echo('<div class="col-md-6">');
            echo('<label>Type of commodity</label>');
            echo('<p>'.$loan['loan-type-of-commodity'].'</p>');
        echo('</div>');
    echo('</div>');
    echo('<h4><i data-feather="navigation" class="mr-2"></i> Travel</h4>');
    echo('<div class="row mb-3">');
        echo('<div class="col-md-3">');
            echo('<label>Origin</label>');
            echo('<p>');
                echo('<img src="/images/flags/'.strtolower($loan['loan-origin-country']).'.png" class="flag mr-1"/> '.get_country_name($loan['loan-origin-country'],$international) );
            echo('</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Place of storage</label>');
            echo('<p>'.$loan['loan-place-of-storage'].'</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Destination</label>');
            echo('<p>'.$loan['loan-destination'].'</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Country of incorporation</label>');
            echo('<p>');
                echo('<img src="/images/flags/'.strtolower($loan['loan-country-of-incorporation']).'.png" class="flag mr-1"/> '.get_country_name($loan['loan-country-of-incorporation'],$international) );
            echo('</p>');
        echo('</div>');
    echo('</div>');
    echo('<h4><i data-feather="users" class="mr-2"></i> Actors</h4>');
    echo('<div class="row mb-3">');
        echo('<div class="col-md-3">');
            echo('<label>Supplier</label>');
            echo('<p>'.$loan['loan-supplier-name'].'</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Supplier country of incorporation</label>');
            echo('<p><img src="/images/flags/'.strtolower($loan['loan-supplier-country-of-incorporation']).'.png" class="flag mr-1"/> '.get_country_name($loan['loan-supplier-country-of-incorporation'],$international).'</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Buyer</label>');
            echo('<p>'.$loan['loan-buyer'].'</p>');
        echo('</div>');
        echo('<div class="col-md-3">');
            echo('<label>Buyer country of incorporation</label>');
            echo('<p><img src="/images/flags/'.strtolower($loan['loan-buyer-country-of-incorporation']).'.png" class="flag mr-1"/> '.get_country_name($loan['loan-buyer-country-of-incorporation'],$international).'</p>');
        echo('</div>');
    echo('</div>');

    $invoice_lines = json_decode(callAPI('GET',API_URL.'/api/invoice-lines/?loan-id='.$loan['loan-id'] ), true);
    foreach( $invoice_lines as $invoice_line ){
        $events[] = array(
            'date' => get_invoice_date($invoice_line['invoice-id']),
            'type' => 'invoice-line',
            'value' => $invoice_line['invoice-line-amount'],
            'currency' => $invoice_line['invoice-line-currency'],
            'line-type' => $invoice_line['invoice-line-type'],
            'line-start-date' => $invoice_line['invoice-line-start-date'],
            'line-end-date' => $invoice_line['invoice-line-end-date'],
            'line-interest-rate' => $invoice_line['invoice-line-interest-rate'],
            'line-amount-due' => $invoice_line['invoice-line-amount-due'],
            'invoice-id' => $invoice_line['invoice-id']
        );
    }

    echo('<div class="row">');
        echo('<div class="col">');
            echo('<h4><i data-feather="list" class="mr-2"></i> Timeline</h4>');
        echo('</div>');
        echo('<div class="col-auto">');
            echo('<a href="/controller/invoices_lines/recalculate-invoice-lines.php?loan_id='.$loan_id.'" class="btn btn-gradient">');
                echo('Update invoice lines');
            echo('</a>');
        echo('</div>');
    echo('</div>');

    echo('<table class="table table-hover table-borderless table-striped table-earning border-bottom">');
        echo('<thead class="text-center">');
            echo('<tr>');
                echo('<th>Date</th>');
                echo('<th>Type</th>');
                echo('<th>Amount</th>');
                echo('<th>Outstanding</th>');
                echo('<th>Loan Status</th>');
                echo('<th>Line Type</th>');
                echo('<th>Period</th>');
                echo('<th>Interest Rate</th>');
                echo('<th>Amount Due</th>');
            echo('</tr>');
        echo('</thead>');
        echo('<tbody>');
            // Tri de $events par date croissante
            usort($events, 'date_compare');

            foreach($events as $event){
                if($event['type'] === 'drawdown'){
                    echo('<tr>');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</th>');
                        echo('<td><i data-feather="log-out" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td class="text-right"><span class="amount">'.$event['value'].'</span> '.$event['currency'].'</td>');
                        echo('<td class="text-right"><span class="amount">'.$event['outstanding'].'</span> '.$loan['loan-currency'].'</td>');
                        echo('<td class="table-warning text-warning">Outstanding</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                else if($event['type'] === 'repaid'){
                    echo('<tr>');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</th>');
                        echo('<td><i data-feather="log-in" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td class="text-right"><span class="amount">'.$event['value'].'</span> '.$event['currency'].'</td>');
                        if(floatval($event['outstanding']) !== floatval(0) && floatval($event['outstanding']) >= 0.01){
                            echo('<td class="text-right"><span class="amount">'.$event['outstanding'].'</span> '.$loan['loan-currency'].'</td>');
                            echo('<td class="table-warning text-warning">Outstanding</td>');
                        }
                        else{
                            echo('<td class="text-right"><span class="amount">0.00</span> '.$loan['loan-currency'].'</td>');
                            echo('<td class="table-success text-success">Repaid</td>');
                        }
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                else if($event['type'] === 'new-interest-rate'){
                    echo('<tr>');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</th>');
                        echo('<td><i data-feather="activity" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td class="text-right">'.$event['value'].' %</td>');
                        echo('<td></td>');
                }
                else if($event['type'] === 'invoice-line'){
                    echo('<tr>');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</th>');
                        //echo('<td><i data-feather="file" class="mr-2 ml-3"></i> '.ucfirst($event['type']).' - <a href="/invoice/'.$event['invoice-id'].'/">'.$event['invoice-id'].'</a></td>');
                        echo('<td class="text-right">Invoice - <a href="/invoice/'.$event['invoice-id'].'/">'.$event['invoice-id'].'</a></td>');
                        echo('<td class="text-right">'.number_format($event['value'], 2, '.', "'").' '.$event['currency'].'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td>'.$event['line-type'].'</td>');

                        if($event['line-start-date'] !== NULL && $event['line-end-date'] !== NULL ){
                            echo('<td>'.date("d/m/Y", strtotime($event['line-start-date'])).' - '.date("d/m/Y", strtotime($event['line-end-date'])).'</td>');
                        }
                        elseif ($event['line-start-date'] !== NULL && $event['line-end-date'] === NULL) {
                            echo('<td>'.date("d/m/Y", strtotime($event['line-start-date'])).' - In progress</td>');
                        }
                        else{
                            echo('<td></td>');
                        }

                        if($event['line-interest-rate'] !== NULL){
                            echo('<td class="text-right">'.$event['line-interest-rate'].' %</td>');
                        }
                        else{
                            echo('<td></td>');
                        }

                        echo('<td class="text-right"><span class="amount">'.$event['line-amount-due'].'</span> '.$event['currency'].'</td>');
                }
                else if($event['type'] === 'new-month'){
                    echo('<tr class="border-top border-dark">');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</th>');
                        echo('<td><i data-feather="clock" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                else if($event['type'] === 'initial-due-date'){
                    echo('<tr class="table-dark">');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</td>');
                        echo('<td><i data-feather="clock" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                else if($event['type'] === 'reimbursement-date'){

                    if( direct_date_compare($event['date'], $loan['loan-initial-due-date'] ) > 0 ){
                        echo('<tr class="table-danger">');
                    }
                    else{
                        echo('<tr class="table-success">');
                    }
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</td>');
                        echo('<td><i data-feather="clock" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                else {
                    echo('<tr>');
                        echo('<th scope="row">'.date("d/m/Y", strtotime($event['date'])).'</td>');
                        echo('<td><i data-feather="clock" class="mr-2"></i> '.ucfirst($event['type']).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                }
                echo('</tr>');
            }
        echo('</tbody>');
    echo('</table>');
}



function get_rm_client_list( $rm_user_id ){
    $clients = json_decode( callAPI('GET',API_URL.'/api/clients/'), true);
    $rm_clients_list;

    if( !empty($clients) ){
        foreach( $clients as $company ){
            if( $rm_user_id == $company['company-relation-manager'] || $rm_user_id == $company['company-account-operator'] ){
                $rm_clients_list[] = $company;
            }
        }
    }

    return $rm_clients_list;
}


function get_outstanding_amount_from_events( $events, $until_today = false ){

    $outstanding_amount_limit = 0;

    if( $until_today ){
        $outstanding_amount_limit = -INF;
    }

    // Tri chronologique des événements
    usort($events, 'date_compare');

    $loop_date = $events[0]['date'];
    $outstanding_amount = $events[0]['value'];
    $outstanding_amount_array;

    while( $outstanding_amount > $outstanding_amount_limit && direct_date_compare( $loop_date, date('Y-m-d') ) <= 0 ){

        for( $i =1; $i < count($events); $i++ ){
            if($loop_date === $events[$i]['date']){
                if($events[$i]['type'] === 'repaid'){
                    $outstanding_amount -= $events[$i]['value'];
                }
                elseif($events[$i]['type'] === 'drawdown'){
                    $outstanding_amount += $events[$i]['value'];
                }
            }
        }

        $outstanding_amount_array[ $loop_date ] = $outstanding_amount;

        $loop_date = date('Y-m-d', strtotime('1 day', strtotime( $loop_date )));
    }

    return $outstanding_amount_array;
}

function get_outstanding_amount_from_private_placement_events( $events, $until_today = false ){

    // $outstanding_amount_limit = 0;

    // if( $until_today ){
    //     $outstanding_amount_limit = -INF;
    // }

    // Tri chronologique des événements
    usort($events, 'date_compare');

    $loop_date = $events[0]['date'];
    $outstanding_amount = 0;
    $outstanding_amount_array;

    while(direct_date_compare( $loop_date, date('Y-m-d') ) <= 0 ){
        for( $i = 0; $i < count($events); $i++ ){
            if($loop_date === $events[$i]['date']){
                if($events[$i]['type'] === 'subscription'){
                    $outstanding_amount += $events[$i]['value'];
                } elseif($events[$i]['type'] === 'redemption') {
                    $outstanding_amount -= $events[$i]['value'];
                }
            }
        }

        $outstanding_amount_array[ $loop_date ] = $outstanding_amount;

        $loop_date = date('Y-m-d', strtotime('1 day', strtotime( $loop_date )));
    }

    return $outstanding_amount_array;
}





// cache pour les amount_due totaux des factures

// traduction des Incoterms = Acronyme => Nom complet

// Suppression des getters devenus inutiles


function get_loans_number_per_fund($loans = false){
    $loans_number = [];
    reset($loans_number);

    $month = "2018-01-01";
    $funds = json_decode(callAPI('GET',API_URL.'/api/funds/'), true);

    // PREPARE THE EMPTY ARRAY
    while( direct_date_compare( date('Y-m-d'), $month  ) > 0 ){
        foreach($funds as $fund){
            $currencies = explode("-",$fund['fund-currencies']);

            foreach( $currencies as $currency ){
                $loans_number[] = array(    'date' => $month,
                                            'fund' => $fund['fund-abbr'],
                                            'currency' =>  $currency,
                                            'loan_number' => 0 );
            }
        }

        $month = date('Y-m-d', strtotime('1 month', strtotime( $month )));
    }

    // FOREACH LOAN => PUT IT IN THE CORRECT MONTH
    foreach( $loans as $loan ){
        $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan['loan-id']).'/'), true);

        $correct_month = date('Y-m-01', strtotime( $loan_status['loan-first-drawdown-date'] ));

        for( $i = 0; $i < count( $loans_number ); $i++){
            if( direct_date_compare( $correct_month, $loans_number[$i]['date'] ) == 0
                && $loans_number[$i]['fund'] == $loan['loan-fund']
                && $loans_number[$i]['currency'] == $loan['loan-currency'] ) {

                $loans_number[ $i ]['loan_number']++;

            }
        }
    }

    return $loans_number;
}

function get_loans_outstanding_amounts_per_month( $loans = false, $funds = false){
    $loans_outstanding_amounts = [];
    reset($loans_outstanding_amounts);

    $month = "2018-01-01";

    if( !$funds ){
        $funds = json_decode(callAPI('GET',API_URL.'/api/funds/'), true);
    }

    if( !$loans ){
        $loans = json_decode(callAPI('GET',API_URL.'/api/loans/'), true);
    }

    // PREPARE THE EMPTY ARRAY
    while( direct_date_compare( date('Y-m-d'), $month  ) > 0 ){
        foreach($funds as $fund){
            $currencies = explode("-",$fund['fund-currencies']);

            foreach( $currencies as $currency ){
                $loans_outstanding_amounts[] = array(    'date' => $month,
                                            'fund' => $fund['fund-abbr'],
                                            'currency' =>  $currency,
                                            'loans_values' => 0 );
            }
        }

        $month = date('Y-m-d', strtotime('1 month', strtotime( $month )));
    }

    // FOREACH LOAN => PUT ITS VALUE IN THE CORRECT MONTH
    foreach( $loans as $loan ){
        $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan['loan-id']).'/'), true);

        $correct_month = date('Y-m-01', strtotime( $loan_status['loan-first-drawdown-date'] ));

        for( $i = 0; $i < count( $loans_outstanding_amounts ); $i++){
            if( direct_date_compare( $correct_month, $loans_outstanding_amounts[$i]['date'] ) == 0
                && $loans_outstanding_amounts[$i]['fund'] == $loan['loan-fund']
                && $loans_outstanding_amounts[$i]['currency'] == $loan['loan-currency'] ) {

                $loans_outstanding_amounts[ $i ]['loans_values'] += get_loan_outstanding_amount( $loan['loan-id'] ) ;

            }
        }
    }

    return $loans_outstanding_amounts;
}



function get_loans_values_per_fund( $loans = false ){
    $loans_values = [];
    reset($loans_values);

    $month = "2018-01-01";
    $funds = json_decode(callAPI('GET',API_URL.'/api/funds/'), true);

    if( !$loans ){
        $loans = json_decode(callAPI('GET',API_URL.'/api/loans/'), true);
    }

    // PREPARE THE EMPTY ARRAY
    while( direct_date_compare( date('Y-m-d'), $month  ) > 0 ){
        foreach($funds as $fund){
            $currencies = explode("-",$fund['fund-currencies']);

            foreach( $currencies as $currency ){
                $loans_values[] = array(    'date' => $month,
                                            'fund' => $fund['fund-abbr'],
                                            'currency' =>  $currency,
                                            'loans_values' => 0 );
            }
        }

        $month = date('Y-m-d', strtotime('1 month', strtotime( $month )));
    }

    // FOREACH LOAN => PUT ITS VALUE IN THE CORRECT MONTH
    foreach( $loans as $loan ){
        $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan['loan-id']).'/'), true);

        $correct_month = date('Y-m-01', strtotime( $loan_status['loan-first-drawdown-date'] ));

        for( $i = 0; $i < count( $loans_values ); $i++){
            if( direct_date_compare( $correct_month, $loans_values[$i]['date'] ) == 0
                && $loans_values[$i]['fund'] == $loan['loan-fund']
                && $loans_values[$i]['currency'] == $loan['loan-currency'] ) {

                $loan_data = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan['loan-id']).'/'), true);
                $loans_values[ $i ]['loans_values'] += $loan_data['loan-value'];
            }
        }
    }

    return $loans_values;
}

function get_loans_values_per_fund_per_team($funds = false, $relation_manager){
    $loans_values = [];
    reset($loans_values);

    $month = "2018-01-01";


    if( !$funds ){
        $funds = json_decode(callAPI('GET',API_URL.'/api/funds/'), true);
    }

    // PREPARE THE EMPTY ARRAY
    while( direct_date_compare( date('Y-m-d'), $month  ) > 0 ){
        foreach($funds as $fund){
            $currencies = explode("-",$fund['fund-currencies']);

            foreach( $currencies as $currency ){
                $loans_values[] = array(    'date' => $month,
                                            'fund' => $fund['fund-abbr'],
                                            'currency' =>  $currency,
                                            'loans_values' => 0 );
            }
        }

        $month = date('Y-m-d', strtotime('1 month', strtotime( $month )));
    }

    $clients = json_decode(callAPI('GET',API_URL.'/api/clients/?company-relation-manager='.$relation_manager), true);
    // FOREACH LOAN => PUT ITS VALUE IN THE CORRECT MONTH
    foreach($clients as $client){
        $loans =  json_decode(callAPI('GET',API_URL.'/api/loans/?company-abbr='.$client['company-abbr']), true);
        foreach( $loans as $loan ){
            $loan_status = json_decode(callAPI('GET',API_URL.'/api/get-loan/status/'.str_replace('/','-',$loan['loan-id']).'/'), true);

            $correct_month = date('Y-m-01', strtotime( $loan_status['loan-first-drawdown-date'] ));

            for( $i = 0; $i < count( $loans_values ); $i++){
                if( direct_date_compare( $correct_month, $loans_values[$i]['date'] ) == 0
                    && $loans_values[$i]['fund'] == $loan['loan-fund']
                    && $loans_values[$i]['currency'] == $loan['loan-currency'] ) {

                    $loan_data = json_decode(callAPI('GET',API_URL.'/api/loans/'.str_replace('/','-',$loan['loan-id']).'/'), true);
                    $loans_values[ $i ]['loans_values'] += $loan_data['loan-value'];
                }
            }
        }
    }
    return $loans_values;
}


function filter_loans( $url_path = false ){

    $url = API_URL.'/api/loans/?';

    if( !$url_path || empty( $url_path ) ){
       return json_decode(callAPI('GET', $url), true);
    }

    return json_decode(callAPI('GET', $url.$url_path), true);
}






?>
