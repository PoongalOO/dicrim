<?php
if (!defined('_ECRIRE_INC_VERSION')) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(
	// Titre du plugin
	'dicrim_titre' => 'DICRIM',
	'dicrim_description' => 'Document d\'Information Communal sur les RIsques Majeurs',

	// Menu
	'titre_dicrim' => 'DICRIM',
	'gestion_dicrim' => 'Gestion du DICRIM',

	// Risques
	'risque' => 'Risque',
	'risques' => 'Risques',
	'nouveau_risque' => 'Nouveau risque',
	'modifier_risque' => 'Modifier ce risque',
	'aucun_risque' => 'Aucun risque',
	'un_risque' => 'Un risque',
	'nb_risques' => '@nb@ risques',
	'logo_risque' => 'Logo de ce risque',
	'vignette_risque' => 'Vignette pour ce risque',
	'langue_risque' => 'Langue de ce risque',

	// Contenus
	'contenu' => 'Contenu',
	'contenus' => 'Contenus',
	'nouveau_contenu' => 'Nouveau contenu',
	'modifier_contenu' => 'Modifier ce contenu',
	'aucun_contenu' => 'Aucun contenu',
	'un_contenu' => 'Un contenu',
	'nb_contenus' => '@nb@ contenus',
	'logo_contenu' => 'Logo de ce contenu',
	'vignette_contenu' => 'Vignette pour ce contenu',
	'langue_contenu' => 'Langue de ce contenu',

	// Champs communs
	'titre' => 'Titre',
	'descriptif' => 'Descriptif',
	'contenu' => 'Contenu',
	'ordre' => 'Ordre',
	'statut' => 'Statut',
	'date' => 'Date',

	// Champs spécifiques aux risques
	'type_risque' => 'Type de risque',
	'identification' => 'Identification du risque',
	'consequences' => 'Conséquences du risque',
	'mesures_prises' => 'Mesures prises',
	'alerte' => 'Alerte',
	'consignes_securite' => 'Consignes de sécurité',

	// Champs spécifiques aux contenus
	'type_contenu' => 'Type de contenu',

	// Types de risques
	'risque_naturel' => 'Risque naturel',
	'risque_technologique' => 'Risque technologique',
	'risque_transport' => 'Risque de transport',
	'risque_sanitaire' => 'Risque sanitaire',

	// Types de contenus
	'sommaire' => 'Sommaire',
	'edito_maire' => 'Édito du Maire',
	'quest_ce_dicrim' => 'Qu\'est-ce qu\'un DICRIM ?',
	'quest_ce_risque_majeur' => 'Qu\'est-ce qu\'un risque majeur ?',

	// Navigation
	'page_precedente' => 'Page précédente',
	'page_suivante' => 'Page suivante',
	'retour_sommaire' => 'Retour au sommaire',

	// Actions
	'generer_pdf' => 'Générer le PDF',
	'telecharger_dicrim' => 'Télécharger le DICRIM',
	'consulter_dicrim' => 'Consulter le DICRIM',

	// Messages
	'dicrim_genere' => 'Le DICRIM a été généré avec succès',
	'erreur_generation' => 'Erreur lors de la génération du DICRIM',
	'erreur_ordre_numerique' => 'L\'ordre doit être un nombre',
	'risque_cree' => 'Le risque a été créé avec succès',
	'risque_modifie' => 'Le risque a été modifié avec succès',
	'erreur_creation_risque' => 'Erreur lors de la création du risque',
	'erreur_modification_risque' => 'Erreur lors de la modification du risque',
	'contenu_cree' => 'Le contenu a été créé avec succès',
	'contenu_modifie' => 'Le contenu a été modifié avec succès',
	'erreur_creation_contenu' => 'Erreur lors de la création du contenu',
	'erreur_modification_contenu' => 'Erreur lors de la modification du contenu',
	'aucun_contenu_publie' => 'Aucun contenu publié pour le moment',

	// Aide
	'aide_ordre' => 'L\'ordre détermine la position dans le document final',
	'aide_type_contenu' => 'Sélectionnez le type de contenu approprié',
	'aide_identification' => 'Décrivez précisément le risque et ses caractéristiques',
	'aide_consequences' => 'Listez les conséquences possibles de ce risque',
	'aide_mesures_prises' => 'Décrivez les mesures de prévention mises en place',
	'aide_alerte' => 'Expliquez comment la population sera alertée',
	'aide_consignes' => 'Donnez les consignes de sécurité à suivre',

	// Images
	'supprimer_logo' => 'Supprimer ce logo',
	'supprimer_vignette' => 'Supprimer cette vignette',
	'aide_logo_risque' => 'Image principale illustrant le risque (format JPG, PNG ou GIF, max 1Mo)',
	'aide_vignette_risque' => 'Image secondaire illustrant le risque (format JPG, PNG ou GIF, max 1Mo)',
	'aide_logo_contenu' => 'Image principale illustrant le contenu (format JPG, PNG ou GIF, max 1Mo)',
	'aide_vignette_contenu' => 'Image secondaire illustrant le contenu (format JPG, PNG ou GIF, max 1Mo)',
	'erreur_taille_logo' => 'Le logo dépasse la taille maximale autorisée (1Mo)',
	'erreur_format_logo' => 'Format de logo non autorisé (utilisez JPG, PNG ou GIF)',
	'erreur_taille_vignette' => 'La vignette dépasse la taille maximale autorisée (1Mo)',
	'erreur_format_vignette' => 'Format de vignette non autorisé (utilisez JPG, PNG ou GIF)'
);
?>