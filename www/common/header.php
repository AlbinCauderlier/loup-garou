<header class="navbar fixed-top navbar-expand-lg bg-white shadow py-0 d-print-none" role="header">
    <div class="container-fluid">
        <a href="/clients/" class="navbar-brand py-0" title="Loups Garous home">
            <img src="<?=ROOT_URL?>/images/iris-logo2.png" style="max-height:63px; width: 72px;" alt="Loups Garous"/>
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
                                <a href="/user/settings/" class="py-2 dropdown-item">
                                    <i data-feather="settings" class="mr-3"></i> Setting
                                </a>
                                <?php
                                    if( $user_data['user-profile'] === "administrator" ){
                                        echo('<a href="/user/users-management/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="users" class="mr-3"></i> Users management');
                                        echo('</a>');
                                        echo('<a href="/logs/" class="py-2 dropdown-item">');
                                            echo('<i data-feather="list" class="mr-3"></i> Logs');
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
                            <i data-feather="file-plus" class="mr-1"></i> Créer un nouveau village
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
