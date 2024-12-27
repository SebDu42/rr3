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
$arret |= isset($_GET["vco_id"]) ? !mConstructeurExiste($_GET["vco_id"]) : false;
if ($arret) die(htmlentities("Reqête non autorisée"));

// Récupération de l'action passée en GET
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
    deconnecterGlobaleBase($con);
}

// Affiche la liste de tous les constructeurs
function cLister() {
    $titre = "Liste des constructeurs";

    $constructeurs = mRecupererTousConstructeurs();

    require "vue_lister.php";
}

// Ajout ou modification d'un nouveau constructeur
function cEditer() {

    global $action;

    if ($action == "ajouter") {
        $titre = "Ajouter un constructeur";
    }
    else {
        // La requète n'est valide que si on fourni un id de constructeur
        if (!isset($_GET["vco_id"]) and !isset($_POST["vco_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier un constructeur";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // // on affiche le formulaire d'édition
        if ($action == "ajouter") {
            $donnees = null;
        }
        else {
            $donnees = mRecupererConstructeur($_GET["vco_id"]);
        }
        $erreurs = null;
        require "vue_editer.php";
    }
    else {
        // sinon on véfrifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonnees($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            require "vue_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie le constructeur...
            if ($action == "ajouter") {
                mAjouterConstructeur($donnees);
            }
            else {
                mModifierConstructeur($donnees);
            }
            // puis on affiche la liste des constructeurs
            header("Location:controleur.php?action=lister");
        }
    }
}

// Suppression d'un constructeur
function cSupprimer() {
    // La requète n'est valide que si on fourni un id de constructeur
    if (!isset($_GET["vco_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime le constructeur...
    mSupprimerConstructeur($_GET["vco_id"]);

    // puis on redirige vers l'action par defaut.
    header("Location:controleur.php?action=lister");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];
    // Auncune vérification nécessaire pour les constructeurs
    return $erreurs;
}

?>
