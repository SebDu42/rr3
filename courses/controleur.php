<?php

// FIXME: vérifier que toutes les données passées en GET sont vérifiées au début du fichier controleur.
// TODO: stocker les durées de course en ms, puis formater pour l'affichage.
// TODO: ajouter des filtres sur la voiture et le circuit pour les listes.

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
$arret |= isset($_GET["ce_id"]) ? !mEvenementExiste($_GET["ce_id"]) and $_GET["ce_id"]  != '' : false;
$arret |= isset($_GET["c_id"]) ? !mCourseExiste($_GET["c_id"]) : false;
$arret |= isset($_GET["up_id"]) ? !mPerformanceExiste($_GET["up_id"], $_SESSION["utilisateur"]->u_id) : false;
$arret |= isset($_POST["up_id"]) ? !mPerformanceExiste($_POST["up_id"], $_SESSION["utilisateur"]->u_id) : false;
$arret |= isset($_GET["vco_id"]) ? !mConstructeurExiste($_GET["vco_id"]) and $_GET["vco_id"] != '' : false;
if ($arret) die(htmlentities("Reqête non autorisée"));

// Récupération des données passées en GET
$action = isset($_GET["action"]) ? $_GET["action"] : "mes_performances";

// Traitement selon l'action
switch ($action) {
    case "mes_performances":
        cListerPerformances();
        break;
    case "ajouter_performance":
    case "modifier_performance":
        cEditerPerformances();
        break;
    case "supprimer_performance":
        cSupprimerPerformance();
        break;
    case "lister_courses":
        cListerCourses();
        break;
    case "ajouter_course":
    case "modifier_course":
        cEditerCourse();
        break;
    case "supprimer_course":
        cSupprimerCourse();
        break;
    default:
        die(htmlentities("Reqête non autorisée"));
}

if (isset($con)) {
    deconnecterGlobaleBase($con);
}

// Affiche la liste de toutes les performances de l'utilisateur, éventuellement filtrés par catégorie, série et événement;
function cListerPerformances() {

    $titre = "Liste de mes performances";

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

    // Récupération de l'événement pour le filtrage éventuel
    if (isset($_GET["ce_id"])) {
        $idEvenement = $_GET["ce_id"];
        $_SESSION["ce_id"] = $idEvenement;
    }
    else if (isset($_SESSION["ce_id"])) {
        $idEvenement = mEvenementExiste($_SESSION["ce_id"]) ? $_SESSION["ce_id"] : '';
    }
    else {
        $idEvenement = '';
    }
    $_SESSION["ce_id"] = $idEvenement;

    $categories = mRecupererToutesCategories();
    $series = mRecupererToutesSeries($idCategorie);
    $evenements = mRecupererTousEvenements($idCategorie, $idSerie);
    $performances = mRecupererToutesPerformances($_SESSION["utilisateur"]->u_id, $idCategorie, $idSerie, $idEvenement);

    require "vue_performances_lister.php";
}

// Ajout ou modification de performances de l'utilisateur
function cEditerPerformances() {

    global $action;

    if ($action == "ajouter_performance") {
        // La requète d'ajout n'est valide que si on fournit un id de course
        if (!isset($_GET["c_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }

        // On ajoute une performance vide...
        mAjouterPerformance($_SESSION["utilisateur"]->u_id, $_GET["c_id"]);
        // ...puis on redirige vers la liste des performances de l'utilisateur, en filtrant avec l'événement de la course.
        $course = mRecupererCourse($_GET["c_id"]);
        header("Location:controleur.php?action=mes_performances&ce_id=".$course["c_fk_ce_id"]);
    }
    else {
        // La requète de modification n'est valide que si on fournit un id de performace
        if (!isset($_GET["up_id"]) and !isset($_POST["up_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }
        $titre = "Modifier une performance";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // On affiche le formulaire d'édition
        $donnees = mRecupererPerformance($_GET["up_id"]);
        $erreurs = null;
        $voitures = mRecupererVoituresAssociees($_SESSION["utilisateur"]->u_id, $donnees["up_fk_c_id"]);
        require "vue_performances_editer.php";
    }
    else {
        // sinon on vérifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonneesPerformance($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            $voitures = mRecupererVoituresAssociees($_SESSION["utilisateur"]->u_id, $donnees["up_fk_c_id"]);
            require "vue_performances_editer.php";
        }
        else {
            // sinon on modifie la performance
            mModifierPerformance($donnees);
            // puis on affiche la liste des performances
            header("Location:controleur.php?action=mes_performances");
        }
    }
}

// Supression de performances de l'utlisateur
function cSupprimerPerformance() {
    // La requète n'est valide que si on fournit un id de performances
    if (!isset($_GET["up_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime l'événement...
    mSupprimerPerformance($_GET["up_id"]);

    // puis on redirige vers la liste des événements
    header("Location:controleur.php?action=mes_performances");
}


// Affiche la liste de toutes les courses, éventuellement filtrés par catégorie, série et événement;
function cListerCourses() {

    $titre = "Liste des courses";

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

    // Récupération de l'événement pour le filtrage éventuel
    if (isset($_GET["ce_id"])) {
        $idEvenement = $_GET["ce_id"];
        $_SESSION["ce_id"] = $idEvenement;
    }
    else if (isset($_SESSION["ce_id"])) {
        $idEvenement = mEvenementExiste($_SESSION["ce_id"]) ? $_SESSION["ce_id"] : '';
    }
    else {
        $idEvenement = '';
    }
    $_SESSION["ce_id"] = $idEvenement;

    $categories = mRecupererToutesCategories();
    $series = mRecupererToutesSeries($idCategorie);
    $evenements = mRecupererTousEvenements($idCategorie, $idSerie);
    $courses = mRecupererToutesCourses($idCategorie, $idSerie, $idEvenement);

    require "vue_courses_lister.php";
}

// Ajout ou modification d'une courses
function cEditerCourse() {

    global $action;

    if ($action == "ajouter_course") {

        if (isset($_GET["ce_id"])) {
            $idEvenement = $_GET["ce_id"];
        }
        else if (isset($_SESSION["ce_id"])) {
            $idEvenement = mEvenementExiste($_SESSION["ce_id"]) ? $_SESSION["ce_id"] : '';
        }
        else {
            $idEvenement = '';
        }
        $_SESSION["ce_id"] = $idEvenement;

        $titre = "Ajouter une course";
    }
    else {
        // La requète de modification n'est valide que si on fournit un id de course
        if (!isset($_GET["c_id"]) and !isset($_POST["c_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }

        $titre = "Modifier une course";
    }

    // Récupération de la catégorie de courses pour le filtrage
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

    // Récupération de la série de courses pour le filtrage
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
        if ($action == "ajouter_course") {
            $donnees = null;
        }
        else {
            $donnees = mRecupererCourse($_GET["c_id"]);
        }
        $erreurs = null;
        $categories = mRecupererToutesCategories();
        $series = mRecupererToutesSeries($idCategorie);
        $evenements = mRecupererTousEvenements($idCategorie, $idSerie);
        $types = mRecupererTousTypes();
        $circuits = mRecupererTousCircuits();
        $conditions = mRecupererToutesConditions();
        $monnaies = mRecupererToutesMonnaies();
        require "vue_courses_editer.php";
    }
    else {
        // sinon on vérifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonneesCourse($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            $categories = mRecupererToutesCategories();
            $series = mRecupererToutesSeries($idCategorie);
            $evenements = mRecupererTousEvenements($idCategorie, $idSerie);
            $types = mRecupererTousTypes();
            $circuits = mRecupererTousCircuits();
            $conditions = mRecupererToutesConditions();
            $monnaies = mRecupererToutesMonnaies();
            require "vue_courses_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie la course
            if ($action == "ajouter_course") {
                mAjouterCourse($donnees);
            }
            else {
                mModifierCourse($donnees);
            }
            // Récupération de l'événement de la course pour le filtrage
            $_SESSION["ce_id"] = $donnees["c_fk_ce_id"];
            // puis on affiche la liste des courses
            header("Location:controleur.php?action=lister_courses");
        }
    }
}

// Supression d'une courses
function cSupprimerCourse() {
    // La requète n'est valide que si on fournit un id de course
    if (!isset($_GET["c_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // On supprime l'événement...
    mSupprimerCourse($_GET["c_id"]);

    // puis on redirige vers la liste des courses
    header("Location:controleur.php?action=lister_courses");
}

// Vérifie si les données du formulaire sont valides
function cTesterDonneesCourse($donnees) {
    $erreurs = [];

    if (!preg_match("#^\d+$#",$donnees['c_rang'])) {
        $erreurs['rang'] = "Le rang doit être un nombre entier positif.";
    }
    if (!preg_match("#^(\d{1,3}(\.\d)?)?$#",$donnees['c_ip_min'])) {
        $erreurs['ip_min'] = "L'IP minimum doit être un nombre décimal positif au format xxx.x .";
    }
    if (!preg_match("#^\d*$#",$donnees['c_nb_tours'])) {
        $erreurs['nb_tours'] = "Le nombre de tours doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#",$donnees['c_nb_concurrents'])) {
        $erreurs['nb_concurrents'] = "Le nombre de concurrents doit être un nombre entier positif.";
    }

    return $erreurs;
}

function cTesterDonneesPerformance($donnees) {
    $erreurs = [];

    if (!preg_match("#^\d*$#",$donnees['up_classement'])) {
        $erreurs['classement'] = "Le classement doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#",$donnees['up_recompense'])) {
        $erreurs['recompense'] = "La récompense doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#",$donnees['up_reputation'])) {
        $erreurs['reputation'] = "La réputation doit être un nombre entier positif.";
    }
    if ($donnees["up_temps"] != '') {
        if (!preg_match("#^\d{1,2}:\d{1,2}(\.\d{1,3})?$#", $donnees["up_temps"])) {
            $erreurs["temps"] = "La durée de la course doit être un temps au format mm:ss.sss .";
        }
    }
    if ($donnees["up_vitesse"] != '') {
        if (!preg_match("#^\d{1,3}(\.\d{1,2})?$#", $donnees["up_vitesse"])) {
            $erreurs["vitesse"] = "La vitesse de la course doit être un nombre décimal positif au format xxx.xx .";
        }
    }
    if ($donnees["up_distance"] != '') {
        if (!preg_match("#^(-)?\d{1,4}(\.\d{1,3})?$#", $donnees["up_distance"])) {
            $erreurs["distance"] = "La distance doit être un nombre décimal au format xxxx.xxx .";
        }
    }
    if ($donnees["up_nb_depassements"] != '') {
        if (!preg_match("#^\d+$#",$donnees["up_nb_depassements"])) {
            $erreurs["nb_depassements"] = "Le nombre de dépassements doit être un nombre entier positif.";
        }
    }

    return $erreurs;
}

?>
