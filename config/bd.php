<?php

// connection à la base de données
function connecterBase() {

    global $serveurBD;
    global $utilisateurBD;
    global $motDePasseBD;
    global $base;
    global $con;

    if (! isset($con)) {
        $con = new mysqli($serveurBD, $utilisateurBD, $motDePasseBD, $base);
        if ($con->connect_error) {
            die('#Erreur de connexion ('.$con->connect_errno.') : '.$con->connect_error);
        }
        $con->query("SET NAMES UTF8;");
    }

    return $con;
}

function deconnecterGlobaleBase() {
    global $con;
    mysqli_close($con);
}
