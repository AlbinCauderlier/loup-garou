<?php
// ---------------------------------
//        AUTHENTICATION
// ---------------------------------
//Commun
define("FIELD_EMAIL","Email");
define("FIELD_PASSWORD","Password");
// TODO - Remplacer par le précédent !
define("PARAM_PASSWORD_TITLE","Mot de passe");

// Sign up
define("SIGN_UP_TITLE","Sign up Argos Logistic");
define("SIGN_UP_DESCRIPTION","Sign up Argos Logistic to register your public bitcoin address.");
define("SIGN_UP_KEYWORDS","Argos Logistic, Bitcoin");

define("SIGN_UP_BUTTON","Sign up");

// Log in
define("CONNEXION_TITLE","Log in");
define("CONNEXION_DESCRIPTION","Log in");
define("CONNEXION_KEYWORDS","Argos Logistic, Bitcoin");

define("CONNEXION_PROCESS_TITLE","Log in");
define("CONNEXION_BUTTON","Log in");
define("PASSWORD_FORGOTTEN","Forgot password?");

// Authentication
define("AUTHENTICATION_TITLE","Sign up or log in MuBiz");
define("AUTHENTICATION_DESCRIPTION","Forms to sign up or log in MuBiz");
define("AUTHENTICATION_KEYWORDS","Argos Logistic, sign up, log in");

// Set password
define("FIELD_PASSWORD_1","Password");
define("FIELD_PASSWORD_2","Confirm password");
define("SET_PASSWORD_BUTTON","Save password");
define("SET_PASSWORD_TITLE","Set your password");

// Change password
define("FIELD_OLD_PASSWORD","Old password");
define("FIELD_NEW_PASSWORD_1","New password");
define("FIELD_NEW_PASSWORD_2","Confirm new password");
define("CHANGE_PASSWORD_BUTTON","Change the password");

// Forgotten password
define("FORGOTTEN_PASSWORD_TITLE","Forgotten password");
define("FORGOTTEN_PASSWORD_DESCRIPTION","Forgotten password form for Argos Logistic");
define("FORGOTTEN_PASSWORD_KEYWORDS","Argos Logistic, Bitcoin");

define("FORGOTTEN_PASSWORD_PROCESS_TITLE","Search your Argos Logistic account");
define("FORGOTTEN_PASSWORD_PROCESS_DETAILS","Enter your e-mail");
define("FORGOTTEN_PASSWORD_BUTTON","Search");

// Logout
define("LOGOUT_TITLE","Thank you for using MuBiz");
define("LOGOUT_DESCRIPTION","Log out MuBiz");
define("LOGOUT_KEYWORDS","Argos Logistic, log out");

define("LOGOUT_NOTIFICATION","Vous avez été déconnecté");
define("LOGOUT_MAIN_TITLE","Merci d'utiliser Argos Logistic");

// Set password
define("SETPASSWORD_TITLE","Saisie du mot de passe");
define("SETPASSWORD_DESCRIPTION","Argos Logistic simplifie votre adresse publique, pour que vous receviez plus facilement vos paiements en Bitcoin.");
define("SETPASSWORD_KEYWORDS","Argos Logistic, Bitcoin");


// ---------------------------------
//        SETTINGS
// ---------------------------------
define("PARAM_DISCONNEXION_TITLE","Se déconnecter de Argos Logistic");
define("PARAM_DISCONNEXION_BUTTON","Déconnexion");
define("PARAM_DESACTIVATION_BUTTON","Désactiver mon compte (Reste à faire)");
define("PARAM_DESACTIVATION_TITLE","Désactiver son compte de Argos Logistic");
define("PARAM_UNSUBSCRIBE_BUTTON","Désinscription (Reste à faire)");
define("PARAM_UNSUBSCRIBE_TITLE","Se désinscrire de Argos Logistic");


// ---------------------------------
//        ERROR MESSAGES
// ---------------------------------
define("AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE","You sent the form from an unknow URL.");
define("AUTHENTICATION_ERROR_INVALID_EMAIL","The email field is not valid.");
define("AUTHENTICATION_ERROR_INVALID_PASSWORD","The password field is not valid.");
define("AUTHENTICATION_ERROR_LOGIN_ALREADY_USED","This e-mail is already registered.");
define("AUTHENTICATION_ERROR_UNKNOWN_LOGIN","This login is not recorded. Please sign in, first.");
define("AUTHENTICATION_ERROR_WRONG_PASSWORD","Unvalid password");
define("AUTHENTICATION_ERROR_INVALID_OTP","Not valid data provided");
define("AUTHENTICATION_EMAIL_REGISTRATION_OK","You just received an email, for confirmation, at : <br/>");
define("AUTHENTICATION_EMAIL_LINK","Check my email on ");

define("UNKNOWN_EMAIL_ERROR_MESSAGE","The e-mail is unknown. Please sign in.");

define("EMPTY_PASSWORD_ERROR_MESSAGE","Your e-mail has been confirmed. Please set a password.");

define("PLEASE_LOG_IN_ERROR_MESSAGE","Your log in has been registered and confirmed. Please log in thanks your password.");
define("WRONG_OTP_ERROR_MESSAGE","The sent data is not valid.");

define("UNCONFIRMED_EMAIL_ERROR_MESSAGE","Your e-mail is not confirmed. Please check your confirmation e-mail (including spam).");
define("NO_PASSWORD_SET_ERROR_MESSAGE","You did set a password. Please check your confirmation e-mail (including spam).");

define("DIFFERENT_NEW_PASSWORDS_ERROR_MESSAGE","The new passwords are not the same.");

define("AUTHENTICATION_PASSWORD_CHANGED_OK","Password has been changed.");

define("AUTHENTICATION_PASSWORD_RESET_OK","Password has been initialized.");


// ---------------------------------
//        AUTHENTICATION E-MAILS
// ---------------------------------
// Demande de confirmation de l'adresse e-mail : Mail envoyé lors de l'inscription ou lors d'un changement d'adresse e-mail
define("EMAIL_VERIFICATION_SUBJECT","Please confirm your email address");
define("EMAIL_VERIFICATION_TITLE","Argos Logistic - Please verify your email address");

define("EMAIL_VERIFICATION_CONSIGNE_1","Please click the link below to complete email address verification:");
define("EMAIL_VERIFICATION_BUTTON","Confirm my email address");
define("EMAIL_VERIFICATION_CONSIGNE_2","If you did not sign up for this account, ignore this email. Your email address will be deleted after 7 days.");

// Confirmation de l'activation du compte suite à la confirmation de la 1ère adresse e-mail
define("REGISTRATION_CONFIRMATION_SUBJECT","Your email address is now confirmed");
define("REGISTRATION_CONFIRMATION_TITLE","Argos Logistic - Registration confirmation");
define("REGISTRATION_CONFIRMATION_DETAILS","Your email address has been confirmed and your account is now active.");

// Confirmation de l'enregistrement de la demande de désinscription. Le compte a été désactivé (pas d'affichage de QR Code) dès la demande de l'utilisateur + un message est affiché sur la page de paramétrage
define("UNSUBSCRIPTION_REGISTRATION_TITLE","Argos Logistic - Unsubscription recorded");
define("UNSUBSCRIPTION_REGISTRATION_DETAILS","Nous vous confirmons par le présent e-mail, l'enregistrement de votre demande de désinscription et la désactivation de votre compte.");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE1","Si vous n'êtes pas à l'origine de cette demande de désinscription, merci de cliquer sur le lien ci-dessous pour réactiver votre compte.");
define("UNSUBSCRIPTION_REGISTRATION_CANCELLATION_BUTTON","Réactiver mon compte");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE2","Sans retour de votre part sous un délai de 3 mois, votre compte sera automatiquement désinscrit.");

// TODO - Prévoir un message de rappel à 1 mois de l'échéance.

// Confirmation de la désinscription du compte, suite à 3 mois sans annulation.
define("UNSUBSCRIPTION_CONFIRMATION_TITLE","Argos Logistic - Confirmation de votre désinscription");
define("UNSUBSCRIPTION_CONFIRMATION_DETAILS","Suite à votre demande de désinscription du ../../...., vous disposiez d'un délai de 3 mois pour confirmer ou annuler votre désinscription aux services Argos Logistic. N'ayant pas reçu d'annulation de votre part, nous vous confirmons, par cet e-mail, la suppression des données vous concernant.");

// Vérification de l'adresse e-mail suite à une demande de réinitialisation du mot de passe (forgotten_password)
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_SUBJECT","Argos Logistic - Please confirm your email address");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_TITLE","MuBiz - Mot de passe oublié - Vérification de l'adresse e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_1","Following your request for a forgotten password, please confirm your email address thanks thoe link below.");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_BUTTON","Confirm my email address");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_2","If you did not sign up for this account, ignore this email. Your request will be deleted after 7 days or a valid authentication.");

?>