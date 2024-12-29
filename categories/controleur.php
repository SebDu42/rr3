<?php

session_start();

// Si auncun utilisateur n'est connecté, on retourne à la page d'authentification.
if (!isset($_SESSION["utilisateur"])) {
    header("Location: ../authentification/controleur.php?action=connecter");
}

require "modele.php";
require "../config/config.php";
require "../config/menus.php";

// Vérification de la cohérence des paramètres passés en GET
$arret = false;
$arret |= isset($_GET["cca_id"]) ? !mCategorieExiste($_GET["cca_id"]) : false;
if ($arret) die(htmlentities("Reqête non autorisée"));

// Récupération de l'action passé en GET
$action = isset($_GET["action"]) ? $_GET["action"] : "lister";

// Traitement selon l'action
switch ($action) {
    case "lister":
        cLister();
        break;
    case "ajouter":
    case "modifier":
        cEditer();
        break;
    case "supprimer":
        cSupprimer();
        break;
    default:
        die(htmlentities("Reqête non autorisée"));
}

if (isset($con)) {
    deconnecterGlobaleBase();
}

// Affiche la liste de toutes les catégories
function cLister() {
    $titre = "Liste des catégories";

    $categories = mRecupererToutesCategories();

    require "vue_lister.php";
}

// Ajout ou modification d'une nouvelle catégorie
function cEditer() {

    global $action;

    if ($action == "ajouter") {
        $idCP = '';
        $_SESSION["ccap_id"] = $idCP;

        $titre = "Ajouter une catégorie";
    }
    else {
        // La requète n'est valide que si on fourni un id de catégorie
        if (!isset($_GET["cca_id"]) and !isset($_POST["cca_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier une catégorie";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // on affiche le formulaire d'édition
        if ($action == "ajouter") {
            $donnees = null;
        }
        else {
            $donnees = mRecupererCategorie($_GET["cca_id"]);
        }
        $erreurs = null;
        $CPs = mRecupererToutesCategoriesPrincipales();
        require "vue_editer.php";
    }
    else {
        // sinon on véfrifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonnees($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            $CPs = mRecupererToutesCategoriesPrincipales();
            require "vue_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie la catégorie...
            if ($action == "ajouter") {
                mAjouterCategorie($donnees);
            }
            else {
                mModifierCategorie($donnees);
            }
            // puis on affiche la liste des catégories
            header("Location:controleur.php?action=lister");
        }
    }
}

// Suppression d'une catégorie
function cSupprimer() {
    // La requète n'est valide que si on fourni un id de catégorie
    if (!isset($_GET["cca_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime la categorie...
    mSupprimerCategorie($_GET["cca_id"]);

    // puis on redirige vers l'action par defaut.
    header("Location:controleur.php?action=lister");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];

    if (!preg_match("#^\d+$#",$donnees['cca_rang'])) {
        $erreurs['rang'] = "Le rang doit être un nombre entier positif.";
    }

    return $erreurs;
}
