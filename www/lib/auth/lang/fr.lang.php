<?php
// ---------------------------------
//        AUTHENTICATION
// ---------------------------------
// Commun
define("FIELD_EMAIL","Adresse e-mail");
define("FIELD_PASSWORD","Mot de passe");
// TODO - Remplacer par le précédent !
define("PARAM_PASSWORD_TITLE","Mot de passe");

// Menu title
define("LOGIN_TITLE","Se connecter");

// Sign up
define("SIGN_UP_TITLE","S'inscrire à Argos Logistic");
define("SIGN_UP_DESCRIPTION","S'inscrire à Argos Logistic permet d'acheter des bitcoins, de lancer un projet et de soutenir des initiatives.");
define("SIGN_UP_KEYWORDS","Argos Logistic, Bitcoin");
define("SIGN_UP_BUTTON","Inscription");

// Log in
define("CONNEXION_TITLE","Se connecter à Argos Logistic");
define("CONNEXION_DESCRIPTION","Formulaire de connexion à Argos Logistic");
define("CONNEXION_KEYWORDS","Argos Logistic, Bitcoin");

define("CONNEXION_PROCESS_TITLE","Se connecter à Argos Logistic");
define("CONNEXION_BUTTON","Connexion");
define("PASSWORD_FORGOTTEN","Mot de passe oublié ?");

// Authentication
define("AUTHENTICATION_TITLE","S'inscrire ou se connecter à Argos Logistic");
define("AUTHENTICATION_DESCRIPTION","Formulaires d'inscription et de connexion à Argos Logistic");
define("AUTHENTICATION_KEYWORDS","Argos Logistic, inscription, connexion");

// Set password
define("FIELD_PASSWORD_1","Mot de passe");
define("FIELD_PASSWORD_2","Confirmation du mot de passe");
define("SET_PASSWORD_BUTTON","Enregistrer");
define("SET_PASSWORD_TITLE","Saisie du mot de passe");

// Change password
define("FIELD_OLD_PASSWORD","Ancien mot de passe");
define("FIELD_NEW_PASSWORD_1","Nouveau mot de passe");
define("FIELD_NEW_PASSWORD_2","Confirmation du nouveau mot de passee");
define("CHANGE_PASSWORD_BUTTON","Changer de mot de passe");

// Forgotten password
define("FORGOTTEN_PASSWORD_TITLE","Réinitialisation du mot de passe oublié");
define("FORGOTTEN_PASSWORD_DESCRIPTION","Réinitialisation du mot de passe Argos Logistic");
define("FORGOTTEN_PASSWORD_KEYWORDS","Argos Logistic, Bitcoin");

define("FORGOTTEN_PASSWORD_PROCESS_TITLE","Retrouvez votre compte Argos Logistic");
define("FORGOTTEN_PASSWORD_PROCESS_DETAILS","Indiquez votre adresse e-mail");
define("FORGOTTEN_PASSWORD_BUTTON","Cherche");

// Logout
define("LOGOUT_TITLE","Merci d'utiliser Argos Logistic");
define("LOGOUT_DESCRIPTION","Déconnexion de Argos Logistic");
define("LOGOUT_KEYWORDS","Argos Logistic, Déconnexion");

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
define("PARAM_ACCOUNT_DESACTIVATION_BUTTON","Désactiver mon compte (Reste à faire)");
define("PARAM_ACCOUNT_DESACTIVATION_TITLE","Désactiver son compte de Argos Logistic");
define("PARAM_UNSUBSCRIBE_BUTTON","Désinscription (Reste à faire)");
define("PARAM_UNSUBSCRIBE_TITLE","Se désinscrire de Argos Logistic");


// ---------------------------------
//        ERROR MESSAGES
// ---------------------------------
define("AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE","L'adresse d'émission du formulaire est invalide.");
define("AUTHENTICATION_ERROR_INVALID_EMAIL","Le champs E-mail est invalide.");
define("AUTHENTICATION_ERROR_INVALID_PASSWORD","Le champs Password est invalide.");
define("AUTHENTICATION_ERROR_LOGIN_ALREADY_USED","L'identifiant indiqué est déjà utilisé.");
define("AUTHENTICATION_ERROR_UNKNOWN_LOGIN","Cet identifiant est inconnu. Inscrivez-vous pour accéder à Argos Logistic.");
define("AUTHENTICATION_ERROR_WRONG_PASSWORD","Mot de passe erroné.");
define("AUTHENTICATION_ERROR_INVALID_OTP","Données fournies invalides.");
define("AUTHENTICATION_EMAIL_REGISTRATION_OK","Vous avez reçu un e-mail de confirmation à l'adresse <br/>");
define("AUTHENTICATION_EMAIL_LINK","Consulter mes e-mails sur ");

define("UNKNOWN_EMAIL_ERROR_MESSAGE","L'adresse e-mail est inconnue. Veuillez vous inscrire.");

define("EMPTY_PASSWORD_ERROR_MESSAGE","Votre adresse e-mail a été confirmée.");

define("PLEASE_LOG_IN_ERROR_MESSAGE","Votre identifiant a bien été enregistré et confirmé. Merci de vous authentifier à l'aide de votre mot de passe.");
define("WRONG_OTP_ERROR_MESSAGE","La donnée fournie n'est pas valide.");

define("UNCONFIRMED_EMAIL_ERROR_MESSAGE","Votre adresse e-mail n'a pas été confirmée. Veuillez consulter (dont les spams) votre e-mail de confirmation.");
define("NO_PASSWORD_SET_ERROR_MESSAGE","Vous avez confirmé votre adresse e-mail mais n'avez pas saisi de mot de passe. Veuillez consulter votre e-mail de confirmation.");

define("DIFFERENT_NEW_PASSWORDS_ERROR_MESSAGE","Les nouveaux mots de passe saisis sont différents.");

define("AUTHENTICATION_PASSWORD_CHANGED_OK","Le mot de passe a été modifié.");

define("AUTHENTICATION_PASSWORD_RESET_OK","Le mot de passe a été réinitialisé.");


// ---------------------------------
//        AUTHENTICATION E-MAILS
// ---------------------------------
// Demande de confirmation de l'adresse e-mail : Mail envoyé lors de l'inscription ou lors d'un changement d'adresse e-mail 
define("EMAIL_VERIFICATION_SUBJECT","Veuillez confirmer votre adresse e-mail");
define("EMAIL_VERIFICATION_TITLE","Argos Logistic - Demande de confirmation de l'adresse e-mail");

define("EMAIL_VERIFICATION_CONSIGNE_1","Merci de confirmer votre adresse e-mail, en cliquant sur le lien ci-dessous.");
define("EMAIL_VERIFICATION_BUTTON","Confirmer cette adresse électronique");
define("EMAIL_VERIFICATION_CONSIGNE_2","Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer ce message. Votre adresse e-mail sera supprimée automatiquement après 7 jours.");

// Confirmation de l'activation du compte suite à la confirmation de la 1ère adresse e-mail
define("REGISTRATION_CONFIRMATION_SUBJECT","Confirmation de votre adresse e-mail");
define("REGISTRATION_CONFIRMATION_TITLE","Argos Logistic - Confirmation de votre inscription");
define("REGISTRATION_CONFIRMATION_DETAILS","Votre adresse e-mail a été confirmée.");


define("CHANGED_PASSWORD_NOTIFICATION_TEXT","Votre mot de passe a été correctement modifié.");


// Confirmation de l'enregistrement de la demande de désinscription. Le compte a été désactivé (pas d'affichage de QR Code) dès la demande de l'utilisateur + un message est affiché sur la page de paramétrage
define("UNSUBSCRIPTION_REGISTRATION_TITLE","Argos Logistic - Confirmation de votre désinscription");
define("UNSUBSCRIPTION_REGISTRATION_DETAILS","Nous vous confirmons par le présent e-mail, l'enregistrement de votre demande de désinscription et la désactivation de votre compte.");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE1","Si vous n'êtes pas à l'origine de cette demande de désinscription, merci de cliquer sur le lien ci-dessous pour réactiver votre compte.");
define("UNSUBSCRIPTION_REGISTRATION_CANCELLATION_BUTTON","Réactiver mon compte");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE2","Sans retour de votre part sous un délai de 3 mois, votre compte sera automatiquement désinscrit.");

// TODO - Prévoir un message de rappel à 1 mois de l'échéance.

// Confirmation de la désinscription du compte, suite à 3 mois sans annulation.
define("UNSUBSCRIPTION_CONFIRMATION_TITLE","Argos Logistic - Confirmation de votre désinscription");
define("UNSUBSCRIPTION_CONFIRMATION_DETAILS","Suite à votre demande de désinscription du ../../...., vous disposiez d'un délai de 3 mois pour confirmer ou annuler votre désinscription aux services Argos Logistic. N'ayant pas reçu d'annulation de votre part, nous vous confirmons, par cet e-mail, la suppression des données vous concernant.");

// Vérification de l'adresse e-mail suite à une demande de réinitialisation du mot de passe (forgotten_password)
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_SUBJECT","Veuillez confirmer votre adresse e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_TITLE","Argos Logistic - Mot de passe oublié - Vérification de l'adresse e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_1","Suite à votre demande d'initialisation de mot de passe, veuillez confirmer votre adresse e-mail en cliquant sur le lien ci-dessous.");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_BUTTON","Confirmer cette adresse e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_2","Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer ce message.");

?>