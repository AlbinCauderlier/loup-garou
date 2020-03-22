<?php

include_once("iris_functions.php");
include_once("users_functions.php");


/**
 *	Fonction de nettoyage d'un texte
 *
 *	@param	$text	Texte à nettoyer
 *
 *	@return		Texte nettoyé
 */
function cleanText($text){
	$text=trim($text); // delete white spaces after & before text

	if(1 === get_magic_quotes_gpc()){
		$stripslashes=create_function('$txt','return stripslashes($txt);');
	}
	else{
		$stripslashes=create_function('$txt','return $txt;');
	}

	// magic quotes ?
	$text=$stripslashes($text);
	$text=htmlentities($text,ENT_QUOTES); // converts to string with " and ' as well
	//$text=nl2br($text);
	return $text;
}


/**
 * RequestDispatcher
 * Processing of the request uri
 *
 * TODO - Optimiser, car ici, p1 est djà le premier niveau d'arborescence et p2 est, par contre, tout le reste de l'arbo.
 */
function getRequestedUri(){
	$dossier_pages='pages/';
	$page_home='home';

	// Default=home page
	if(empty($_GET)){
		return($page_home);
	}


	// Directory protection
	if(isset($_GET['p1'])){
		$page=cleanText($_GET['p1']);
		$temp=explode('/',$page);

		// Existing page?
		if(isset($_GET['p2'])){
			$temp2=explode('/',cleanText($_GET['p2']));

			if(isset($temp2[1]) && file_exists($dossier_pages.$temp[0].'/'.$temp2[0].'/'.$temp2[1].'/index.php')){
				return($temp[0].'/'.$temp2[0].'/'.$temp2[1]);
			}

			if(file_exists($dossier_pages.$temp[0].'/'.$temp2[0].'/index.php')){
				return($temp[0].'/'.$temp2[0]);
			}
		}

		if(file_exists($dossier_pages.$temp[0].'/index.php')){
			return($temp[0]);
		}
	}

	// Redirection, faute de page trouvée, vers la page error
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	return('error');
}


/**
 *
 */
function remove_accents($string,$charset='utf-8'){
	$string=htmlentities($string,ENT_NOQUOTES,$charset);

	$string=preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#','\1',$string);
	$string=preg_replace('#&([A-za-z]{2})(?:lig);#','\1',$string); // pour les ligatures e.g. '&oelig;'
	$string=preg_replace('#&[^;]+;#', '',$string); // supprime les autres caractères

	return $string;
}



/**
 *
 *	@param		Session_label		Le label du champ.
 *	@return		Indique si ce label est utilisé et renseigné.
 *
 *	Permet d'éviter une nouvelle authentification si l'utilisateur est toujours connecté.
 */
function session_isset($label){
	// On teste si la variable de session existe et contient une valeur
	if(isset($_SESSION[$label]) && !empty($_SESSION[$label])){
		return true;
	}
	return false;
}


function get_curl_call($url){
	$ch=curl_init($url);
	//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
	//curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); => = curl -L ?
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

	//curl_setopt($ch,CURLOPT_DNS_USE_GLOBAL_CACHE,true);
	//curl_setopt($ch,CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

function callAPI($method, $url, $data = false, $headers= false){
	$curl = curl_init();

	switch ($method){
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		default:
			if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
	}

	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	if(!$headers){
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'APIKEY: '.APIKEY,
			'Content-Type: application/json',
		));
   	}
   	else{
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'APIKEY: '.APIKEY,
			$headers
		));
   	}
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	// EXECUTE:
	$result = curl_exec($curl);
	if(!$result){
		die("API Request - Connection Failure : ".$result);
	}
	curl_close($curl);
	return $result;
}

function callAPI_post($url, $data){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => http_build_query($data),
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/x-www-form-urlencoded",
        "cache-control: no-cache",
        "APIKEY: ".APIKEY,
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    }
    return $response;
}

function is_a_valid_url($url){
	$file_headers = @get_headers($url);
	if(!$file_headers || strpos($file_headers[0],'200')===false || strpos($url,'http')===false){
		return false;
	}
	return true;
}


function get_currency_symbol($currency_code){
	// Complete list : http://www.xe.com/symbols.php
	switch($currency_code){
		case 'USD': return '$';
		case 'EUR': return '€';
		case 'BTC': return '฿';
		case 'GBP': return '£';
		case 'CNY': return '¥';
		case 'INR': return '₹';
		default: 	return $currency_code;
	}
}


/**
 * Génération du numéro aléatoire de cart_id
*/
function random(int $car) {
	$string = "";
	$chaine = "1234567890abcdefghijklmnpqrstuvwxyz";
	srand((double)microtime()*1000000);

	for($i=0; $i<$car; $i++) {
		$string .= $chaine[mt_rand()%strlen($chaine)];
	}
	return $string;
}


/**
 *	Parse a Unix time stamp to a date
 *
 *	@param	$unix_time	Time stamp
 *
 *  @return				A string with the date
 *
 */
function unixtime_to_delay($unix_timedelay){
	$seconds = Math.floor($unix_timedelay/1000);
	$minutes = Math.floor($seconds/60);
	$hours = Math.floor($minutes/60);
	$days = Math.floor($hours/24);

	$hours = $hours-($days*24);
	$minutes = $minutes-($days*24*60)-($hours*60);
	$seconds = $seconds-($days*24*60*60)-($hours*60*60)-($minutes*60);

	$result='';
	if($days>0)		$result .= $days.'days ';
	if($hours>0)	$result .= $hours.'hours ';
	if($minutes>0)	$result .= $minutes.'min ';
	if($seconds>0)	$result .= $seconds.'sec ';

	return $result;
}


function get_back_to_top_link($link = "#") {
	echo('<a href="'.$link.'" title="Back to top" class="bottom-link">');
		echo('<i data-feather="chevron-up" class="icon-md text-white"></i>');
	echo('</a>');
}

/**
 *
 * Permet l'interprétation des fichiers PHP de génération des e-mails.
 *
 * @param	$url	L'URL du fichier php
 * @return	string	La chaine en sortie du fichier interprété.
 */
function get_include_contents($url) {
	// Contrôle que l'URL fournie est bien un fichier.
	if(!is_file($url)){
		return false;
	}

	ob_start();
	include $url;
	return ob_get_clean();
}

function get_country_name($country_code, $international = false){
	if( !$international ){
		$international = json_decode(file_get_contents("/var/www/html/includes/international.json"),true);
	}

	$country_code = strtoupper($country_code);

	for($i = 0; $i < count($international) ; $i++){
		//if( $international[$i]['alpha2Code'] == strtoupper($country_code) || $international[$i]['alpha3Code'] == strtoupper($country_code) ){
		if( $international[$i]['alpha2Code'] == $country_code ){
			return $international[$i]['name'];
		}
	}
	return $country_code;
}


function date_compare($element1, $element2) {
    return direct_date_compare($element1['date'], $element2['date']);
}

function direct_date_compare($element1, $element2) {
    return strtotime($element1) - strtotime($element2);
}

function events_date_compare($element1, $element2) {
    return direct_date_compare($element1['event-date'], $element2['event-date']);
}

function str_compare_loan_id ( $loan1, $loan2){
	return strcmp( $loan1['loan-id'], $loan2['loan-id'] );
}







function display_user_messages(){
	if( isset($_SESSION['success_message']) && !empty($_SESSION['success_message']) ){
        echo('<div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
              <strong>Well done!</strong> '.$_SESSION['success_message'].'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
    }

    if( isset($_SESSION['error_message']) && !empty($_SESSION['error_message']) ){
        echo('<div class="alert alert-warning alert-dismissible fade show d-print-none" role="alert">
              <strong>Warning!</strong> '.$_SESSION['error_message'].'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>');
    }

    reset_messages();
}

function reset_messages(){
	unset($_SESSION['error_message']);
	unset($_SESSION['success_message']);
}


function db_log( $user, $action, $data = NULL ){
	$query_logs = "INSERT INTO `logs`	(`log-id`,`log-timestamp`,`log-user`,`log-action`,`log-data`)
    						VALUE 		(NULL, NOW() ,'".$user."','".$action."','".$data."')";

    $conn_logs = mysqli_connect(DB_URL,DB_USER,DB_PASSWORD,"iris_logs");
    mysqli_query($conn_logs, $query_logs);

    mysqli_close($conn_logs);
}


function first_day_of_next_month( $date ){
	return date("Y-m-d", strtotime(date("Y-m-t", strtotime($date)))+86400);
}


$usd_eur_value;

function get_usd_value( $value, $currency = "EUR" ){
	if( $value == 0 ){
		return 0;
	}
	if( $currency === "USD" ){
		return $value;
	}


	if( $currency === "EUR" ){
		if( !isset( $usd_eur_value ) ){
			$get_oed_currency_changes = json_decode(get_curl_call("https://api.exchangeratesapi.io/latest"), true);
			$usd_eur_value = $get_oed_currency_changes['rates']['USD'];
			return $value * $get_oed_currency_changes['rates']['USD'];
		}

		return $value * $usd_eur_value;
	}

	return $value;
}


?>
