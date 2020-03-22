<?php   
    //Si un internaute tente une connexion directe à cette page, le ramener vers la page d'accueil, en passant (par précaution), par la phase de déconnexion.
    if(!isset($_SESSION['isConnected']) || empty($_SESSION['isConnected']) || !isset($_SESSION['login']) || empty($_SESSION['login'])){
        // Inclusion des paramètres d'authentication
        require_once("controller/authentication/config.php");
        
        header('Location: '.AUTHENTICATION_LOGOUT_PAGE);
        exit;
    }

    $user_data = json_decode(callAPI('GET',API_URL.'/api/users/?user-email-address='.$_SESSION['user-email-address']), true);
    $user_data = $user_data[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>User Guide - Loups Garous</title>    
    <?php
        include_once("common/head.php");
    ?>
</head>
<body id="top" data-spy="scroll" data-target="#toc" data-offset="130">
    <?php
        include_once("common/header.php");
    ?>
    <main class="mt-0">
        <section class="bg-gradient my-4 pt-5">
            <div class="container py-3">
                <h1 class="text-white">User Guide</h1>
            </div>
        </section>
		<section>
			<div class="container pt-4">
                <div class="row">
                    <aside class="col-md-2">
                        <nav class="p-0" id="toc">
                            <ul class="list-unstyled navbar-list mb-0">
                                <li class="nav-item">
                                    <a href="#clients" class="border-left nav-link py-2">
                                        <i data-feather="home" class="mr-2"></i> Clients
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#facility_agreement" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Facility Agreement
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#limits" class="border-left nav-link py-2">
                                        <i data-feather="refresh-cw" class="mr-2"></i> Limits
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#loans" class="border-left nav-link py-2">
                                        <i data-feather="refresh-cw" class="mr-2"></i> Loans
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#drawdowns" class="border-left nav-link py-2">
                                        <i data-feather="log-out" class="mr-2"></i> Drawdowns
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#repaids" class="border-left nav-link py-2">
                                        <i data-feather="log-in" class="mr-2"></i> Repaids
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#events" class="border-left nav-link py-2">
                                        <i data-feather="activity" class="mr-2"></i> Events
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#operations" class="border-left nav-link py-2">
                                        <i data-feather="refresh-cw" class="mr-2"></i> Operations
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#subscriptions" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Subscriptions
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#redemptions" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Redemptions
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#private_placements" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Private Placements
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#funds" class="border-left nav-link py-2">
                                        <i data-feather="home" class="mr-2"></i> Funds
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#ibans" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> IBANS
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#invoice_lines" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Invoice Lines
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#credit_notes" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Credit Notes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#invoices" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Invoices
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#statis" class="border-left nav-link py-2">
                                        <i data-feather="pie-chart" class="mr-2"></i> Statics
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#forecast" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Forecasts
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#cashflow" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Cashflow
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#risks" class="border-left nav-link py-2">
                                        <i data-feather="alert-triangle" class="mr-2"></i> Risks
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dashboard" class="border-left nav-link py-2">
                                        <i data-feather="file-text" class="mr-2"></i> Dashboard
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </aside>
                    <div class="col-md-10">
                        <h2 id="clients">
                            <i data-feather="home" class="mr-2"></i> Clients
                        </h2>
                        <h3>Vue d'ensemble</h3>
                        <p>

                        </p>
                        <h3>Liste des clients inactifs</h3>
                        <p>

                        </p>
                        <h3>Vue détaillée</h3>
                        <p>

                        </p>
                        <h3>Overview</h3>
                        <p>

                        </p>
                        <h3>Page of a client</h3>
                        <p>

                        </p>
                        <h3>Register a new client</h3>
                        <p>

                        </p>
                        <h3>Editer les coordonnées d'un client</h3>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="facility_agreement">
                            <i data-feather="file-text" class="mr-2"></i> Facility Agreement
                        </h2>
                        <p>
                            Dans Loups Garous, un facility agreement contient les données concernant l'accord entre un client et un fond.
                        </p>
                        <h3>Page of the Facility Agreements</h3>
                        <p>

                        </p>
                        <h3>Détail dans la page par client</h3>
                        <p>

                        </p>
                        <h3>Register a new Facility Agreement</h3>
                        <p>

                        </p>
                        <h3>Edit a Facility Agreement</h3>
                        <p>

                        </p>
                        <h3>Event regarding a Facility Agreement</h3>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="limits">
                            <i data-feather="refresh-cw" class="mr-2"></i> Limits
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="loans">
                            <i data-feather="refresh-cw" class="mr-2"></i> Loans
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="drawdowns">
                            <i data-feather="log-out" class="mr-2"></i> Drawdowns
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="repaids">
                            <i data-feather="log-in" class="mr-2"></i> Repaids
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="events">
                            <i data-feather="refresh-cw" class="mr-2"></i> Events
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="operations">
                            <i data-feather="home" class="mr-2"></i> Operations
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="subscriptions">
                            <i data-feather="file-text" class="mr-2"></i> Subscriptions
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="redemptions">
                            <i data-feather="file-text" class="mr-2"></i> Redemptions
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="private_placements">
                            <i data-feather="file-text" class="mr-2"></i> Private Placements
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="funds">
                            <i data-feather="file-text" class="mr-2"></i> Funds
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="ibans">
                            <i data-feather="file-text" class="mr-2"></i> IBANS
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="invoice_lines">
                            <i data-feather="file-text" class="mr-2"></i> Invoice Lines
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="credit_notes">
                            <i data-feather="file-text" class="mr-2"></i> Credit Notes
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="invoices">
                            <i data-feather="file-text" class="mr-2"></i> Invoices
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="statis">
                            <i data-feather="pie-chart" class="mr-2"></i> Statics
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="forecast">
                            <i data-feather="file-text" class="mr-2"></i> Forecasts
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="cashflow">
                            <i data-feather="file-text" class="mr-2"></i> Cashflow
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="risks">
                            <i data-feather="alert-triangle" class="mr-2"></i> Risks
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        <h2 id="dashboard">
                            <i data-feather="file-text" class="mr-2"></i> Dashboard
                        </h2>
                        <p>

                        </p>
                        <hr class="my-5"/>
                        FOR NEW DDWNS:

Once we have confirmation from the bank that payment is under processing, register it in IRIS (it’s important to receive the confirmation of the bank to be sure of the DDWN’s value date and then have the correct amount in the invoice at the end of the month).

 

In IRIS:

 

Top right -> Actions -> New Loan : to register the information in the addendum.
Select the company name and the fund (Asia or Europe), the addendum number will be given automatically.
In Facility Type -> select one of the 4 possibilities:
                                                              i.      Inventory Financing (when the goods are stored somewhere and we have a FCR, Warehouse receipt, …)

                                                             ii.      Transit Financing (when the goods are on board of the vessel and shipped – when we have a B/L date)

                                                           iii.      B/L Financing (when the ORIGINAL B/L is provided to us – quite rare)

                                                           iv.      Prefinancing (when the goods are financed before arriving to the purchase port, before having “hands on the goods” – Performance risk on the buyer)

Due date : when the loan has to be repaid
Financial Aspect -> register all the amounts, interest rates, etc… (calculate the DDWN fees if needed).
                                                              i.      !!! In “Minimum Facility Fee”, “Penality Fee” & “Success Fee” -> Put always 0. We do not use these information for the moment.

LTV: give the percentage of the Purchase Value or Sales Value we finance (usually we only use these two possibilities, however it is possible to also finance Margin Value or Freight Value, but it’s rare too)
Commodity -> indicate the commodity we finance and its type (Soft, Metal/Minerals, Energy, Chemicals & Equipment)
Travel:
                                                              i.      Origin Country -> where the vessel come from

                                                             ii.      Place of storage -> where the goods are stored (if the goods are not stored, take the country stated in the Purchase Incoterm)

                                                           iii.      Destination -> Country stated in Sales Incoterm

                                                           iv.      Country of incorporation -> State the country of our client (borrower)

Incoterms:
                                                              i.      Type (choose a type in the list, if goods are UNSOLD, or no Incoterms are used -> Select “NPT” (No Property Transfer)

                                                             ii.      City and Country as it is stated in the addendum (If goods are unsold, put “Unsold” in the City field and do not select any country

(if other information are in the addendum ex: CIFFO, put the “FO” in the city field) -> CIF / FO Rotterdam / Netherlands

Supplier – Seller:
                                                              i.      Put the name and country of incorporation of the companies

(if approved Supplier, Multiple suppliers, Unsold or Multiple Buyers -> Do not select any Country)

 

Once all the information are registered -> “New Loan” Button

 

Top Right Button -> Actions -> New DDWN
Select the loan ID you just entered with the previous steps,
Indicate the amount of the DDWN (same as in the Loan), the currency
Indicate de DDWN Date (Value Date)
 

FOR NEW REPAIDS :

 

Top right Button -> Actions -> New Repaid: When a borrower repays a loan
Select Loan ID (Addendum n°)
The amount repaid with the currency
The repaid date (!!! THE DATE ON THE SWIFT !!!)
 

FOR INVOICES :

 

Client pay an interest invoice:
Go in Invoices -> (check the name and amount)
Select paid (on the right)
Indicate the payment date (SWIFT DATE)
 

 

Once any of the steps above is fulfilled -> Register the date in Excel file (For Romain – Cash Flow)
Go to: “SRV-SCCF” – > “Horizon Capital” – > “HC ASIA”, “HC EUROPE”, “HC Multistrategy Fund” or “Private Placemements” – > “Daily Report” –> “Entries Recap” (Excel file).

Be careful to save and close the file each time you are done, in order to allow the other to use it)

 

 

When you answer the bank, be careful to the bank account on which the funds are transferred:
 

EU : USD : LU29 3610 1179 1451 0102

EU : EUR : LU56 3610 1179 1451 0101

Asia : USD : LU96 3610 1183 2391 0103

PP1 : EUR : LU50 3610 1179 1441 0202

PP2 : USD : LU12 3610 1179 1441 0304

Multi-Strat Fund: LU04 3610 1185 2471 0101

 

!!! Be careful to the SWIFT DATE !!!
                    </div>
                </div>
			</div>
		</section>
    </main>
    <?php
        include_once("common/footer.php");
    ?>
</body>
</html>