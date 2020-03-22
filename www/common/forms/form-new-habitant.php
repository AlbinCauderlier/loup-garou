                        <form id="habitant-form" action="/controller/villages/new-village.php" method="post" class="mb-5 needs-validation" novalidate>
                            <h3>Choisir un village</h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i data-feather="tag"></i>
                                    </div>
                                </div>
                                <input class="form-control" id="village-name" name="village-name" type="text" placeholder="Nom du village" required/>
                            </div>
                            <hr class="my-5"/>
                            <h3>Choisir un habitant</h3>
                            <div class="form-row">
                            <?php
                                $users = json_decode(callAPI('GET',API_URL.'/api/users/'), true);

                                foreach( $users as $user ){
                                    echo('<div class="col-md-3">');
                                        echo('<label>'.$user["user-firstname"].'</label><br/>');
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
                            <h3>Choisir un rôle</h3>
                            <div class="form-row">
                            <?php
                                $users = json_decode(callAPI('GET',API_URL.'/api/users/'), true);

                                foreach( $users as $user ){
                                    echo('<div class="col-md-3">');
                                        echo('<label>'.$user["user-firstname"].'</label><br/>');
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
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="home" class="mr-2"></i> Créer le village
                            </button>
                        </form>
