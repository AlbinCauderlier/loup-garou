<?php
// ---------------------------------
//        AUTHENTICATION
// ---------------------------------
// Commun
define("FIELD_EMAIL","Dirección e-mail");
define("FIELD_PASSWORD","Contraseña");
// TODO - Remplacer par le précédent !
define("PARAM_PASSWORD_TITLE","Contraseña");

// Menu title
define("LOGIN_TITLE","Conectarse");

// Sign up
define("SIGN_UP_TITLE","Registrarse en Argos Logistic");
define("SIGN_UP_DESCRIPTION","Inscribirse en Argos Logistic permite comprar y vender productos a través de la plataforma Openbazaar.");
define("SIGN_UP_KEYWORDS","Argos Logistic, Bitcoin");

define("SIGN_UP_BUTTON","Registrarse");

// Log in
define("CONNEXION_TITLE","Conectarse a Argos Logistic");
define("CONNEXION_DESCRIPTION","Formulario de conexíón a Argos Logistic");
define("CONNEXION_KEYWORDS","Argos Logistic, Bitcoin");

define("CONNEXION_PROCESS_TITLE","Conectarse a Argos Logistic");
define("CONNEXION_BUTTON","Conexión");
define("PASSWORD_FORGOTTEN","¿Contraseña olvidada?");

// Authentication
define("AUTHENTICATION_TITLE","Registrarse o conectarse a Argos Logistic");
define("AUTHENTICATION_DESCRIPTION","Formularios de conexión y registro a Argos Logistic");
define("AUTHENTICATION_KEYWORDS","Argos Logistic, registro conexión");

// Set password
define("FIELD_PASSWORD_1","Contraseña");
define("FIELD_PASSWORD_2","Confirmar contraseña");
define("SET_PASSWORD_BUTTON","Registro");
define("SET_PASSWORD_TITLE","Introduce tu contraseña");

// Change password
define("FIELD_OLD_PASSWORD","Antigua contraseña");
define("FIELD_NEW_PASSWORD_1","Nueva contraseña");
define("FIELD_NEW_PASSWORD_2","Confirmar nueva contraseña");
define("CHANGE_PASSWORD_BUTTON","Cambiar contraseña");

// Forgotten password
define("FORGOTTEN_PASSWORD_TITLE","Reinicializar contraseña olvidada");
define("FORGOTTEN_PASSWORD_DESCRIPTION","Reinicializar contraseña Argos Logistic");
define("FORGOTTEN_PASSWORD_KEYWORDS","Argos Logistic, Bitcoin");

define("FORGOTTEN_PASSWORD_PROCESS_TITLE","Encuentra tu cuneta Argos Logistic");
define("FORGOTTEN_PASSWORD_PROCESS_DETAILS","Indica tu dirección e-mail");
define("FORGOTTEN_PASSWORD_BUTTON","Buscar");

// Logout
define("LOGOUT_TITLE","Gracias por utilizar Argos Logistic");
define("LOGOUT_DESCRIPTION","Desconectarse de Argos Logistic");
define("LOGOUT_KEYWORDS","Argos Logistic, Desconexión");

define("LOGOUT_NOTIFICATION","Has sido desconectado");
define("LOGOUT_MAIN_TITLE","Gracias por utilizar Argos Logistic");

// Set password
define("SETPASSWORD_TITLE","Introduce tu contraseña");
define("SETPASSWORD_DESCRIPTION","Contraseñas");
define("SETPASSWORD_KEYWORDS","Argos Logistic, Bitcoin");


// ---------------------------------
//        SETTINGS
// ---------------------------------
define("PARAM_DISCONNEXION_TITLE","Desconectarse de Argos Logistic");
define("PARAM_DISCONNEXION_BUTTON","Desconexión");
define("PARAM_DESACTIVATION_BUTTON","Desactivar mi cuenta (Reste à faire)");
define("PARAM_DESACTIVATION_TITLE","Désactivar su cuenta Argos Logistic");
define("PARAM_UNSUBSCRIBE_BUTTON","Desinscribirse (Reste à faire)");
define("PARAM_UNSUBSCRIBE_TITLE","Desinscribirse de Argos Logistic");


// ---------------------------------
//        ERROR MESSAGES
// ---------------------------------
define("AUTHENTICATION_ERROR_FORMULAIRE_INVALIDE","La dirección de emisión del formulario es inválida.");
define("AUTHENTICATION_ERROR_INVALID_EMAIL","El campo e-mail es inválido.");
define("AUTHENTICATION_ERROR_INVALID_PASSWORD","El campo contraseña es inválida.");
define("AUTHENTICATION_ERROR_LOGIN_ALREADY_USED","El nombre de usuario indicado ya está utilizado.");
define("AUTHENTICATION_ERROR_UNKNOWN_LOGIN","El nombre de usuario no es conocido.");
define("AUTHENTICATION_ERROR_WRONG_PASSWORD","Contraseña errónea.");
define("AUTHENTICATION_ERROR_INVALID_OTP","Los datos introducidos son inválidos.");
define("AUTHENTICATION_EMAIL_REGISTRATION_OK","Un e-mail de confirmación ha sido enviado a la dirección e-mail <br/>");
define("AUTHENTICATION_EMAIL_LINK","Consultar los e-mails sobre ");

define("UNKNOWN_EMAIL_ERROR_MESSAGE","La dirección e-mail no es conocida.");

define("EMPTY_PASSWORD_ERROR_MESSAGE","La dirección e-mail ha sido confirmada.");

define("PLEASE_LOG_IN_ERROR_MESSAGE","El nombre de usuario ha sido registrado y confirmado. Gracias por autenticarse con su contraseña.");
define("WRONG_OTP_ERROR_MESSAGE","El dato introducido no es válido.");

define("UNCONFIRMED_EMAIL_ERROR_MESSAGE","La dirección e-mail no ha sido confirmada. Por favor, consulte su e-amil y recuerde revisar el correo no deseado.");
define("NO_PASSWORD_SET_ERROR_MESSAGE","Has confirmado tu dirección e-mail pero no has introducido la contraseña.");

define("DIFFERENT_NEW_PASSWORDS_ERROR_MESSAGE","Las contraseñas introducidas no coinciden.");

define("AUTHENTICATION_PASSWORD_CHANGED_OK","La contraseña ha sido modificada.");

define("AUTHENTICATION_PASSWORD_RESET_OK","La contraseña ha sido reinicializada.");


// ---------------------------------
//        AUTHENTICATION E-MAILS
// ---------------------------------
// Demande de confirmation de l'adresse e-mail : Mail envoyé lors de l'inscription ou lors d'un changement d'adresse e-mail 
define("EMAIL_VERIFICATION_SUBJECT","Por favor, confirma tu dirección e-mail");
define("EMAIL_VERIFICATION_TITLE","Argos Logistic - Confirmación de la dirección e-mail");

define("EMAIL_VERIFICATION_CONSIGNE_1","Gracias por confirmar la dirección e-mail haciendo click sobre el siguiente enlace:");
define("EMAIL_VERIFICATION_BUTTON","Confirma la dirección eletrónica");
define("EMAIL_VERIFICATION_CONSIGNE_2","Si usted no ha realizado ésta inscripción, por favor ignore éste mensaje. La dirección será suprimida en los próximos 7 días.");

// Confirmation de l'activation du compte suite à la confirmation de la 1ère adresse e-mail
define("REGISTRATION_CONFIRMATION_SUBJECT","Confirmación de la dirección e-mail");
define("REGISTRATION_CONFIRMATION_TITLE","Argos Logistic - Confirmación del registro");
define("REGISTRATION_CONFIRMATION_DETAILS","Tu dirección e-mail ha sido confirmada.");

// Confirmation de l'enregistrement de la demande de désinscription. Le compte a été désactivé (pas d'affichage de QR Code) dès la demande de l'utilisateur + un message est affiché sur la page de paramétrage
define("UNSUBSCRIPTION_REGISTRATION_TITLE","Argos Logistic - Confirmación de la desinscripción");
define("UNSUBSCRIPTION_REGISTRATION_DETAILS","Le confirmamos su desinscripción de Argos Logistic y la desactivación de su cuenta.");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE1","Si usted no ha realizado ésta petición de desinscripción, por favor haga click en el siguiente enlace para reactivar la cuenta.");
define("UNSUBSCRIPTION_REGISTRATION_CANCELLATION_BUTTON","Reactivar la cuenta");
define("UNSUBSCRIPTION_REGISTRATION_CONSIGNE2","Si no tenemos respuesta por su parte en un máximo de 3 meses, su cuenta será automaticamente desinscrita.");

// TODO - Prévoir un message de rappel à 1 mois de l'échéance.

// Confirmation de la désinscription du compte, suite à 3 mois sans annulation.
define("UNSUBSCRIPTION_CONFIRMATION_TITLE","Argos Logistic - Confirmación de la desinscripción");
define("UNSUBSCRIPTION_CONFIRMATION_DETAILS","En respuesta a su petición de desinscripción del ../../...., 
	dispone de 3 meses para confirmar o anular su desinscripción de los servicios de Argos Logistic. Si no recibimos durante ese tiempo contestación de su parte, le confirmamos, a través de éste mismo e-mail, el borrado de los datos concercientes.");

// Vérification de l'adresse e-mail suite à une demande de réinitialisation du mot de passe (forgotten_password)
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_SUBJECT","Por favor, confirme la dirección e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_TITLE","Argos Logistic - Contraseña olvidada - Verificación de la dirección e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_1","En respuesta a la petición de reinicialización de contraseña, necesitamos que confirme la dirección e-mail haciendo click en el siguiente enlance.");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_BUTTON","Confirmar la dirección e-mail");
define("FORGOTTEN_PASSWORD_EMAIL_VERIFICATION_CONSIGNE_2","Si ústed no ha ŕealizado ésta petición, ignore el mensaje. Gracias.");

?>