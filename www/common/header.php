<header class="navbar fixed-top navbar-expand-lg bg-white shadow py-0 d-print-none" role="header">
    <div class="container-fluid">
        <a href="/clients/" class="navbar-brand py-0" title="Iris home">
            <img src="<?=ROOT_URL?>/images/iris-logo2.png" style="max-height:63px; width: 72px;" alt="Iris"/>
        </a>
        <div class="row">
            <ul class="navbar-nav mr-auto float-left">
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( strpos($_SESSION['page'], 'client') !== false ) echo('active');?>">
                                <i data-feather="home"></i><br/>Clients
                            </a>
                        </button>
                        <div class="dropdown-menu">
                        <?php
                            if( $_SESSION['page'] === 'clients' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>All');
                            echo('</a>');

                            if( $_SESSION['page'] === 'clients/limits-management' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/limits-management/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>Limit Management');
                            echo('</a>');

                            if( $_SESSION['page'] === 'clients/facility-agreements' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/facility-agreements/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>Facility Agreements');
                            echo('</a>');

                            if( $_SESSION['page'] === 'clients/detailed' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/detailed/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>Detailed');
                            echo('</a>');

                            if( $_SESSION['page'] === 'clients/inactives' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/inactives/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>Inactives');
                            echo('</a>');

                            echo('<div class="dropdown-divider"></div>');
                            if( $_SESSION['page'] === 'clients/overview' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/clients/overview/" class="dropdown-item">');
                            }
                            echo('<i data-feather="home" class="mr-1"></i>Overview');
                            echo('</a>');

                            echo('<div class="dropdown-divider"></div>');
                            if( $_SESSION['page'] === 'groups' ){
                                echo('<a class="dropdown-item text-primary disabled">');
                            }
                            else{
                                echo('<a href="/groups/" class="dropdown-item">');
                            }
                            echo('<i data-feather="layers" class="mr-1"></i>Groups');
                            echo('</a>');
                        ?>
                        </div>
                  </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( strpos($_SESSION['page'], 'loan') !== false ) echo('active');?>">
                                <i data-feather="clipboard"></i><br/>Loans
                            </a>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                                if( $_SESSION['page'] === 'loans' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>Current year');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/all' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/all/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>All');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/all-outstanding' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/all-outstanding/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>Outstanding Loans');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/per-fund' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/per-fund/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>Per Fund');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/at-date-form' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/at-date-form/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>At Date');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/2019' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/2019/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>2019');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/2018' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/2018/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>2018');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/buyers' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/buyers/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>Buyers');
                                echo('</a>');

                                if( $_SESSION['page'] === 'loans/suppliers' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/loans/suppliers/" class="dropdown-item">');
                                }
                                echo('<i data-feather="clipboard" class="mr-1"></i>Suppliers');
                                echo('</a>');
                            ?>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#"><i data-feather="clipboard" class="mr-1"></i>Overview</a>
                                <ul class="dropdown-menu">
                                    <?php
                                        if( $_SESSION['page'] === 'loans/overview' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Overview All');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-fund=HCM' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-fund=HCM" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Fund - HCM');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-fund=HRC' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-fund=HRC" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Fund - HRC');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-fund=ASIA' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-fund=ASIA" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Fund - ASIA');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-fund=PP1' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-fund=PP1" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Fund - PP1');
                                        echo('</a>');


                                        if( $_SESSION['page'] === 'loans/overview/?loan-fund=PP2' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-fund=PP2" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Fund - PP2');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-id=%/2020' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-id=%/2020" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Year - 2020');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-id=%/2019' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-id=%/2019" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Year - 2019');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'loans/overview/?loan-id=%/2018' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/loans/overview/?loan-id=%/2018" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="clipboard" class="mr-1"></i>Year - 2018');
                                        echo('</a>');
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( $_SESSION['page'] == 'operations' || strpos($_SESSION['page'], 'operations/' ) !== false || strpos($_SESSION['page'], 'operations/' ) !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="refresh-cw"></i><br/>Operations
                            </a>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                                echo('<label class="mx-3">Drawdowns</label>');
                                if( $_SESSION['page'] === 'operations/drawdowns' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/drawdowns/" class="dropdown-item">');
                                }
                                    echo('<i data-feather="log-out" class="mr-1"></i>Last 90 days');
                                    echo('</a>');

                                if( $_SESSION['page'] === 'operations/drawdowns/all' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/drawdowns/all/" class="dropdown-item">');
                                }
                                echo('<i data-feather="log-out" class="mr-1"></i>All');
                                echo('</a>');
                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Repaids</label>');
                                if( $_SESSION['page'] === 'operations/repaids' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/repaids/" class="dropdown-item">');
                                }
                                echo('<i data-feather="log-in" class="mr-1"></i>Last 90 days');
                                echo('</a>');

                                if( $_SESSION['page'] === 'operations/repaids/all' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/repaids/all/" class="dropdown-item">');
                                }
                                echo('<i data-feather="log-in" class="mr-1"></i>All Repaids');
                                echo('</a>');
                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3 text-nowrap">Private Placement</label>');

                                if( $_SESSION['page'] === 'operations/private-placement' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/private-placement/" class="dropdown-item">');
                                }
                                echo('Last 90 days');
                                echo('</a>');

                                if( $_SESSION['page'] === 'operations/private-placement/all' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/private-placement/all/" class="dropdown-item">');
                                }
                                echo('All');
                                echo('</a>');

                                if( $_SESSION['page'] === 'private-placement/pp1' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/private-placement/pp1/" class="dropdown-item">');
                                }
                                echo('PP1');
                                echo('</a>');

                                if( $_SESSION['page'] === 'private-placement/pp2' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/private-placement/pp2/" class="dropdown-item">');
                                }
                                echo('PP2');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Operations</label>');

                                if( $_SESSION['page'] === 'operations' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="refresh-cw" class="mr-1"></i>Last 30 days');
                                echo('</a>');
                                if( $_SESSION['page'] === 'operations/per-day' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/per-day/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="refresh-cw" class="mr-1"></i>Today');
                                echo('</a>');
                                if( $_SESSION['page'] === 'operations/per-fund' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/per-fund/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="refresh-cw" class="mr-1"></i>Per Fund (30 days)');
                                echo('</a>');
                                if( $_SESSION['page'] === 'operations/all' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/operations/all/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="refresh-cw" class="mr-1"></i>All');
                                echo('</a>');
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( $_SESSION['page'] == 'invoices' || strpos($_SESSION['page'], 'invoices/' ) !== false || strpos($_SESSION['page'], 'invoice/' ) !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="file-text"></i><br/>Invoices
                            </a>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#"><i data-feather="file-text" class="mr-1"></i>Invoices</a>
                                <ul class="dropdown-menu">
                                    <?php
                                        if( $_SESSION['page'] === 'invoices' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>By Month');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoices/client-distribution' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/client-distribution/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Client Distribution');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoices/per-fund' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/per-fund/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Per Fund');
                                        echo('</a>');



                                        if( $_SESSION['page'] === 'invoices/all-historic' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/all-historic/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Past Invoices');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoices/2019' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/2019/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>2019');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoices/2018' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/2018/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>2018');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoices/unpaid-invoices' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/unpaid-invoices/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Unpaid Invoices');
                                        echo('</a>');
                                        echo('<div class="dropdown-divider"></div>');

                                        if( $_SESSION['page'] === 'invoices/overview' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/overview/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Overview');
                                        echo('</a>');
                                    ?>
                                </ul>

                            </li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#"><i data-feather="align-justify"></i>Invoice Lines</a>
                                <ul class="dropdown-menu">
                                    <?php
                                        if( $_SESSION['page'] === 'invoice-lines' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>Last 90 days');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/all' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/all/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>All');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/per-type' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/per-type/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>Per Type');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/credit-notes' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/credit-notes/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>Credit Notes');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/interest-lines' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/interest-lines/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>Interest Lines');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/open-lines' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/open-lines/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>Open Lines');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/2019' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/2019/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>2019');
                                        echo('</a>');

                                        if( $_SESSION['page'] === 'invoice-lines/2018' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoice-lines/2018/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="align-justify" class="mr-1"></i>2018');
                                        echo('</a>');
                                    ?>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" data-toggle="dropdown" href="#"><i data-feather="file-text"></i>Credit Notes</a>
                                <ul class="dropdown-menu">
                                    <?php
                                        if( $_SESSION['page'] === 'invoices/credit-notes' ){
                                            echo('<a class="dropdown-item text-primary disabled">');
                                        }
                                        else{
                                            echo('<a href="/invoices/credit-notes/" class="dropdown-item">');
                                        }
                                        echo('<i data-feather="file-text" class="mr-1"></i>Credit Notes');
                                        echo('</a>');
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( $_SESSION['page'] == 'forecast' || strpos($_SESSION['page'], 'forecast/' ) !== false || strpos($_SESSION['page'], 'forecast/' ) !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="trending-up"></i><br/>Forecast
                            </a>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                                if( $_SESSION['page'] === 'forecast' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/forecast/invoice-forecast/" class="dropdown-item">');
                                }
                                echo('<i data-feather="trending-up" class="mr-1"></i>Invoice Forecast');
                                echo('</a>');

                                if( $_SESSION['page'] === 'forecast/repaids-forecast' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/forecast/repaids-forecast/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Repaids Forecast');
                                echo('</a>');
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( strpos($_SESSION['page'], 'cashflow') !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="bar-chart-2"></i><br/>CashFlow
                            </a>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                                echo(' <label class="mx-3">Cashflow</label>');
                                if( $_SESSION['page'] === 'cashflow/all/asia/usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/asia/usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow ASIA $');
                                echo('</a>');

                                if( $_SESSION['page'] === 'cashflow/all/hcm/usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/hcm/usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow HCM $');
                                echo('</a>');

                                if( $_SESSION['page'] === 'cashflow/all/hcna/usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/hcna/usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow HCNA $');
                                echo('</a>');

                                if( $_SESSION['page'] === 'cashflow/all/hrc/eur' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/hrc/eur/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow HRC €');
                                echo('</a>');


                                if( $_SESSION['page'] === 'cashflow/all/hrc/usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/hrc/usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow HRC $');
                                echo('</a>');


                                if( $_SESSION['page'] === 'cashflow/all/pp1/eur' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/pp1/eur/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow PP1 €');
                                echo('</a>');


                                if( $_SESSION['page'] === 'cashflow/all/pp2/usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/all/pp2/usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Cashflow PP2 $');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Banks Reports</label>');

                                if( $_SESSION['page'] === 'cashflow/bank-trx-hca' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/bank-trx-hca/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Bank Trx HCA');
                                echo('</a>');

                                if( $_SESSION['page'] === 'cashflow/bank-trx-hrc' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/bank-trx-hrc/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Bank Trx HRC');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Matching</label>');

                                if( $_SESSION['page'] === 'cashflow/matching-hca-usd' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/cashflow/matching-hca-usd/" class="dropdown-item">');
                                }
                                echo('<i data-feather="bar-chart-2" class="mr-1"></i>Matching HCA');
                                echo('</a>');
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( strpos($_SESSION['page'], 'risk') !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="alert-triangle"></i><br/>Risk
                            </a>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                                if( $_SESSION['page'] === 'risk' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/risk/" class="dropdown-item">');
                                }
                                echo('<i data-feather="alert-triangle" class="mr-1"></i>All');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');


                                if( $_SESSION['page'] === 'risk/watchlist' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/risk/watchlist/" class="dropdown-item">');
                                }
                                echo('<i data-feather="alert-triangle" class="mr-1"></i>Watchlist');
                                echo('</a>');
                                if( $_SESSION['page'] === 'risk/contentieux' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/risk/contentieux/" class="dropdown-item">');
                                }
                                echo('<i data-feather="alert-triangle" class="mr-1"></i>Contentieux');
                                echo('</a>');
                                if( $_SESSION['page'] === 'risk/unpaid-invoices' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/risk/unpaid-invoices/" class="dropdown-item">');
                                }
                                echo('<i data-feather="alert-triangle" class="mr-1"></i>Unpaid Invoices');
                                echo('</a>');
                                if( $_SESSION['page'] === 'risk/limits-overdrawns' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/risk/limits-overdrawns/" class="dropdown-item">');
                                }
                                echo('<i data-feather="alert-triangle" class="mr-1"></i>Limits Overdrawns');
                                echo('</a>');
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="dropdown border-0 m-0 p-0" role="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <a class="nav-link px-3 text-center pb-0 <?php if( strpos($_SESSION['page'], 'stats') !== false ) echo('active');?>" data-placement="bottom">
                                <i data-feather="pie-chart"></i><br/>Stats
                            </a>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                                if( $_SESSION['page'] === 'stats/dashboard' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/dashboard/" class="dropdown-item">');
                                }
                                echo('<i data-feather="pie-chart" class="mr-1"></i></i>Dashboard');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/dashboard/overdues' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/dashboard/overdues/" class="dropdown-item">');
                                }
                                echo('<i data-feather="pie-chart" class="mr-1"></i>Overdue Monitoring');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/dashboard/outstanding-per-client' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/dashboard/outstanding-per-client/" class="dropdown-item">');
                                }
                                echo('<i data-feather="pie-chart" class="mr-1"></i>Outstanding per Client');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Statistics</label>');

                                if( $_SESSION['page'] === 'stats/currency_change' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/currency_change/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>Currency Change');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/iris_activity' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/iris_activity/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>Iris Activity');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Activity</label>');

                                if( $_SESSION['page'] === 'stats/activity/asia' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/activity/asia/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HCA');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/activity/hrc' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/activity/hrc/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HRC');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/activity/hcm' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/activity/hcm/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HCM');
                                echo('</a>');

                                echo('<div class="dropdown-divider"></div>');
                                echo('<label class="mx-3">Positions</label>');


                                if( $_SESSION['page'] === 'stats/positions/asia' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/positions/asia/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HCA');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/positions/hrc' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/positions/hrc/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HRC');
                                echo('</a>');

                                if( $_SESSION['page'] === 'stats/positions/hcm' ){
                                    echo('<a class="dropdown-item text-primary disabled">');
                                }
                                else{
                                    echo('<a href="/stats/positions/hcm/" class="dropdown-item">');
                                }
                                echo(' <i data-feather="pie-chart" class="mr-1"></i>HCM');
                                echo('</a>');
                            ?>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="col-auto">
                <div class="header-wrap float-right my-2 pt-1">
                    <div class="header-button">
                        <div class="dropdown px-3">
                            <div class="dropdown-toggle text-right" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- <img src="<?=ROOT_URL?>/images/users/user-dimitri-rusca.png" alt="Dimitri RUSCA - SCCF" class="rounded-circle mr-3" style="max-width: 40px;"/> -->
                                <h6 class="name mb-0">
                                    <?= $user_data['user-firstname'] ?> <?= $user_data['user-lastname'] ?>
                                </h6>
                                <span class="email"><?=$_SESSION['user-email-address']?></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right py-0 mt-2">
                                <div class="dropdown-item">
                                    <div class="py-2">
                                        <h6 class="name mb-0">
                                            <?= $user_data['user-firstname'] ?> <?= $user_data['user-lastname'] ?>
                                        </h6>
                                        <span class="email"><?= $user_data['user-profile'] ?></span>
                                    </div>
                                </div>
                                <div class="dropdown-divider my-0"></div>
                                <a href="/tools/" class="py-2 dropdown-item">
                                    <i data-feather="package" class="mr-3"></i> Tools
                                </a>
                                <a href="/ressources/" class="py-2 dropdown-item">
                                    <i data-feather="book" class="mr-3"></i> Ressources
                                </a>
                                <a href="/user/settings/" class="py-2 dropdown-item">
                                    <i data-feather="settings" class="mr-3"></i> Setting
                                </a>
                                <?php
                                    if( $user_data['user-profile'] === "administrator" ){
                                        echo('<div class="dropdown-divider my-0"></div>');
                                        echo('<a href="/api-doc/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="book" class="mr-3"></i> API Documentation');
                                        echo('</a>');
                                        echo('<a href="/user/users-management/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="users" class="mr-3"></i> Users management');
                                        echo('</a>');
                                        echo('<a href="/logs/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="list" class="mr-3"></i> Logs');
                                        echo('</a>');
                                        echo('<a href="/redis/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="database" class="mr-3"></i> Redis');
                                        echo('</a>');
                                        echo('<a href="'.ROOT_URL.':8080" class="py-2 dropdown-item" target="_blank">');
                                            echo('<i data-feather="database" class="mr-3"></i> PHPMyAdmin');
                                        echo('</a>');
                                    }
                                ?>
                                <div class="dropdown-divider my-0"></div>
                                <a href="/logout/" class="py-2 dropdown-item">
                                    <i data-feather="power" class="mr-3"></i> Logout
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn text-white bg-gradient border-0 rounded-pill ml-2 py-2 px-3" data-toggle="modal" data-target="#ActionsModal" title="Access to forms for adding data">
                            <i data-feather="file-plus" class="mr-1"></i> Actions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
