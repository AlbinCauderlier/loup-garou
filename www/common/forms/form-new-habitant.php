                        <form id="habitant-form" action="/controller/villages/new-village.php" method="post" class="mb-5 needs-validation" novalidate>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <h3>Votre village</h3>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="home"></i>
                                            </div>
                                        </div>
                                        <input class="form-control" id="village-name" name="village-name" type="text" placeholder="Nom du village" required readonly/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h3>Choisir un joueur</h3>
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                </div>
                                                <select id="user" name="user-profile" class="form-control custom-select border-left-0" required>
                                                    <option disabled selected>Profile</option>
                                                <?php
                                                    $users = json_decode(callAPI('GET',API_URL.'/api/users/'), true);
                                                    foreach( $users as $user ){
                                                        
                                                        echo('<option value="'.$user["user-id"].'">'.$user["user-firstname"].'</option>');
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h3>Choisir un rôle</h3>
                                    <div class="form-row">
                                    <?php
                                                echo('<div class="input-group mb-2">');
                                                    echo('<div class="input-group-prepend">');
                                                        echo('<div class="input-group-text">');
                                                            echo('<i data-feather="tag"></i>');
                                                        echo('</div>');
                                                    echo('</div>');
                                                    echo('<select id="user-profile" name="'.$user["user-id"].'-user-profile" class="form-control custom-select border-left-0" required>');
                                                        echo('<option disabled selected>Profile</option>');
                                                        echo('<option value="storyteller">Conteur</option>');
                                                        echo('<option value="citizen">Villageois</option>');
                                                        echo('<option value="werewolf">Loup Garou</option>');
                                                        echo('<option value="witch">Sorcière</option>');
                                                        echo('<option value="littlegirl">Petite Fille</option>');
                                                        echo('<option value="hunter">Chasseur</option>');
                                                        echo('<option value="cupid">Cupidon</option>');
                                                    echo('</select>');
                                                echo('</div>');
                                            echo('</div>');
                                        }
                                    ?>
                                </div>
                            </div>
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="home" class="mr-2"></i> Créer le village
                            </button>
                        </form>
