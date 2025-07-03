<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/actions');
include_spip('inc/editer');
include_spip('inc/filtres_images_mini');  // Pour le traitement des images

/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 */
function formulaires_editer_dicrim_contenu_identifier_dist($id_dicrim_contenu='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	return serialize(array(intval($id_dicrim_contenu)));
}

/**
 * Chargement du formulaire d'édition de contenu DICRIM
 */
function formulaires_editer_dicrim_contenu_charger_dist($id_dicrim_contenu='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$valeurs = array();
	
	// Charger les données existantes
	if ($id_dicrim_contenu !== 'nouveau' && intval($id_dicrim_contenu) > 0) {
		$row = sql_fetsel('*', 'spip_dicrim_contenus', 'id_dicrim_contenu='.intval($id_dicrim_contenu));
		if ($row) {
			$valeurs = $row;
		}
	}
	
	// Valeurs par défaut pour un nouveau contenu
	if ($id_dicrim_contenu === 'nouveau') {
		$valeurs = array_merge(array(
			'titre' => '',
			'type_contenu' => '',
			'contenu' => '',
			'ordre' => 5,
			'statut' => 'prepa'
		), $valeurs);
	}
	
	// Ajout des paramètres du formulaire
	$valeurs['id_dicrim_contenu'] = $id_dicrim_contenu;
	$valeurs['redirect'] = $retour;
	
	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de contenu DICRIM
 */
function formulaires_editer_dicrim_contenu_verifier_dist($id_dicrim_contenu='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
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
	if (isset($_FILES['logo_contenu']) && $_FILES['logo_contenu']['error'] == 0) {
		if ($_FILES['logo_contenu']['size'] > 1048576) {
			$erreurs['logo_contenu'] = _T('dicrim:erreur_taille_logo');
		}
		$formats_acceptes = array('jpg', 'jpeg', 'png', 'gif');
		$extension = strtolower(pathinfo($_FILES['logo_contenu']['name'], PATHINFO_EXTENSION));
		if (!in_array($extension, $formats_acceptes)) {
			$erreurs['logo_contenu'] = _T('dicrim:erreur_format_logo');
		}
	}
	
	// Vérification de la vignette
	if (isset($_FILES['vignette_contenu']) && $_FILES['vignette_contenu']['error'] == 0) {
		if ($_FILES['vignette_contenu']['size'] > 1048576) {
			$erreurs['vignette_contenu'] = _T('dicrim:erreur_taille_vignette');
		}
		$formats_acceptes = array('jpg', 'jpeg', 'png', 'gif');
		$extension = strtolower(pathinfo($_FILES['vignette_contenu']['name'], PATHINFO_EXTENSION));
		if (!in_array($extension, $formats_acceptes)) {
			$erreurs['vignette_contenu'] = _T('dicrim:erreur_format_vignette');
		}
	}
	
	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de contenu DICRIM
 */
function formulaires_editer_dicrim_contenu_traiter_dist($id_dicrim_contenu='nouveau', $retour='', $lier_trad=0, $config_fonc='', $row=array(), $hidden=''){
	$champs = array(
		'titre' => _request('titre'),
		'type_contenu' => _request('type_contenu'),
		'contenu' => _request('contenu'),
		'ordre' => intval(_request('ordre') ?: 5)
	);
	
	// Gestion des images
	
	// Logo
	if (isset($_FILES['logo_contenu']) && $_FILES['logo_contenu']['error'] == 0) {
		// Création des répertoires si nécessaire
		$dir_logos = _DIR_PLUGIN_DICRIM . 'img/dicrim/logos_contenus/';
		if (!is_dir($dir_logos)) {
			mkdir($dir_logos, 0777, true);
		}
		
		// Traitement de l'image
		$extension = strtolower(pathinfo($_FILES['logo_contenu']['name'], PATHINFO_EXTENSION));
		$logo_nom = 'contenu_' . ($id_dicrim_contenu != 'nouveau' ? $id_dicrim_contenu : time()) . '.' . $extension;
		$destination = $dir_logos . $logo_nom;
		
		if (move_uploaded_file($_FILES['logo_contenu']['tmp_name'], $destination)) {
			$champs['logo_contenu'] = $logo_nom;
		}
	}
	
	// Suppression du logo si demandé
	if (_request('supprimer_logo_contenu') == 'oui') {
		$champs['logo_contenu'] = '';
		
		// Suppression du fichier si existant
		if ($id_dicrim_contenu != 'nouveau') {
			$contenu = sql_fetsel('logo_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id_dicrim_contenu));
			if ($contenu['logo_contenu']) {
				@unlink(_DIR_PLUGIN_DICRIM . 'img/dicrim/logos_contenus/' . $contenu['logo_contenu']);
			}
		}
	}
	
	// Vignette
	if (isset($_FILES['vignette_contenu']) && $_FILES['vignette_contenu']['error'] == 0) {
		// Création des répertoires si nécessaire
		$dir_vignettes = _DIR_PLUGIN_DICRIM . 'img/dicrim/vignettes_contenus/';
		if (!is_dir($dir_vignettes)) {
			mkdir($dir_vignettes, 0777, true);
		}
		
		// Traitement de l'image
		$extension = strtolower(pathinfo($_FILES['vignette_contenu']['name'], PATHINFO_EXTENSION));
		$vignette_nom = 'contenu_' . ($id_dicrim_contenu != 'nouveau' ? $id_dicrim_contenu : time()) . '.' . $extension;
		$destination = $dir_vignettes . $vignette_nom;
		
		if (move_uploaded_file($_FILES['vignette_contenu']['tmp_name'], $destination)) {
			$champs['vignette_contenu'] = $vignette_nom;
		}
	}
	
	// Suppression de la vignette si demandé
	if (_request('supprimer_vignette_contenu') == 'oui') {
		$champs['vignette_contenu'] = '';
		
		// Suppression du fichier si existant
		if ($id_dicrim_contenu != 'nouveau') {
			$contenu = sql_fetsel('vignette_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id_dicrim_contenu));
			if ($contenu['vignette_contenu']) {
				@unlink(_DIR_PLUGIN_DICRIM . 'img/dicrim/vignettes_contenus/' . $contenu['vignette_contenu']);
			}
		}
	}
	
	// Ajout de la date de modification
	$champs['date_modif'] = date('Y-m-d H:i:s');
	
	if ($id_dicrim_contenu === 'nouveau') {
		// Création
		$champs['date'] = date('Y-m-d H:i:s');
		$champs['statut'] = 'prepa';
		if (isset($GLOBALS['visiteur_session']['id_auteur'])) {
			$champs['id_auteur'] = $GLOBALS['visiteur_session']['id_auteur'];
		}
		
		$id_dicrim_contenu = sql_insertq('spip_dicrim_contenus', $champs);
		
		if ($id_dicrim_contenu) {
			$retours = array(
				'message_ok' => _T('dicrim:contenu_cree'),
				'id_dicrim_contenu' => $id_dicrim_contenu
			);
		} else {
			$retours = array(
				'message_erreur' => _T('dicrim:erreur_creation_contenu')
			);
		}
	} else {
		// Modification
		$id_dicrim_contenu = intval($id_dicrim_contenu);
		$ok = sql_updateq('spip_dicrim_contenus', $champs, 'id_dicrim_contenu='.$id_dicrim_contenu);
		
		if ($ok !== false) {
			$retours = array(
				'message_ok' => _T('dicrim:contenu_modifie'),
				'id_dicrim_contenu' => $id_dicrim_contenu
			);
		} else {
			$retours = array(
				'message_erreur' => _T('dicrim:erreur_modification_contenu')
			);
		}
	}
	
	// Redirection si demandée
	if ($retour && !$retours['message_erreur']) {
		$retours['redirect'] = $retour;
	}
	
	return $retours;
}