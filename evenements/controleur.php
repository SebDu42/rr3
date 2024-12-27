<?php

// FIXME: vérifier que toutes les données passées en GET sont vérifiées au début du fichier controleur.

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
$arret |= isset($_GET["cca_id"]) ? !mCategorieExiste($_GET["cca_id"]) and $_GET["cca_id"] != '' : false;
$arret |= isset($_GET["cs_id"]) ? !mSerieExiste($_GET["cs_id"]) and $_GET["cs_id"] != '' : false;
$arret |= isset($_GET["ce_id"]) ? !mEvenementExiste($_GET["ce_id"]) and $_GET["ce_id"] != '' : false;
$arret |= isset($_GET["vco_id"]) ? !mConstructeurExiste($_GET["vco_id"]) and $_GET["vco_id"] != '' : false;
$arret |= isset($_GET["v_id"]) ? !mVoitureExiste($_GET["v_id"]) : false;
$arret |= isset($_GET["ve_id"]) ? !mExclusionExiste($_GET["ve_id"]) : false;
$arret |= isset($_GET["va_id"]) ? !mAssociationExiste($_GET["va_id"]) : false;
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
    case "associer_voitures":
        cAssocierVoitures();
        break;
    case "retablir_voiture":
        cRetablirVoiture();
        break;
    case "exclure_voiture":
        cExclureVoiture();
        break;
    default:
        die(htmlentities("Reqête non autorisée"));
}

if (isset($con)) {
    deconnecterGlobaleBase($con);
}

// Affiche la liste de tous les événements, éventuellement filtrés par catégorie et série
function cLister() {
    $titre = "Liste des événements";

    // Récupération de la catégorie pour le filtrage éventuel
    if (isset($_GET["cca_id"])) {
        $idCategorie = $_GET["cca_id"];
        $_SESSION["cca_id"] = $idCategorie;
    }
    else if (isset($_SESSION["cca_id"])) {
        $idCategorie = mCategorieExiste($_SESSION["cca_id"]) ? $_SESSION["cca_id"] : '';
    }
    else {
        $idCategorie = '';
    }
    $_SESSION["cca_id"] = $idCategorie;

    // Récupération de la série pour le filtrage éventuel
    if (isset($_GET["cs_id"])) {
        $idSerie = $_GET["cs_id"];
    }
    else if (isset($_SESSION["cs_id"])) {
        $idSerie = mSerieExiste($_SESSION["cs_id"]) ? $_SESSION["cs_id"] : '';
    }
    else {
        $idSerie = '';
    }
    $_SESSION["cs_id"] = $idSerie;

    $categories = mRecupererToutesCategories();
    $series = mRecupererToutesSeries($idCategorie);
    $evenements = mRecupererTousEvenements($idCategorie, $idSerie);

    require "vue_lister.php";
}

// Ajout ou modification d'un événement
function cEditer() {

    global $action;

    if ($action == "ajouter") {
        $titre = "Ajouter un événement";
    }
    else {
        // La requète de modification n'est valide que si on fournit un id d'événement
        if (!isset($_GET["ce_id"]) and !isset($_POST["ce_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier un événement";
    }

    if (isset($_GET["cca_id"])) {
        $idCategorie = $_GET["cca_id"];
    }
    else if (isset($_SESSION["cca_id"])) {
        $idCategorie = mCategorieExiste($_SESSION["cca_id"]) ? $_SESSION["cca_id"] : '';
    }
    else {
        $idCategorie = '';
    }
    $_SESSION["cca_id"] = $idCategorie;

    if (isset($_GET["cs_id"])) {
        $idSerie = $_GET["cs_id"];
    }
    else if (isset($_SESSION["cs_id"])) {
        $idSerie = mSerieExiste($_SESSION["cs_id"]) ? $_SESSION["cs_id"] : '';
    }
    else {
        $idSerie = '';
    }
    $_SESSION["cs_id"] = $idSerie;

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // on affiche le formulaire d'édition
        if ($action == "ajouter") {
            $donnees = null;
        }
        else {
            $donnees = mRecupererEvenement($_GET["ce_id"]);
        }
        $erreurs = null;
        $categories = mRecupererToutesCategories();
        $series = mRecupererToutesSeries($idCategorie);
        require "vue_editer.php";
    }
    else {
        // sinon on véfrifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonnees($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            $categories = mRecupererToutesCategories();
            $series = mRecupererToutesSeries($idCategorie);
            require "vue_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie la catégorie...
            if ($action == "ajouter") {
                mAjouterEvenement($donnees);
            }
            else {
                mModifierEvenement($donnees);
            }
            // Récupération de la série de l'événement pour le filtrage
            $_SESSION["cs_id"] = $donnees["ce_fk_cs_id"];
            // puis on affiche la liste des catégories
            header("Location:controleur.php?action=lister");
        }
    }
}

// Supression d'un événement
function cSupprimer() {
    // La requète n'est valide que si on fournit un id d'événement
    if (!isset($_GET["ce_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime l'événement...
    mSupprimerEvenement($_GET["ce_id"]);

    // puis on redirige vers la liste des événements
    header("Location:controleur.php?action=lister");
}

// Affiche la liste des voitures associées à l'événement
// et celle des voitures disponibles.
function cAssocierVoitures() {
    // La requète n'est valide que si on fournit un id d'événement
    if (!isset($_GET["ce_id"]) and !isset($_SESSION["ce_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    $titre = "Associer des voitures à l'événement";

    // On récupère l'id de l'événement
    if (isset($_GET["ce_id"])) {
        $idEvenement = $_GET["ce_id"];
    }
    else if (isset($_SESSION["ce_id"])) {
        $idEvenement = mEvenementExiste($_SESSION["ce_id"]) ? $_SESSION["ce_id"] : '';
    }
    $_SESSION["ce_id"] = $idEvenement;

    // Récupération des données pour les tableaux
    $evenement = mRecupererEvenement($idEvenement);
    $voituresDisponibles = mRecupererVoituresExclues($idEvenement);
    $voituresAssociees = mRecupererVoituresAssociees($idEvenement);

    require "vue_associer.php";
}

// Ajoute une voiture à la liste des voitures associées à l'événement
function cRetablirVoiture() {
    // La requète n'est valide que si on fournit un id d'association
    if (!isset($_GET["ve_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On retire la voiture de la liste des voitures non associées
    mRetirerExclusion($_GET["ve_id"]);

    // Puis on redirige vers la liste des voitures associées
    header("Location:controleur.php?action=associer_voitures");
}

// Supprime une voiture de la liste des voitures associées
function cExclureVoiture() {
    // La requète n'est valide que si on fournit un id d'événement
    // et un id de voiture
    if (!isset($_GET["ce_id"]) or !isset($_GET["va_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On récupère l'id de l'événement
    $idEvenement = $_GET["ce_id"];
    $_SESSION["ce_id"] = $idEvenement;

    // On récupère l'id de la voiture
    $idAssociation = $_GET["va_id"];
    $_SESSION["va_id"] = $idAssociation;

    // On retire la voiture de la liste des voitures non associées à l'événement
    mAjouterExclusion($idEvenement, $idAssociation);

    // Puis on redirige vers la liste des voitures associées
    header("Location:controleur.php?action=associer_voitures");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];

    if (!preg_match("#^\d+$#",$donnees['ce_rang'])) {
        $erreurs['rang'] = "Le rang doit être un nombre entier positif.";
    }

    return $erreurs;
}


?>
