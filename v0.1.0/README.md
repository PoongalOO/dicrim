# Plugin DICRIM pour SPIP

## Description

Le plugin **DICRIM** permet de créer et gérer un Document d'Information Communal sur les RIsques Majeurs directement dans SPIP. Il permet d'informer les habitants d'une commune des risques majeurs naturels et technologiques auxquels ils sont exposés.

## Fonctionnalités

### Objets gérés

1. **Contenus DICRIM** - Pages fixes du document
   - Sommaire
   - Édito du Maire
   - Qu'est-ce qu'un DICRIM ?
   - Qu'est-ce qu'un risque majeur ?

2. **Risques** - Gestion détaillée des risques
   - Identification du risque
   - Conséquences du risque
   - Mesures prises
   - Alerte
   - Consignes de sécurité

### Fonctionnalités principales

- **Interface de gestion unifiée** : tous les contenus dans une seule interface
- **Ordre personnalisable** : définition de l'ordre d'affichage des contenus
- **Navigation par flèches** : consultation du document comme un livre
- **Génération PDF** : téléchargement du DICRIM complet en PDF
- **Affichage public** : consultation en ligne du document

### Types de risques supportés

- Risques naturels (inondations, séismes, etc.)
- Risques technologiques (industries, transport de matières dangereuses)
- Risques de transport
- Risques sanitaires

## Installation

1. Télécharger le plugin dans le dossier `plugins/dicrim/`
2. Activer le plugin dans l'interface d'administration SPIP
3. Les tables seront créées automatiquement avec des contenus par défaut

## Utilisation

### Interface d'administration

Le plugin ajoute un menu **DICRIM** dans l'espace privé qui permet de :
- Gérer les contenus fixes du document
- Créer et modifier les risques
- Consulter le document complet
- Générer le PDF

### Structure du document

Le DICRIM est organisé selon l'ordre défini pour chaque contenu :

1. **Sommaire** (ordre 1)
2. **Édito du Maire** (ordre 2)
3. **Qu'est-ce qu'un DICRIM ?** (ordre 3)
4. **Qu'est-ce qu'un risque majeur ?** (ordre 4)
5. **Risques** (selon leur ordre) :
   - Identification du risque
   - Conséquences du risque
   - Mesures prises
   - Alerte
   - Consignes de sécurité

### Création de contenus

#### Contenus fixes
- Utiliser le formulaire de création de contenu
- Sélectionner le type approprié
- Définir l'ordre d'affichage
- Rédiger le contenu

#### Risques
- Créer un nouveau risque
- Remplir tous les champs détaillés
- Définir l'ordre d'apparition dans le document
- Publier le risque

### Consultation publique

Le document peut être consulté :
- En ligne avec navigation par flèches
- En téléchargement PDF complet
- Avec une interface responsive

## Structure technique

### Tables créées

- `spip_dicrim_contenus` : contenus fixes du DICRIM
- `spip_dicrim_risques` : risques détaillés

### Fichiers principaux

- `paquet.xml` : déclaration du plugin
- `base/dicrim_declarer.php` : déclaration des tables et objets
- `inc/dicrim_autoriser.php` : gestion des autorisations
- `lang/dicrim_fr.php` : chaînes de langue françaises
- `formulaires/` : formulaires d'édition
- `prive/` : interface d'administration

## Comment utiliser les squelettes publics

vous pouvez utiliser le contenu DICRIM de plusieurs façons :

### Affichage complet du DICRIM :
URLs disponibles :
- ?page=dicrim - Document complet
- ?page=dicrim-liste - Liste des contenus
- ?page=dicrim-risque&id_dicrim_risque=1 - Risque spécifique
- ?page=dicrim-contenu&id_dicrim_contenu=1 - Contenu spécifique

### Liste de tous les contenus :
<!-- URL: ?page=dicrim-liste -->
<!-- Affiche une grille avec tous les contenus et risques -->

### Widget DICRIM dans vos squelettes :
```
<!-- Dans n'importe quel squelette -->
<INCLURE{fond=inclure/widget-dicrim}>

<!-- Ou en version compacte -->
<INCLURE{fond=inclure/widget-dicrim}{classe=compact}>
```
### Boucles dans vos propres squelettes :
```
<!-- Lister tous les risques -->
<BOUCLE_risques(DICRIM_RISQUES){statut=publie}{par ordre}>
    <h3>[(#TITRE)]</h3>
    <p>[(#DESCRIPTIF)]</p>
    <p>Type: [(#TYPE_RISQUE|dicrim_libelle_type_risque)]</p>
</BOUCLE_risques>

<!-- Lister tous les contenus -->
<BOUCLE_contenus(DICRIM_CONTENUS){statut=publie}{par ordre}>
    <h3>[(#TITRE)]</h3>
    <div>[(#CONTENU|propre)]</div>
</BOUCLE_contenus>
```

### URLs générées automatiquement :
- Page DICRIM complète : `?page=dicrim`
- Risque spécifique : `?page=dicrim-risque&id_dicrim_risque=X`
- Contenu spécifique : `?page=dicrim-contenu&id_dicrim_contenu=X`
- Liste des contenus : `?page=dicrim-liste`

### Exemples d'utilisation avancée :

#### Dans un squelette d'article pour afficher les risques liés :
```html
<B_risques_locaux>
<div class="risques-locaux">
	<h3>Risques majeurs dans cette zone</h3>
	<BOUCLE_risques_locaux(DICRIM_RISQUES){statut=publie}{0,3}>
	<div class="risque-apercu">
		<h4><a href="[(#URL_PAGE{dicrim-risque}|parametre_url{id_dicrim_risque,#ID_DICRIM_RISQUE})]">[(#TITRE)]</a></h4>
		<p>[(#DESCRIPTIF|couper{100})]</p>
		<span class="badge-[(#TYPE_RISQUE)]">[(#TYPE_RISQUE|dicrim_libelle_type_risque)]</span>
	</div>
	</BOUCLE_risques_locaux>
</div>
</B_risques_locaux>
```

#### Widget DICRIM compact pour la sidebar :
```html
<INCLURE{fond=inclure/widget-dicrim}{classe=compact}>
```

Tous les squelettes sont responsives et incluent des styles CSS intégrés. Ils s'adapteront automatiquement au design de votre site SPIP !



## Compatibilité

- SPIP 3.2 à 4.4.4
- Compatible avec les plugins standards SPIP

## Licence

GPL v3

## Auteur

Plugin développé pour la création de Documents d'Information Communal sur les RIsques Majeurs.