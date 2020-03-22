                        <form id="main-form" action="" method="post" class="mb-5 needs-validation" novalidate>
                            <h3 id="international">International</h3>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="user-language">Language *</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="globe"></i>
                                            </div>
                                        </div>
                                        <select id="user-language" name="user-language" class="form-control custom-select border-left-0" required>
                                            <option disabled selected>User language</option>
                                            <option value="en">English</option>
                                            <option value="fr">French</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="user-currency">Currency *</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="dollar-sign"></i>
                                            </div>
                                        </div>
                                        <select id="user-currency" name="user-currency" class="form-control custom-select border-left-0" required>
                                            <option disabled selected>User currency</option>
                                            <option value="usd">$ - USD</option>
                                            <option value="eur">€ - EUR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="user-timezone">Time zone *</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i data-feather="globe"></i>
                                            </div>
                                        </div>
                                        <select id="user-timezone" name="user-timezone" class="form-control custom-select border-left-0" required>
                                            <option disabled selected>User TimeZone</option>
                                            <option value="+1:00">Paris - Zurich</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="save" class="mr-2"></i> Save
                            </button>
                        </form>
