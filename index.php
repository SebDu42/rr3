<?php

session_start();

// Action par défaut
if (isset($_SESSION["utilisateur"])) {
    header("Location: constructeurs/controleur.php");
}
else {
    header("Location: authentification/controleur.php?action=connecter");
}
