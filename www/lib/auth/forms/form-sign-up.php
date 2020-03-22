                    <form id="signup" action="<?=ROOT_URL?>/controller/authentication/signup.php" method="post">
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i data-feather="user"></i></div>
                                </div>
                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname *" required />
                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Name *" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i data-feather="mail"></i></div>
                                </div>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email address *" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i data-feather="lock"></i></div>
                                </div>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password *" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i data-feather="tag"></i>
                                    </div>
                                </div>
                                <select id="user-language" name="user-language" class="form-control custom-select border-left-0" required>
                                    <option disabled selected>Role *</option>
                                    <option value="relation-manager">Relation Manager</option>
                                    <option value="account-officer">Account Officer</option>
                                    <option value="director">Director</option>
                                    <option value="risk-officer">Risk Officer</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-gradient btn-block p-2 mb-5" type="submit" disabled="">
                            <i data-feather="save" class="mr-2"></i> Create New User
                        </button>
                    </form>