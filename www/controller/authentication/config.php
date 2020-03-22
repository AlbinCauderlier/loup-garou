<?php

/* **********************************
 * AUTHENTICATION CONFIG
 ************************************/

/**
 * CONTROL
 */
define("AUTHENTICATION_EMAIL_MAX_LENGHT",80);
define("AUTHENTICATION_PASSWORD_MIN_LENGHT",6);
define("AUTHENTICATION_PASSWORD_MAX_LENGHT",80);

/**
 * VIEWS
 */
define("AUTHENTICATION_FORM_PAGE",ROOT_URL);
//define("AUTHENTICATION_REGISTRATION_PAGE",ROOT_URL."/signup");
define("AUTHENTICATION_LOGIN_PAGE",ROOT_URL);
define("AUTHENTICATION_LOGOUT_PAGE",ROOT_URL."/logout");
define("AUTHENTICATION_FIRST_PAGE",ROOT_URL."/villages");

define("SECURITY_PAGE",ROOT_URL."/user/settings/");
define("AUTHENTICATION_CHANGE_PASSWORD_PAGE",ROOT_URL."/user/settings/");

define("SETTINGS_PAGE",ROOT_URL."/user/settings/");
define("AUTHENTICATION_FIRST_LOGIN_PAGE",ROOT_URL);
define("AUTHENTICATION_FORGOTTEN_PASSWORD_PAGE",ROOT_URL."/forgotten-password");

define("AUTHENTICATION_SIGN_OUT_PAGE",ROOT_URL."/settings/sign-out/");


?>