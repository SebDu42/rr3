<?php

session_start();

// Action par défaut
if (isset($_SESSION["utilisateur"])) {
    header("Location: courses/controleur.php?action=mes_performances");
}
else {
    header("Location: ../authentification/controleur.php?action=connecter");
}

?>
