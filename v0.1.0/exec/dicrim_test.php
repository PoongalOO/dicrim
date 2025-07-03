<?php
// Script de test pour vérifier l'installation du plugin DICRIM
// À exécuter depuis l'espace privé de SPIP ou via l'URL : ?exec=dicrim_test

if (!defined('_ECRIRE_INC_VERSION')) return;

function exec_dicrim_test_dist() {
	echo "<h2>Test d'installation du plugin DICRIM</h2>";
	
	// Vérifier l'existence des tables
	$tables_ok = true;
	
	if (sql_showtable('spip_dicrim_risques')) {
		echo "<p style='color: green;'>✓ Table spip_dicrim_risques : OK</p>";
	} else {
		echo "<p style='color: red;'>✗ Table spip_dicrim_risques : MANQUANTE</p>";
		$tables_ok = false;
	}
	
	if (sql_showtable('spip_dicrim_contenus')) {
		echo "<p style='color: green;'>✓ Table spip_dicrim_contenus : OK</p>";
	} else {
		echo "<p style='color: red;'>✗ Table spip_dicrim_contenus : MANQUANTE</p>";
		$tables_ok = false;
	}
	
	if (!$tables_ok) {
		echo "<h3>Installation forcée des tables</h3>";
		include_spip('base/dicrim_declarer');
		include_spip('base/create');
		
		// Forcer la création des tables
		$tables = dicrim_declarer_tables_objets_sql(array());
		foreach ($tables as $table => $desc) {
			creer_ou_upgrader_table($table, $desc);
			echo "<p>Table $table créée/mise à jour</p>";
		}
		
		echo "<p style='color: green;'><strong>Installation terminée !</strong></p>";
	} else {
		echo "<p style='color: green;'><strong>Plugin correctement installé !</strong></p>";
	}
	
	// Test des formulaires
	echo "<h3>Test des formulaires</h3>";
	echo "<a href='" . generer_url_ecrire('test', 'var_mode=test') . "'>Tester un formulaire de risque</a>";
}
