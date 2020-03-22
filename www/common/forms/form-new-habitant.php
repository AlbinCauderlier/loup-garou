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
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="user"></i>
                                            </div>
                                        </div>
                                        <select id="user" name="user-profile" class="form-control custom-select border-left-0" required>
                                            <option disabled selected>Joueur</option>
                                        <?php
                                            $users = json_decode(callAPI('GET',API_URL.'/api/users/'), true);
                                            foreach( $users as $user ){
                                                
                                                echo('<option value="'.$user["user-id"].'">'.$user["user-firstname"].'</option>');
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h3>Choisir une carte</h3>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="tag"></i>
                                            </div>
                                        </div>
                                        <select id="user-card" name="user-card" class="form-control custom-select border-left-0" required>
                                            <option disabled selected>Carte</option>
                                            <option value="storyteller">Conteur</option>
                                            <option value="citizen">Villageois</option>
                                            <option value="werewolf">Loup Garou</option>
                                            <option value="witch">Sorcière</option>
                                            <option value="littlegirl">Petite Fille</option>
                                            <option value="hunter">Chasseur</option>
                                            <option value="cupid">Cupidon</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="home" class="mr-2"></i> Créer le village
                            </button>
                        </form>
