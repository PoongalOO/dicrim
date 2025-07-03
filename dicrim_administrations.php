<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/meta');

/**
 * Installation/désinstallation du plugin DICRIM
 */

function dicrim_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();
	
	// Version 1.0.0 : création des tables
	$maj['create'] = array(
		array('maj_tables', array('spip_dicrim_risques', 'spip_dicrim_contenus')),
	);
	
	$maj['1.0.0'] = array(
		array('maj_tables', array('spip_dicrim_risques', 'spip_dicrim_contenus')),
		array('dicrim_installer_contenus_defaut')
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

function dicrim_vider_tables($nom_meta_base_version) {
	// Suppression des tables lors de la désinstallation
	sql_drop_table('spip_dicrim_risques');
	sql_drop_table('spip_dicrim_contenus');
	
	effacer_meta($nom_meta_base_version);
}

/**
 * Installer les contenus par défaut du DICRIM
 */
function dicrim_installer_contenus_defaut() {
	// Vérifier si des contenus existent déjà
	$nb_contenus = sql_countsel('spip_dicrim_contenus');
	if ($nb_contenus > 0) {
		return; // Ne pas réinstaller si des contenus existent
	}
	
	$contenus_defaut = array(
		array(
			'titre' => 'Sommaire',
			'type_contenu' => 'sommaire',
			'ordre' => 1,
			'contenu' => 'Ce document présente les risques majeurs auxquels notre commune peut être exposée.',
			'statut' => 'publie',
			'date' => date('Y-m-d H:i:s'),
			'id_auteur' => 1
		),
		array(
			'titre' => 'Édito du Maire',
			'type_contenu' => 'edito_maire',
			'ordre' => 2,
			'contenu' => 'Chers concitoyens, ce document d\'information vous présente les risques majeurs de notre commune.',
			'statut' => 'publie',
			'date' => date('Y-m-d H:i:s'),
			'id_auteur' => 1
		),
		array(
			'titre' => 'Qu\'est-ce qu\'un DICRIM ?',
			'type_contenu' => 'quest_ce_dicrim',
			'ordre' => 3,
			'contenu' => 'Le DICRIM est un document d\'information communal sur les risques majeurs.',
			'statut' => 'publie',
			'date' => date('Y-m-d H:i:s'),
			'id_auteur' => 1
		),
		array(
			'titre' => 'Qu\'est-ce qu\'un risque majeur ?',
			'type_contenu' => 'quest_ce_risque_majeur',
			'ordre' => 4,
			'contenu' => 'Un risque majeur est caractérisé par sa faible fréquence et sa forte gravité.',
			'statut' => 'publie',
			'date' => date('Y-m-d H:i:s'),
			'id_auteur' => 1
		)
	);
	
	foreach ($contenus_defaut as $contenu) {
		sql_insertq('spip_dicrim_contenus', $contenu);
	}
}
?>