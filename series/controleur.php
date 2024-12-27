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
$arret |= isset($_GET["cca_id"]) ? !mCategorieExiste($_GET["cca_id"]) and $_GET["cca_id"] != '' : false;
$arret |= isset($_GET["css_id"]) ? !mStatutSerieExiste($_GET["css_id"]) and $_GET["css_id"] != '' : false;
$arret |= isset($_GET["cs_id"]) ? !mSerieExiste($_GET["cs_id"]) : false;
$arret |= isset($_GET["vco_id"]) ? !mConstructeurExiste($_GET["vco_id"]) and $_GET["vco_id"] != '' : false;
$arret |= isset($_GET["v_id"]) ? !mVoitureExiste($_GET["v_id"]) : false;
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
    case "ajouter_voiture":
        cAjouterVoiture();
        break;
    case "retirer_voiture":
        cRetirerVoiture();
        break;
    default:
        die(htmlentities("Reqête non autorisée"));
}

if (isset($con)) {
    deconnecterGlobaleBase($con);
}

// Affiche la liste de toutes les séries, éventuellement filtrées par catégorie
function cLister() {
    $titre = "Liste des series";

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

    if (isset($_GET["css_id"])) {
        $idStatut = $_GET["css_id"];
    }
    else if (isset($_SESSION["css_id"])) {
        $idStatut = mStatutSerieExiste($_SESSION["css_id"]) ? $_SESSION["css_id"] : '';
    }
    else {
        $idStatut = '';
    }
    $_SESSION["css_id"] = $idStatut;

    $series = mRecupererToutesSeries($idCategorie, $idStatut);
    $categories = mRecupererToutesCategories();
    $statuts = mRecupererTousStatutsSeries();

    require "vue_lister.php";
}

// Ajout ou modification d'une série
function cEditer() {

    global $action;

    if ($action == "ajouter") {

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

        $idStatut = '1';
        //$_SESSION["css_id"] = $idStatut;

        $titre = "Ajouter une série";
    }
    else {
        // La requète de modification n'est valide que si on fournit un id de série
        if (!isset($_GET["cs_id"]) and !isset($_POST["cs_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier une série";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // on affiche le formulaire d'édition
        if ($action == "ajouter") {
            $donnees = null;
        }
        else {
            $donnees = mRecupererSerie($_GET["cs_id"]);
        }
        $erreurs = null;
        $categories = mRecupererToutesCategories();
        $statuts = mRecupererTousStatutsSeries();
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
            $statuts = mRecupererTousStatutsSeries();
            require "vue_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie la catégorie...
            if ($action == "ajouter") {
                mAjouterSerie($donnees);
            }
            else {
                mModifierSerie($donnees);
            }
            // Récupération de la catégorie de la série pour le filtrage
            $_SESSION["cca_id"] = $donnees["cs_fk_cca_id"];
            $_SESSION["css_id"] = $donnees["cs_fk_css_id"];
            // puis on affiche la liste des séries
            header("Location:controleur.php?action=lister");
        }
    }
}

// Suppression d'une série
function cSupprimer() {
    // La requète n'est valide que si on fournit un id de série
    if (!isset($_GET["cs_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime la série...
    mSupprimerSerie($_GET["cs_id"]);

    // puis on redirige vers la liste des séries
    header("Location:controleur.php?action=lister");
}

// Affiche la liste des voitures associées à la série
// et celle des voitures disponibles.
function cAssocierVoitures() {
    // La requète n'est valide que si on fournit un id de série
    if (!isset($_GET["cs_id"]) and !isset($_SESSION["cs_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    $titre = "Associer des voitures à la série";

    // On récupère l'id de la série
    if (isset($_GET["cs_id"])) {
        $idSerie = $_GET["cs_id"];
    }
    else if (isset($_SESSION["cs_id"])) {
        $idSerie = mSerieExiste($_SESSION["cs_id"]) ? $_SESSION["cs_id"] : '';
    }
    $_SESSION["cs_id"] = $idSerie;

    // On récupère l'éventuel id de constructeur pour filtrer les voitures
    if (isset($_GET["vco_id"])) {
        $idConstructeur = mConstructeurExiste($_GET["vco_id"]) ? $_GET["vco_id"] : '';
    }
    else if (isset($_SESSION["vco_id"])) {
        $idConstructeur = mConstructeurExiste($_SESSION["vco_id"]) ? $_SESSION["vco_id"] : '';
    }
    else {
        $idConstructeur = '';
    }
    $_SESSION["vco_id"] = $idConstructeur;

    // Récupération des données pour les tableaux
    $serie = mRecupererSerie($idSerie);
    $constructeurs = mRecupererTousConstructeurs();
    $voituresDisponibles = mRecupererVoituresDisponibles($idSerie, $idConstructeur);
    $voituresAssociees = mRecupererVoituresAssociees($idSerie);

    require "vue_associer.php";
}

// Ajoute une voiture à la liste des voitures associées à la série
function cAjouterVoiture() {
    // La requète n'est valide que si on fournit un id de série
    // et un id de voiture
    if (!isset($_GET["cs_id"]) or !isset($_GET["v_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On récupère l'id de l'événement
    $idSerie = $_GET["cs_id"];
    $_SESSION["cs_id"] = $idSerie;

    // On récupère l'id de la voiture
    $idVoiture = $_GET["v_id"];
    $_SESSION["v_id"] = $idVoiture;

    // On associe la voiture à l'événement
    mAjouterAssociation($idSerie, $idVoiture);

    // Puis on redirige vers la liste des voitures associées
    header("Location:controleur.php?action=associer_voitures");
}

// Retire une voiture de la liste des voitures associées à une série
function cRetirerVoiture() {
    // La requète n'est valide que si on fournit un id d'association
    if (!isset($_GET["va_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime l'association
    mSupprimerAssociation($_GET["va_id"]);

    // Puis on redirige vers la liste des voitures associées
    header("Location:controleur.php?action=associer_voitures");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];

    if (!preg_match("#^\d+$#",$donnees['cs_rang_principal'])) {
        $erreurs['rang_principal'] = "Le rang principal doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d+$#",$donnees['cs_rang_secondaire'])) {
        $erreurs['rang_secondaire'] = "Le rang secondaire doit être un nombre entier positif.";
    }
    if (((!preg_match("#^\d+$#",$donnees['cs_avancement'])))
    or ($donnees['cs_avancement'] > 100)) {
        $erreurs['avancement'] = "L'avancement doit être un nombre entier positif compris entre 0 et 100.";
    }

    return $erreurs;
}

?>
