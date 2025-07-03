<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Pipelines du plugin DICRIM
 */

/**
 * Pipeline affiche_milieu pour ajouter des informations dans l'espace privé
 */
function dicrim_affiche_milieu($flux) {
	$exec = $flux['args']['exec'];
	
	// Ajouter des informations sur la page de gestion DICRIM
	if ($exec == 'dicrim_gestion') {
		$flux['data'] .= '<div class="dicrim-info">';
		$flux['data'] .= '<p>' . _T('dicrim:dicrim_description') . '</p>';
		$flux['data'] .= '</div>';
	}
	
	return $flux;
}

/**
 * Pipeline editer_contenu_objet pour intégrer l'upload de logo dans le formulaire d'édition
 */
function dicrim_editer_contenu_objet($flux) {
    $type = $flux['args']['type'];
    
    // Ajouter le formulaire d'upload de logo pour les risques et contenus
    if ($type == 'dicrim_risque' || $type == 'dicrim_contenu') {
        $id_objet = $flux['args']['id'];
        $contexte = array('id_objet' => $id_objet, 'objet' => $type);
        $logo_formulaire = recuperer_fond('prive/squelettes/inclure/logo_edit', $contexte);
        $flux['data'] = preg_replace('%(<!--extra-->)%is', $logo_formulaire.'$1', $flux['data']);
    }
    
    return $flux;
}

/**
 * Pipeline pre_edition pour s'assurer que les images sont correctement gérées
 */
function dicrim_pre_edition($flux) {
    // Traitement des logos et vignettes lors de l'édition
    if (($flux['args']['table'] == 'spip_dicrim_risques' || $flux['args']['table'] == 'spip_dicrim_contenus') 
        && $flux['args']['action'] == 'modifier') {
        
        $id_objet = $flux['args']['id_objet'];
        $type = ($flux['args']['table'] == 'spip_dicrim_risques') ? 'risque' : 'contenu';
        
        // Assurer que les dossiers d'images existent
        $dir_base = _DIR_IMG . 'dicrim/';
        $dir_logos = $dir_base . 'logos_' . $type . 's/';
        $dir_vignettes = $dir_base . 'vignettes_' . $type . 's/';
        
        if (!is_dir($dir_base)) mkdir($dir_base, 0777, true);
        if (!is_dir($dir_logos)) mkdir($dir_logos, 0777, true);
        if (!is_dir($dir_vignettes)) mkdir($dir_vignettes, 0777, true);
    }
    
    return $flux;
}

/**
 * Pipeline porte_plume_barre_pre_charger pour ajouter la barre d'outils aux champs du formulaire
 */
function dicrim_porte_plume_barre_pre_charger($barres) {
    // PPP se charge de tout, nous ne faisons rien de spécial ici
    return $barres;
}

/**
 * Pipeline porte_plume_lien_classe_vers_icone pour définir les icônes des barres d'outils
 */
function dicrim_porte_plume_lien_classe_vers_icone($flux) {
    // Pas de modifications spécifiques pour les icônes
    return $flux;
}

/**
 * Pipeline header_prive pour charger les ressources JS et CSS dans l'espace privé
 */
function dicrim_header_prive($flux) {
    $flux .= '<link rel="stylesheet" type="text/css" href="' . find_in_path('css/dicrim_porte_plume.css') . '" />';
    $flux .= '<script type="text/javascript" src="' . find_in_path('javascript/dicrim_porte_plume.js') . '"></script>';
    return $flux;
}

/**
 * Pipeline insert_head pour charger les ressources JS et CSS dans l'espace public
 */
function dicrim_insert_head($flux) {
    $flux .= '<link rel="stylesheet" type="text/css" href="' . find_in_path('css/dicrim_porte_plume.css') . '" />';
    $flux .= '<script type="text/javascript" src="' . find_in_path('javascript/dicrim_porte_plume.js') . '"></script>';
    return $flux;
}