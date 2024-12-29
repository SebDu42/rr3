<?php

require "../config/bd.php";

// Vérification des données d'authentification d'un utilisteur
function validerUtilisateur($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableUtilisateurs = $prefixeTables.$tables["utilisateurs"][0];

    $courriel = $con->real_escape_string($donnees["u_courriel"]);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableUtilisateurs` ";
    $requete .= "WHERE `u_courriel` = '$courriel' AND `u_valide` = '1';";

    $resultat = $con->query($requete);

    if ($resultat->num_rows == 0) {
        return false;
    }
    else {
        $motDePasse = $donnees["u_mot_de_passe"];
        $utilisateur = $resultat->fetch_object();
        $hash = $utilisateur->u_mot_de_passe;
        return password_verify($motDePasse, $hash);
    }

}

function recupererUtilisateurParCourriel($courriel) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableUtilisateurs = $prefixeTables.$tables["utilisateurs"][0];

    $courriel = $con->real_escape_string($courriel);

    $requete = "SELECT `u_id`, `u_fk_ur_id`, `u_nom`, `u_prenom`, `u_courriel` ";
    $requete .= "FROM `$tableUtilisateurs` ";
    $requete .= "WHERE `u_courriel` = '$courriel';";

    $utilisateur = $con->query($requete);
    affiche($utilisateur);

    return $utilisateur->fetch_object();

}
