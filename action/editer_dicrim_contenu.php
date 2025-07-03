<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Action pour éditer un contenu DICRIM
 * Cette action est maintenant principalement gérée par le formulaire
 */

function action_editer_dicrim_contenu_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}

	// Cette action est maintenant principalement gérée par le formulaire
	// On garde cette fonction pour la compatibilité
	return intval($arg);
}