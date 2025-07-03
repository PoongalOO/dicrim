<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');
include_spip('inc/filtres_images_mini');  // Pour le traitement des images

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 */
function formulaires_editer_dicrim_risque_identifier_dist($id_dicrim_risque='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_dicrim_risque)));
}

/**
 * Chargement du formulaire d'édition de risque DICRIM
 */
function formulaires_editer_dicrim_risque_charger_dist($id_dicrim_risque='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs = array();
	
	// Charger les données existantes
	if ($id_dicrim_risque !== 'nouveau' && intval($id_dicrim_risque) > 0) {
		$row = sql_fetsel('*', 'spip_dicrim_risques', 'id_dicrim_risque='.intval($id_dicrim_risque));
		if ($row) {
			$valeurs = $row;
		}
	}
	
	// Valeurs par défaut pour un nouveau risque
	if ($id_dicrim_risque === 'nouveau') {
		$valeurs = array_merge(array(
			'titre' => '',
			'descriptif' => '',
			'type_risque' => '',
			'identification' => '',
			'consequences' => '',
			'mesures_prises' => '',
			'alerte' => '',
			'consignes_securite' => '',
			'ordre' => 10,
			'statut' => 'prepa'
		), $valeurs);
	}
	
	// Ajout des paramètres du formulaire
	$valeurs['id_dicrim_risque'] = $id_dicrim_risque;
	$valeurs['redirect'] = $retour;
	
	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de risque DICRIM
 */
function formulaires_editer_dicrim_risque_verifier_dist($id_dicrim_risque='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$erreurs = array();
	
	// Vérification du titre obligatoire
	if (!_request('titre') || trim(_request('titre')) === '') {
		$erreurs['titre'] = _T('info_obligatoire');
	}
	
	// Vérification de l'ordre
	$ordre = _request('ordre');
	if ($ordre !== '' && $ordre !== null && !is_numeric($ordre)) {
		$erreurs['ordre'] = _T('dicrim:erreur_ordre_numerique');
	}
	
	// Vérification du logo
	if (isset($_FILES['logo_risque']) && $_FILES['logo_risque']['error'] == 0) {
		if ($_FILES['logo_risque']['size'] > 1048576) {
			$erreurs['logo_risque'] = _T('dicrim:erreur_taille_logo');
		}
		$formats_acceptes = array('jpg', 'jpeg', 'png', 'gif');
		$extension = strtolower(pathinfo($_FILES['logo_risque']['name'], PATHINFO_EXTENSION));
		if (!in_array($extension, $formats_acceptes)) {
			$erreurs['logo_risque'] = _T('dicrim:erreur_format_logo');
		}
	}
	
	// Vérification de la vignette
	if (isset($_FILES['vignette_risque']) && $_FILES['vignette_risque']['error'] == 0) {
		if ($_FILES['vignette_risque']['size'] > 1048576) {
			$erreurs['vignette_risque'] = _T('dicrim:erreur_taille_vignette');
		}
		$formats_acceptes = array('jpg', 'jpeg', 'png', 'gif');
		$extension = strtolower(pathinfo($_FILES['vignette_risque']['name'], PATHINFO_EXTENSION));
		if (!in_array($extension, $formats_acceptes)) {
			$erreurs['vignette_risque'] = _T('dicrim:erreur_format_vignette');
		}
	}
	
	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de risque DICRIM
 */
function formulaires_editer_dicrim_risque_traiter_dist($id_dicrim_risque='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$champs = array(
		'titre' => _request('titre'),
		'descriptif' => _request('descriptif'),
		'type_risque' => _request('type_risque'),
		'identification' => _request('identification'),
		'consequences' => _request('consequences'),
		'mesures_prises' => _request('mesures_prises'),
		'alerte' => _request('alerte'),
		'consignes_securite' => _request('consignes_securite'),
		'ordre' => intval(_request('ordre') ?: 10)
	);
	
	// Gestion des images
	
	// Logo
	if (isset($_FILES['logo_risque']) && $_FILES['logo_risque']['error'] == 0) {
		// Création des répertoires si nécessaire
		$dir_logos = _DIR_PLUGIN_DICRIM . 'img/dicrim/logos_risques/';
		if (!is_dir($dir_logos)) {
			mkdir($dir_logos, 0777, true);
		}
		
		// Traitement de l'image
		$extension = strtolower(pathinfo($_FILES['logo_risque']['name'], PATHINFO_EXTENSION));
		$logo_nom = 'risque_' . ($id_dicrim_risque != 'nouveau' ? $id_dicrim_risque : time()) . '.' . $extension;
		$destination = $dir_logos . $logo_nom;
		
		if (move_uploaded_file($_FILES['logo_risque']['tmp_name'], $destination)) {
			$champs['logo_risque'] = $logo_nom;
		}
	}
	
	// Suppression du logo si demandé
	if (_request('supprimer_logo_risque') == 'oui') {
		$champs['logo_risque'] = '';
		
		// Suppression du fichier si existant
		if ($id_dicrim_risque != 'nouveau') {
			$risque = sql_fetsel('logo_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id_dicrim_risque));
			if ($risque['logo_risque']) {
				@unlink(_DIR_PLUGIN_DICRIM . 'img/dicrim/logos_risques/' . $risque['logo_risque']);
			}
		}
	}
	
	// Vignette
	if (isset($_FILES['vignette_risque']) && $_FILES['vignette_risque']['error'] == 0) {
		// Création des répertoires si nécessaire
		$dir_vignettes = _DIR_PLUGIN_DICRIM . 'img/dicrim/vignettes_risques/';
		if (!is_dir($dir_vignettes)) {
			mkdir($dir_vignettes, 0777, true);
		}
		
		// Traitement de l'image
		$extension = strtolower(pathinfo($_FILES['vignette_risque']['name'], PATHINFO_EXTENSION));
		$vignette_nom = 'risque_' . ($id_dicrim_risque != 'nouveau' ? $id_dicrim_risque : time()) . '.' . $extension;
		$destination = $dir_vignettes . $vignette_nom;
		
		if (move_uploaded_file($_FILES['vignette_risque']['tmp_name'], $destination)) {
			$champs['vignette_risque'] = $vignette_nom;
		}
	}
	
	// Suppression de la vignette si demandé
	if (_request('supprimer_vignette_risque') == 'oui') {
		$champs['vignette_risque'] = '';
		
		// Suppression du fichier si existant
		if ($id_dicrim_risque != 'nouveau') {
			$risque = sql_fetsel('vignette_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id_dicrim_risque));
			if ($risque['vignette_risque']) {
				@unlink(_DIR_PLUGIN_DICRIM . 'img/dicrim/vignettes_risques/' . $risque['vignette_risque']);
			}
		}
	}
	
	// Ajout de la date de modification
	$champs['date_modif'] = date('Y-m-d H:i:s');
	
	if ($id_dicrim_risque === 'nouveau') {
		// Création
		$champs['date'] = date('Y-m-d H:i:s');
		$champs['statut'] = 'prepa';
		if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
			$champs['id_auteur'] = $GLOBALS['visiteur_session']['id_auteur'];
		}
		
		$id_dicrim_risque = sql_insertq('spip_dicrim_risques', $champs);
		
		if ($id_dicrim_risque) {
			$retours = array(
				'message_ok' => _T('dicrim:risque_cree'),
				'id_dicrim_risque' => $id_dicrim_risque
			);
		} else {
			$retours = array(
				'message_erreur' => _T('dicrim:erreur_creation_risque')
			);
		}
	} else {
		// Modification
		$id_dicrim_risque = intval($id_dicrim_risque);
		$ok = sql_updateq('spip_dicrim_risques', $champs, 'id_dicrim_risque='.$id_dicrim_risque);
		
		if ($ok !== false) {
			$retours = array(
				'message_ok' => _T('dicrim:risque_modifie'),
				'id_dicrim_risque' => $id_dicrim_risque
			);
		} else {
			$retours = array(
				'message_erreur' => _T('dicrim:erreur_modification_risque')
			);
		}
	}
	
	// Redirection si demandée
	if ($retour && !$retours['message_erreur']) {
		$retours['redirect'] = $retour;
	}
	
	return $retours;
}