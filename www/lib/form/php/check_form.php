<?php  

/**
 *	Vérifie qu'un champ obligatoire a bien été renseigné.
 *
 *	@param	$field	La valeur à contrôler
 *
 *	@return		True si la valeur est renseignée
 *			False si la valeur n'est pas renseignée ou vide.
 */
function checkMandatory($field){
	if(empty($field)){
		return false;
	}
	return true;
}



/**
 *	Vérifie qu'un champ obligatoire a bien été renseigné dans un formulaire POST.
 *
 *	@param	$label		Label du champ à contrôler
 *
 *	@return		True si la valeur est renseignée
 *				False si la valeur n'est pas renseignée ou vide.
 */
function checkPostMandatory($label){
	if( !isset($_POST[$label]) || empty($_POST[$label])){
		return false;
	}
	return true;
}

/**
 *	Vérifie qu'un champ obligatoire a bien été renseigné dans un formulaire POST.
 *
 *	@param	$label		Label du champ à contrôler
 *
 *	@return		True si la valeur est renseignée
 *				False si la valeur n'est pas renseignée ou vide.
 */
function checkGetMandatory($label){
	if( !isset($_GET[$label]) || empty($_GET[$label])){
		return false;
	}
	return true;
}



/**
 *	Vérifie qu'un champ respecte les contraintes de taille max et taille min.
 *
 *	@param	$field	La valeur à contrôler
 *
 *	@return		True si la valeur est respecte les longueurs imposées
 *			False si la valeur est trop longue ou trop courte.
 */
function checkLenght($field,$min,$max){
	if(strlen($field)<$min || strlen($field)>$max){
		return false;
	}
	return true;
}

/**
 *	Vérifie qu'un e-mail respecte les règles de construction.
 *
 *	@param	$field	La valeur à contrôler
 *
 *	@return		True si l'adresse e-mail est correctement renseignée
 *				False si l'adresse e-mail est invalide.
 */
function checkEmail($field){
	if(filter_var($field,FILTER_VALIDATE_EMAIL)){
		return true;
	}
	return false;
}


function checkCaracteresSpeciaux($field){
	//if(preg_match("/[^a-zA-Z0-9-_\.@]/",$field)){
	//if(preg_match("/^[a-zA-Z0-9-_]/",$field)){
	//if(preg_match("/^[0-9a-zA-ZÀ-Öß-öù-ÿĀ-ž-_]/",$field)){
	if(preg_match("/^[a-zA-ZÀ-Öß-ö0-9_]/",$field)){
		return true;
	}
	return false;
}





?>