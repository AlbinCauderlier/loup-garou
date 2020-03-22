                        <form id="main-form" action="" method="post" class="mb-5 needs-validation" novalidate>
                            <h3>Le village</h3>
                            <label for="village-name">Nom du village *</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i data-feather="tag"></i>
                                    </div>
                                </div>
                                <input class="form-control" id="user-alias" name="user-alias" type="text" placeholder="Alias" value="<?=$user['user-alias']?>" required/>
                            </div>
                            <hr class="my-5"/>
                            <h3>Les habitants</h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i data-feather="tag"></i>
                                    </div>
                                </div>
                                <select id="user-language" name="user-language" class="form-control custom-select border-left-0" required>
                                    <option disabled selected>Profile</option>
                                    <option value="citizen">Villageois</option>
                                    <option value="werewolf">Loup Garou</option>
                                    <option value="witch">Sorcière</option>
                                    <option value="littlegirl">Petite Fille</option>
                                    <option value="hunter">Chasseur</option>
                                    <option value="cupid">Cupidon</option>
                                </select>
                            </div>
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="save" class="mr-2"></i> Save
                            </button>
                        </form>
