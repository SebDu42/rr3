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
$arret |= isset($_GET["ci_id"]) ? !mCircuitExiste($_GET["ci_id"]) : false;
$arret |= isset($_GET["cic_id"]) ? !mConfigurationExiste($_GET["cic_id"]) : false;
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

// Affiche la liste des circuits et de leurs configurations
function cLister() {
    $titre = "Liste des circuits";

    $circuits = mRecupererTousCircuits();

    require "vue_lister.php";
}

// Ajout ou modification d'un circuit ou d'une configuration
function cEditer() {

    global $action;

    if ($action == "ajouter") {
        // Si l'action est ajouter et qu'il y a un ci_id dans l'URL...
        if (isset($_GET["ci_id"])) {
            // C'est qu'on veut ajouter une configuration à un circuit
            $titre = "Ajouter une configuration";
        }
        else {
            // Sinon il s'agit d'un nouveau circuit
            $titre = "Ajouter un circuit";
        }

    }
    else {
        // La requète de modification n'est valide que si on fournit un id de configuration
        if (!isset($_GET["cic_id"]) and !isset($_POST["cic_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier une configuration";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // on affiche le formulaire
        if ($action == "ajouter") {
            if (isset($_GET["ci_id"])) {
                $donnees = mRecupererCircuit($_GET["ci_id"]);
            }
            else {
                $donnees = null;
            }
        }
        else {
            $donnees = mRecupererConfiguration($_GET["cic_id"]);
        }
        $erreurs = null;

        require "vue_editer.php";
    }
    else {
        // sinon on vérifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonnees($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // On affiche à nouveau le formulaire
            require "vue_editer.php";
        }
        else {
            // Sinon on ajoute ou on modifie la configuration
            if ($action == "ajouter") {
                // On ajoute la configuration...
                mAjouterConfiguration($donnees);
                // puis on affiche la liste des circuits
                header("Location:controleur.php?action=lister");
            }
            else {
                // On modifie la configuration...
                mModifierConfiguration($donnees);
                // puis on affiche la liste des circuits
                header("Location:controleur.php?action=lister");
            }
        }
    }
}

// Suppression d'un circuit ou d'une configuration
function cSupprimer() {
    // La requète n'est valide que si on fourni un id de configuration
    if (!isset($_GET["cic_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime la configuration
    mSupprimerConfiguration($_GET["cic_id"]);
    // puis on redirige vers la liste des circuits
    header("Location:controleur.php?action=lister");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];

    if (!preg_match("#^(\d{1,2}(\.\d{0,3})?)?$#", $donnees["cic_longueur"])) {
        $erreurs["longueur"] = "La longueur de la piste doit être un nombre décimal au format xx.xxx .";
    }

    return $erreurs;
}

?>
