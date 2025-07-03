<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Autorisations du plugin DICRIM
 */

function dicrim_autoriser() {}

// Autorisations pour les risques DICRIM
function autoriser_dicrim_risque_voir_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('voir', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_risque_modifier_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('modifier', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_risque_creer_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('creer', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_risque_supprimer_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('supprimer', 'article', $id, $qui, $opt);
}

// Autorisations pour les contenus DICRIM
function autoriser_dicrim_contenu_voir_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('voir', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_contenu_modifier_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('modifier', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_contenu_creer_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('creer', 'article', $id, $qui, $opt);
}

function autoriser_dicrim_contenu_supprimer_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('supprimer', 'article', $id, $qui, $opt);
}

// Autorisation pour la gestion DICRIM
function autoriser_dicrim_gestion_voir_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('voir', 'article', $id, $qui, $opt);
}
?>