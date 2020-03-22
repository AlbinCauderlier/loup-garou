<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<link rel="shortcut icon"				href="/images/favicon.ico" />
<link rel="icon"	type="image/x-icon"	href="/images/favicon.ico" />
<link rel="icon"	type="image/png"	href="/images/favicon.ico" />

<link rel="stylesheet"	type="text/css"	href="/vendors/bootstrap-4.3.1/css/bootstrap.min.css"	media="all"/>

<link rel="stylesheet" type="text/css" href="/vendors/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css" media="all"/>
<link rel="stylesheet" type="text/css" href="/vendors/DataTables/datatables.min.css" media="all"/>

<link rel="stylesheet"	type="text/css"	href="/styles/css/styles.css"	media="all" />

<meta name="theme-color" content="#333333">

<?php 
	$user_data = json_decode(callAPI('GET',API_URL.'/api/users/?user-email-address='.$_SESSION['user-email-address']), true);
	$user_data = $user_data[0];
?>