<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Déclaration des objets éditoriaux du plugin DICRIM
 */

function dicrim_declarer_tables_objets_sql($tables) {
	
	// Table des risques DICRIM
	$tables['spip_dicrim_risques'] = array(
		'type' => 'dicrim_risque',
		'principale' => 'oui',
		'table_objet_surnoms' => array('dicrim_risque'),
		'field'=> array(
			'id_dicrim_risque' => 'bigint(21) NOT NULL',
			'titre' => 'varchar(255) NOT NULL DEFAULT ""',
			'descriptif' => 'text NOT NULL DEFAULT ""',
			'type_risque' => 'varchar(100) NOT NULL DEFAULT ""',
			'identification' => 'longtext NOT NULL DEFAULT ""',
			'consequences' => 'longtext NOT NULL DEFAULT ""',
			'mesures_prises' => 'longtext NOT NULL DEFAULT ""',
			'alerte' => 'longtext NOT NULL DEFAULT ""',
			'consignes_securite' => 'longtext NOT NULL DEFAULT ""',
			'logo_risque' => 'varchar(255) NOT NULL DEFAULT ""',
			'vignette_risque' => 'varchar(255) NOT NULL DEFAULT ""',
			'ordre' => 'int(11) NOT NULL DEFAULT 0',
			'statut' => 'varchar(20) NOT NULL DEFAULT "prop"',
			'date' => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'date_modif' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
			'id_auteur' => 'bigint(21) NOT NULL DEFAULT 0'
		),
		'key' => array(
			'PRIMARY KEY' => 'id_dicrim_risque',
			'KEY statut' => 'statut',
			'KEY id_auteur' => 'id_auteur',
			'KEY ordre' => 'ordre'
		),
		'titre' => 'titre AS titre, "" AS lang',
		'date' => 'date',
		'champs_editables' => array('titre', 'descriptif', 'type_risque', 'identification', 'consequences', 'mesures_prises', 'alerte', 'consignes_securite', 'logo_risque', 'vignette_risque', 'ordre'),
		'champs_versionnes' => array('titre', 'descriptif', 'type_risque', 'identification', 'consequences', 'mesures_prises', 'alerte', 'consignes_securite', 'logo_risque', 'vignette_risque'),
		'rechercher_champs' => array('titre' => 10, 'descriptif' => 5, 'identification' => 3, 'consequences' => 3),
		'tables_jointures' => array('spip_auteurs'),
		'statut_textes_instituer' => array(
			'prepa' => 'texte_statut_en_cours_redaction',
			'prop' => 'texte_statut_propose_evaluation',
			'publie' => 'texte_statut_publie',
			'refuse' => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle'
		),
		'statut' => array(
			array('champ' => 'statut', 'publie' => 'publie', 'previsu' => 'publie,prop,prepa', 'post_date' => 'date', 'exception' => array('statut','tout'))
		),
		'texte_retour' => 'icone_retour',
		'texte_objet' => 'dicrim:risque',
		'texte_objets' => 'dicrim:risques',
		'texte_modifier' => 'dicrim:modifier_risque',
		'texte_creer' => 'dicrim:nouveau_risque',
		'info_aucun_objet' => 'dicrim:aucun_risque',
		'info_1_objet' => 'dicrim:un_risque',
		'info_nb_objets' => 'dicrim:nb_risques',
		'texte_logo_objet' => 'dicrim:logo_risque',
		'texte_langue_objet' => 'dicrim:langue_risque',
		'editable' => 'oui',
		'page' => 'dicrim_risque'
	);

	// Table des contenus DICRIM (pages fixes)
	$tables['spip_dicrim_contenus'] = array(
		'type' => 'dicrim_contenu',
		'principale' => 'oui',
		'table_objet_surnoms' => array('dicrim_contenu'),
		'field'=> array(
			'id_dicrim_contenu' => 'bigint(21) NOT NULL',
			'titre' => 'varchar(255) NOT NULL DEFAULT ""',
			'type_contenu' => 'varchar(100) NOT NULL DEFAULT ""',
			'contenu' => 'longtext NOT NULL DEFAULT ""',
			'logo_contenu' => 'varchar(255) NOT NULL DEFAULT ""',
			'vignette_contenu' => 'varchar(255) NOT NULL DEFAULT ""',
			'ordre' => 'int(11) NOT NULL DEFAULT 0',
			'statut' => 'varchar(20) NOT NULL DEFAULT "prop"',
			'date' => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'date_modif' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
			'id_auteur' => 'bigint(21) NOT NULL DEFAULT 0'
		),
		'key' => array(
			'PRIMARY KEY' => 'id_dicrim_contenu',
			'KEY statut' => 'statut',
			'KEY id_auteur' => 'id_auteur',
			'KEY type_contenu' => 'type_contenu',
			'KEY ordre' => 'ordre'
		),
		'titre' => 'titre AS titre, "" AS lang',
		'date' => 'date',
		'champs_editables' => array('titre', 'type_contenu', 'contenu', 'logo_contenu', 'vignette_contenu', 'ordre'),
		'champs_versionnes' => array('titre', 'type_contenu', 'contenu', 'logo_contenu', 'vignette_contenu'),
		'rechercher_champs' => array('titre' => 10, 'contenu' => 5),
		'tables_jointures' => array('spip_auteurs'),
		'statut_textes_instituer' => array(
			'prepa' => 'texte_statut_en_cours_redaction',
			'prop' => 'texte_statut_propose_evaluation',
			'publie' => 'texte_statut_publie',
			'refuse' => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle'
		),
		'statut' => array(
			array('champ' => 'statut', 'publie' => 'publie', 'previsu' => 'publie,prop,prepa', 'post_date' => 'date', 'exception' => array('statut','tout'))
		),
		'texte_retour' => 'icone_retour',
		'texte_objet' => 'dicrim:contenu',
		'texte_objets' => 'dicrim:contenus',
		'texte_modifier' => 'dicrim:modifier_contenu',
		'texte_creer' => 'dicrim:nouveau_contenu',
		'info_aucun_objet' => 'dicrim:aucun_contenu',
		'info_1_objet' => 'dicrim:un_contenu',
		'info_nb_objets' => 'dicrim:nb_contenus',
		'texte_logo_objet' => 'dicrim:logo_contenu',
		'texte_langue_objet' => 'dicrim:langue_contenu',
		'editable' => 'oui',
		'page' => 'dicrim_contenu'
	);

	return $tables;
}

function dicrim_declarer_tables_interfaces($interfaces) {
	$interfaces['table_des_tables']['dicrim_risques'] = 'dicrim_risques';
	$interfaces['table_des_tables']['dicrim_contenus'] = 'dicrim_contenus';
	
	// Permettre le téléversement de logos pour les risques et contenus
	$interfaces['tables_objets_sql']['spip_dicrim_risques']['logo'] = true;
	$interfaces['tables_objets_sql']['spip_dicrim_contenus']['logo'] = true;

	return $interfaces;
}

function dicrim_declarer_tables_principales($tables_principales) {
	$tables_principales['spip_dicrim_risques'] = array('id_dicrim_risque', 'titre');
	$tables_principales['spip_dicrim_contenus'] = array('id_dicrim_contenu', 'titre');
	
	return $tables_principales;
}
?>