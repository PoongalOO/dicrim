<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Fonctions utiles pour le plugin DICRIM
 */

// Inclure les API SPIP nécessaires
include_spip('inc/filtres');
include_spip('inc/filtres_images_mini');
if (!function_exists('quete_logo')) {
    include_spip('inc/logos'); // SPIP 3.x
} else {
    include_spip('inc/chercher_logo'); // SPIP 4.x
}

/**
 * Fonction pour générer les URLs des objets DICRIM
 */
function generer_url_entite_dicrim_risque($id_dicrim_risque, $args='', $ancre='') {
	return generer_url_ecrire('dicrim_risque_edit', "id_dicrim_risque=$id_dicrim_risque");
}

function generer_url_entite_dicrim_contenu($id_dicrim_contenu, $args='', $ancre='') {
	return generer_url_ecrire('dicrim_contenu_edit', "id_dicrim_contenu=$id_dicrim_contenu");
}

/**
 * Critères pour les boucles DICRIM
 */
function critere_dicrim_ordre_dist($idb, &$boucles, $crit) {
	$boucle = &$boucles[$idb];
	$boucle->modificateur['par'][] = 'ordre';
}

/**
 * Filtre pour récupérer tous les contenus du DICRIM dans l'ordre
 */
function filtre_dicrim_contenus_ordonnes($statut = 'publie') {
	$contenus = array();
	
	// Récupérer les contenus fixes
	$res_contenus = sql_select('*', 'spip_dicrim_contenus', "statut=" . sql_quote($statut), '', 'ordre ASC');
	while ($contenu = sql_fetch($res_contenus)) {
		$contenus[] = array(
			'type' => 'contenu',
			'data' => $contenu
		);
	}
	
	// Récupérer les risques
	$res_risques = sql_select('*', 'spip_dicrim_risques', "statut=" . sql_quote($statut), '', 'ordre ASC');
	while ($risque = sql_fetch($res_risques)) {
		$contenus[] = array(
			'type' => 'risque',
			'data' => $risque
		);
	}
	
	// Trier par ordre global
	usort($contenus, function($a, $b) {
		$ordre_a = isset($a['data']['ordre']) ? intval($a['data']['ordre']) : 999;
		$ordre_b = isset($b['data']['ordre']) ? intval($b['data']['ordre']) : 999;
		return $ordre_a - $ordre_b;
	});
	
	return $contenus;
}

/**
 * Filtre pour convertir le type de risque en libellé
 */
function filtre_dicrim_libelle_type_risque($type) {
	$libelles = array(
		'naturel' => _T('dicrim:risque_naturel'),
		'technologique' => _T('dicrim:risque_technologique'), 
		'transport' => _T('dicrim:risque_transport'),
		'sanitaire' => _T('dicrim:risque_sanitaire')
	);
	
	return isset($libelles[$type]) ? $libelles[$type] : $type;
}

/**
 * Filtre pour convertir le type de contenu en libellé
 */
function filtre_dicrim_libelle_type_contenu($type) {
	$libelles = array(
		'sommaire' => _T('dicrim:sommaire'),
		'edito_maire' => _T('dicrim:edito_maire'),
		'quest_ce_dicrim' => _T('dicrim:quest_ce_dicrim'),
		'quest_ce_risque_majeur' => _T('dicrim:quest_ce_risque_majeur'),
		'autre' => 'Autre contenu'
	);
	
	return isset($libelles[$type]) ? $libelles[$type] : ucfirst($type);
}

/**
 * Filtre pour récupérer tous les contenus et risques dans l'ordre
 */
function filtre_dicrim_contenu_complet($statut = 'publie') {
	$elements = array();
	
	// Récupérer les contenus fixes
	$res_contenus = sql_select('*, "contenu" as type_element', 'spip_dicrim_contenus', 
		"statut=" . sql_quote($statut), '', 'ordre ASC');
	while ($contenu = sql_fetch($res_contenus)) {
		$elements[] = $contenu;
	}
	
	// Récupérer les risques
	$res_risques = sql_select('*, "risque" as type_element', 'spip_dicrim_risques', 
		"statut=" . sql_quote($statut), '', 'ordre ASC');
	while ($risque = sql_fetch($res_risques)) {
		$elements[] = $risque;
	}
	
	// Trier par ordre
	usort($elements, function($a, $b) {
		return intval($a['ordre']) - intval($b['ordre']);
	});
	
	return $elements;
}

/**
 * Génération d'URLs pour les objets DICRIM
 */
function generer_url_dicrim_risque($id_risque, $args='', $ancre='') {
	return generer_url_page('dicrim_risque', "id_dicrim_risque=$id_risque", false, $ancre);
}

function generer_url_dicrim_contenu($id_contenu, $args='', $ancre='') {
	return generer_url_page('dicrim_contenu', "id_dicrim_contenu=$id_contenu", false, $ancre);
}

/**
 * Balise pour intégrer un widget DICRIM dans les squelettes
 */
function balise_DICRIM_WIDGET($p) {
	return calculer_balise_dynamique($p, 'DICRIM_WIDGET', array());
}

function balise_DICRIM_WIDGET_dyn($type='resume') {
	$html = '';
	
	if ($type == 'resume') {
		$nb_risques = sql_countsel('spip_dicrim_risques', 'statut="publie"');
		$nb_contenus = sql_countsel('spip_dicrim_contenus', 'statut="publie"');
		
		$html = '<div class="dicrim-widget-resume">';
		$html .= '<h3>DICRIM de notre commune</h3>';
		$html .= '<p>' . $nb_risques . ' risque(s) majeur(s) identifié(s)</p>';
		$html .= '<p>' . $nb_contenus . ' page(s) d\'information</p>';
		$html .= '<a href="' . generer_url_page('dicrim') . '" class="btn-dicrim">Consulter le DICRIM</a>';
		$html .= '</div>';
	}
	
	return $html;
}

/**
 * Filtre pour récupérer l'URL d'un logo de risque
 */
function filtre_dicrim_url_logo_risque($id_risque) {
	if (!$id_risque) return '';
	
	$risque = sql_fetsel('logo_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id_risque));
	if ($risque && $risque['logo_risque']) {
		return find_in_path('img/dicrim/logos_risques/' . $risque['logo_risque']);
	}
	
	return '';
}

/**
 * Filtre pour récupérer l'URL d'une vignette de risque
 */
function filtre_dicrim_url_vignette_risque($id_risque) {
	if (!$id_risque) return '';
	
	$risque = sql_fetsel('vignette_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id_risque));
	if ($risque && $risque['vignette_risque']) {
		return find_in_path('img/dicrim/vignettes_risques/' . $risque['vignette_risque']);
	}
	
	return '';
}

/**
 * Filtre pour récupérer l'URL d'un logo de contenu
 */
function filtre_dicrim_url_logo_contenu($id_contenu) {
	if (!$id_contenu) return '';
	
	$contenu = sql_fetsel('logo_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id_contenu));
	if ($contenu && $contenu['logo_contenu']) {
		return find_in_path('img/dicrim/logos_contenus/' . $contenu['logo_contenu']);
	}
	
	return '';
}

/**
 * Filtre pour récupérer l'URL d'une vignette de contenu
 */
function filtre_dicrim_url_vignette_contenu($id_contenu) {
	if (!$id_contenu) return '';
	
	$contenu = sql_fetsel('vignette_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id_contenu));
	if ($contenu && $contenu['vignette_contenu']) {
		return find_in_path('img/dicrim/vignettes_contenus/' . $contenu['vignette_contenu']);
	}
	
	return '';
}

/**
 * Filtre pour insérer les images dans le contenu formaté d'un risque
 */
function filtre_dicrim_formater_risque($risque) {
	$html = '';
	
	// Insertion du logo principal si disponible
	if (isset($risque['logo_risque']) && $risque['logo_risque']) {
		$html .= '<div class="dicrim-risque-logo">';
		$html .= '<img src="' . find_in_path('img/dicrim/logos_risques/' . $risque['logo_risque']) . '" alt="' . $risque['titre'] . '" class="logo-risque" />';
		$html .= '</div>';
	}
	
	if ($risque['identification']) {
		$html .= '<h3>Identification du risque ' . $risque['titre'] . '</h3>';
		$html .= '<div class="identification">' . $risque['identification'] . '</div>';
	}
	
	if ($risque['consequences']) {
		$html .= '<h3>Conséquences du risque ' . $risque['titre'] . '</h3>';
		$html .= '<div class="consequences">' . $risque['consequences'] . '</div>';
	}
	
	if ($risque['mesures_prises']) {
		$html .= '<h3>Mesures prises</h3>';
		$html .= '<div class="mesures">' . $risque['mesures_prises'] . '</div>';
	}
	
	if ($risque['alerte']) {
		$html .= '<h3>Alerte</h3>';
		$html .= '<div class="alerte">' . $risque['alerte'] . '</div>';
	}
	
	if ($risque['consignes_securite']) {
		$html .= '<h3>Consignes de sécurité en cas de ' . $risque['titre'] . '</h3>';
		$html .= '<div class="consignes">' . $risque['consignes_securite'] . '</div>';
	}
	
	return $html;
}

/**
 * Filtre pour afficher les images dans un contenu
 */
function filtre_dicrim_afficher_images_contenu($contenu) {
	if (!is_array($contenu)) {
		return $contenu;
	}
	
	$html = '';
	
	// Insertion du logo principal si disponible
	if (isset($contenu['logo_contenu']) && $contenu['logo_contenu']) {
		$html .= '<div class="dicrim-contenu-logo">';
		$html .= '<img src="' . find_in_path('img/dicrim/logos_contenus/' . $contenu['logo_contenu']) . '" alt="' . $contenu['titre'] . '" class="logo-contenu" />';
		$html .= '</div>';
	}
	
	// Ajout du contenu texte
	$html .= '<div class="dicrim-contenu-texte">';
	$html .= $contenu['contenu'];
	$html .= '</div>';
	
	return $html;
}

/**
 * Filtre pour afficher les images dans un risque
 */
function filtre_dicrim_afficher_images_risque($risque) {
	if (!is_array($risque)) {
		return $risque;
	}
	
	$html = filtre_dicrim_formater_risque($risque);
	
	// Ajout de la vignette à la fin si disponible
	if (isset($risque['vignette_risque']) && $risque['vignette_risque']) {
		$html .= '<div class="dicrim-risque-vignette">';
		$html .= '<img src="' . find_in_path('img/dicrim/vignettes_risques/' . $risque['vignette_risque']) . '" alt="Vignette ' . $risque['titre'] . '" class="vignette-risque" />';
		$html .= '</div>';
	}
	
	return $html;
}

/**
 * Fonction pour vérifier et afficher le logo (SPIP ou personnalisé)
 * Compatible avec SPIP 3.x et 4.x
 */
function filtre_dicrim_afficher_logo($id, $type='risque') {
    $html = '';
    
    if ($type == 'risque') {
        // Pour les risques, utiliser notre système personnalisé
        $risque = sql_fetsel('logo_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id));
        if ($risque && !empty($risque['logo_risque'])) {
            $chemin = find_in_path('img/dicrim/logos_risques/' . $risque['logo_risque']);
            if ($chemin) {
                $html = '<img src="'.$chemin.'" class="logo-dicrim-risque-custom" alt="Logo risque" style="max-width: 200px; height: auto; border-radius: 8px; display: block; margin: 15px auto;">';
            }
        }
    } else {
        // Pour les contenus, utiliser notre système personnalisé
        $contenu = sql_fetsel('logo_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id));
        if ($contenu && !empty($contenu['logo_contenu'])) {
            $chemin = find_in_path('img/dicrim/logos_contenus/' . $contenu['logo_contenu']);
            if ($chemin) {
                $html = '<img src="'.$chemin.'" class="logo-dicrim-contenu-custom" alt="Logo contenu" style="max-width: 200px; height: auto; border-radius: 8px; display: block; margin: 15px auto;">';
            }
        }
    }
    
    return $html;
}

/**
 * Fonction pour vérifier et afficher la vignette (personnalisée)
 */
function filtre_dicrim_afficher_vignette($id, $type='risque') {
    $html = '';
    
    if ($type == 'risque') {
        $risque = sql_fetsel('vignette_risque', 'spip_dicrim_risques', 'id_dicrim_risque=' . intval($id));
        if ($risque && !empty($risque['vignette_risque'])) {
            $chemin = find_in_path('img/dicrim/vignettes_risques/' . $risque['vignette_risque']);
            if ($chemin) {
                $html = '<img src="'.$chemin.'" class="vignette-dicrim-risque" alt="Vignette risque" style="max-width: 250px; height: auto; border-radius: 8px;">';
            }
        }
    } else {
        $contenu = sql_fetsel('vignette_contenu', 'spip_dicrim_contenus', 'id_dicrim_contenu=' . intval($id));
        if ($contenu && !empty($contenu['vignette_contenu'])) {
            $chemin = find_in_path('img/dicrim/vignettes_contenus/' . $contenu['vignette_contenu']);
            if ($chemin) {
                $html = '<img src="'.$chemin.'" class="vignette-dicrim-contenu" alt="Vignette contenu" style="max-width: 250px; height: auto; border-radius: 8px;">';
            }
        }
    }
    
    return $html;
}
?>