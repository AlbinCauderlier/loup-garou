                        <?php
                            require_once("controller/authentication/config.php");
                        ?>
                        <form id="main-form" action="/controller/authentication/change_password.php" method="post">
                            <div class="form-group">
                                <label for="current-password">Current password *</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" id="current-password" name="current-password" type="password" placeholder="Current password *" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new-password1">New password *</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" id="new-password1" name="new-password1" type="password" placeholder="New password *" minlength="<?=AUTHENTICATION_PASSWORD_MIN_LENGHT?>" required/>
                                </div>
                                <small class="form-text text-muted text-right">min = <?=AUTHENTICATION_PASSWORD_MIN_LENGHT?> letters</small>
                            </div>
                            <div class="form-group">
                                <label for="new-password2">Confirmation of the new password *</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" id="new-password2" name="new-password2" type="password" placeholder="Confirmation of the new password *" minlength="<?=AUTHENTICATION_PASSWORD_MIN_LENGHT?>" required/>
                                </div>
                                <small class="form-text text-muted text-right">min = <?=AUTHENTICATION_PASSWORD_MIN_LENGHT?> letters</small>
                            </div>
                            <button class="btn btn-gradient p-2 mb-5" type="submit">
                                <i data-feather="lock" class="mr-2"></i> Change password
                            </button>
                        </form>