                        <form id="habitant-form" action="/controller/habitants/new-habitant.php" method="post" class="mb-5">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <h3>Votre village</h3>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="home"></i>
                                            </div>
                                        </div>
                                        <input class="form-control" id="village-id" name="village-id" type="text" value="<?= $village['village-id'] ?>" required readonly/>
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
                                        <select id="user-id" name="user-id" class="form-control custom-select border-left-0" required>
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
                                        <select id="card-name" name="card-name" class="form-control custom-select border-left-0" required>
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
                                <i data-feather="user-plus" class="mr-2"></i> Ajouter l'habitant
                            </button>
                        </form>
