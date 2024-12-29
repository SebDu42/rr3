<?php

session_start();

require "modele.php";
require "../config/config.php";

// Récupération des données passées en GET
$action = isset($_GET["action"]) ? $_GET["action"] : "connecter";

// Traitement selon l'action
switch ($action) {
    case "connecter":
        connecter();
        break;
    case "deconnecter":
        deconnecter();
        break;
    default:
        die(htmlentities("Requête non autorisée"));
}

if (isset($con)) {
    deconnecterGlobaleBase();
}

// Connexion d'un utlisateur
function connecter() {
    $titre = "Authentification";
    // S'il n'y a pas de données de connexion...
    if (!isset($_POST['u_courriel'])) {
        // on affiche le formulaire de connexion.
        require "vue.php";
    }
    else {
        // On vérifie que les informations d'authentification sont bonnes
        $erreurs = testDonnees($_POST);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche de nouveau le formulaire
            require "vue.php";
        }
        else {
            // Sinon l'authentification est réussie
            validerAuthentification($_POST["u_courriel"]);
            // On redirige vers la page par defaut
            header("Location:../index.php");
        }
    }
}

function deconnecter() {
    session_destroy();
    header("Location: controleur.php");
}

function testDonnees($donnees) {
    $erreurs = null;
    if (!validerUtilisateur($donnees)) {
        $erreurs["authentification"] = "L'adresse de courriel et/ou le mot de passe ne sont pas valident";
    }

    return $erreurs;
}

function validerAUthentification($courriel) {
    $utilisateur = recupererUtilisateurParCourriel($courriel);
    $_SESSION["utilisateur"] = $utilisateur;
}
