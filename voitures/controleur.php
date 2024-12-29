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
$arret |= isset($_GET["v_id"]) ? !mVoitureExiste($_GET["v_id"]) : false;
$arret |= isset($_GET["uv_id"]) ? !mVoitureUtilisateurExiste($_SESSION["utilisateur"]->u_id, $_GET["uv_id"]) : false;
$arret |= isset($_POST["uv_id"]) ? !mVoitureUtilisateurExiste($_SESSION["utilisateur"]->u_id, $_POST["uv_id"]) : false;
$arret |= isset($_GET["vco_id"]) ? !mConstructeurExiste($_GET["vco_id"])  and $_GET["vco_id"] != '' : false;
if ($arret) die(htmlentities("Reqête non autorisée"));

// Récupération de l'action passée en GET
$action = isset($_GET["action"]) ? $_GET["action"] : "mes_voitures";

// Traitement selon l'action
switch ($action) {
    case "mes_voitures":
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
    deconnecterGlobaleBase();
}

// Affiche la liste de toutes les modèles de voitures ou des voitures de l'utilisateur, éventuellement filtrées par constructeur
function cLister() {

    global $action;

    $titre = $action == "mes_voitures" ? "Liste de mes voitures" : "Liste des voitures";
    $idUtilisateur = $action == "mes_voitures" ? $_SESSION["utilisateur"]->u_id : '';

    if (isset($_GET["vco_id"])) {
        $idConstructeur = $_GET["vco_id"];
    }
    else if (isset($_SESSION["vco_id"])) {
        $idConstructeur = mConstructeurExiste($_SESSION["vco_id"]) ? $_SESSION["vco_id"] : '';
    }
    else {
        $idConstructeur = '';
    }
    $_SESSION["vco_id"] = $idConstructeur;

    if ($action == "mes_voitures") {
        if (isset($_GET["uvs_id"])) {
            $idStatut = $_GET["uvs_id"];
        }
        else if (isset($_SESSION["uvs_id"])) {
            $idStatut = mStatutVoitureExiste($_SESSION["uvs_id"]) ? $_SESSION["uvs_id"] : '';
        }
        else {
            $idStatut = '';
        }
        $_SESSION["uvs_id"] = $idStatut;
    }

    // Récupération de la liste des voitures
    if ($action == "mes_voitures") {
        $voitures = mRecupererToutesVoituresUtilisateur($_SESSION["utilisateur"]->u_id, $idConstructeur, $idStatut);
    }
    else {
        $voitures = mRecupererToutesVoitures($idConstructeur);
    }
    // Récupération de la list des constructeurs
    $constructeurs = mRecupererTousConstructeurs();
    $statuts = mRecupererTousStatuts();

    require "vue_lister.php";
}

// Ajout ou modification d'un modèle de voiture ou d'une voiture de l'utilisateur
function cEditer() {

    global $action;

    if ($action == "ajouter") {
        // Si l'action est ajouter et qu'il y a un v_id dans l'URL...
        if (isset($_GET["v_id"])) {
            // C'est que l'utilisateur veut ajouter la voiture à ses voitures
            mAjouterVoitureUtilisateur($_SESSION["utilisateur"]->u_id, $_GET["v_id"]);
            // On redirige ensuite vers la liste des voitures de l'utilisateur
            $voiture = mRecupererVoiture($_GET["v_id"]);
            header("Location:controleur.php?action=mes_voitures&vco_id=".$voiture["v_fk_vco_id"]);
        }

        if (isset($_GET["vco_id"])) {
            $idConstructeur = $_GET["vco_id"];
        }
        else if (isset($_SESSION["vco_id"])) {
            $idConstructeur = mConstructeurExiste($_SESSION["vco_id"]) ? $_SESSION["vco_id"] : '';
        }
        else {
            $idConstructeur = '';
        }
        $_SESSION["vco_id"] = $idConstructeur;

        $titre = "Ajouter une voiture";

    }
    else {
        // La requète de modification n'est valide que si on fournit un id de voiture
        if (!isset($_GET["v_id"]) and !isset($_POST["v_id"])
        and !isset($_GET["uv_id"]) and !isset($_POST["uv_id"])) {
            die(htmlentities("Reqête non autorisée"));
        }

        $titre = "Modifier une voiture";
    }

    // S'il n'y a pas de données venant d'un formulaire...
    if (count($_POST) == 0) {
        // on affiche le formulaire d'édition
        if ($action == "ajouter") {
            $donnees = null;
        }
        else {
            if (isset($_GET["v_id"])) {
                $donnees = mRecupererVoiture($_GET["v_id"]);
            }
            else {
                $donnees = mRecupererVoitureUtilisateur($_GET["uv_id"]);
            }
        }
        $erreurs = null;
        $statuts = mRecupererTousStatuts();
        $constructeurs = mRecupererTousConstructeurs();
        $classes = mRecupererToutesClasses();
        $transmissions = mRecupererToutesTransmissions();
        $monnaies = mRecupererToutesMonnaies();
        require "vue_editer.php";
    }
    else {
        // sinon on véfrifie les données
        $donnees = $_POST;
        $erreurs = cTesterDonnees($donnees);
        // S'il y a des erreurs...
        if ($erreurs != null) {
            // on affiche à nouveau le formulaire
            if (isset($donnees["uv_id"])) {
                $voiture = mRecupererVoitureUtilisateur($donnees["uv_id"]);
                $donnees["uv_fk_uvs_id"] = $voiture["uv_fk_uvs_id"];
                $donnees["v_fk_vco_id"] = $voiture["v_fk_vco_id"];
                $donnees["v_modele"] = $voiture["v_modele"];
                $donnees["v_fk_vcl_id"] = $voiture["v_fk_vcl_id"];
                $donnees["v_fk_vt_id"] = $voiture["v_fk_vt_id"];
            }
            $statuts = mRecupererTousStatuts();
            $constructeurs = mRecupererTousConstructeurs();
            $classes = mRecupererToutesClasses();
            $transmissions = mRecupererToutesTransmissions();
            $monnaies = mRecupererToutesMonnaies();
            require "vue_editer.php";
        }
        else {
            // sinon on ajoute ou on modifie la voiture...
            if ($action == "ajouter") {
                mAjouterVoiture($donnees);
                // puis on affiche la liste des voitures
                header("Location:controleur.php?action=lister");
            }
            else {
                if (isset($donnees["uv_id"])) {
                    // On modifie une voiture de l'utilisateur...
                    mModifierVoitureUtilisateur($donnees);
                    affiche($donnees);
                    // puis on affiche la liste des voitures de l'utilisateur
                    header("Location:controleur.php?action=mes_voitures");
                }
                else {
                    // On modifie un modèle de voiture...
                    mModifierVoiture($donnees);
                    // puis on affiche la liste des voitures
                    header("Location:controleur.php?action=lister");
                }
            }
        }
    }

}

// Suppression d'un modèle de voiture ou d'une voiture de l'utlisateur
function cSupprimer() {
    // La requète n'est valide que si on fourni un id de modèle de voitures ou de voiture d'utilisateur
    if (!isset($_GET["v_id"]) && !isset($_GET["uv_id"])) {
        die(htmlentities("Reqête non autorisée"));
    }

    // S'il s'agit d'un modèle de voiture...
    if (isset($_GET["v_id"])) {
        // on le supprime...
        mSupprimerVoiture($_GET["v_id"]);
        // puis on redirige vers la liste des voitures disponibles
        header("Location:controleur.php?action=lister");
    }
    else {
        // sinon, il s'agit d'une voiture possédée pas l'utilisateur
        // On vérifie qu'il possède bien la voiture

        // On supprime la voiture...
        mSupprimerVoitureUtilisateur($_GET["uv_id"]);
        // puis on redirige vers la liste des voitures de l'utilisateur
        header("Location:controleur.php?action=mes_voitures");
    }

}

// Vérifie si les données du formulaire sont valides
function cTesterDonnees($donnees) {
    $erreurs = [];

   // Préfixe des tables suivant qu'il s'agit d'un modèle de voiture ou d'une voiture de l'utilisateur
    $p = isset($donnees["uv_id"]) ? "uv_" : "v_";
    // Idem pour le suffixe des améliorations
    $s = isset($donnees["uv_id"]) ? "" : "_max";

    if (!preg_match("#^\d{1,3}$#", $donnees[$p."vitesse"])) {
        $erreurs["vitesse"] = "La vitesse doit être un nombre entier positif avec au plus 3 chiffres.";
    }
    if (!preg_match("#^\d(\.\d{0,2})?$#", $donnees[$p."acceleration"])) {
        $erreurs["acceleration"] = "L'accélération doit être un nombre décimal au format x.xx .";
    }
    if (!preg_match("#^\d{1,2}(\.\d{0,2})?$#", $donnees[$p."freinage"])) {
        $erreurs["freinage"] = "Le freinage doit être un nombre décimal au format xx.xx .";
    }
    if (!preg_match("#^\d(\.\d{0,2})?$#", $donnees[$p."adherence"])) {
        $erreurs["adherence"] = "L'adhérence doit être un nombre décimal au format x.xx .";
    }
    if (!preg_match("#^\d{1,3}(\.\d{0,1})?$#", $donnees[$p."ip"])) {
        $erreurs["ip"] = "L'indice de performance doit être un nombre décimal au format xxx.x .";
    }

    if ($p == "v_") {
        if (!preg_match("#^\d*$#", $donnees["v_prix"])) {
            $erreurs["prix"] = "Le prix de la voiture doit être un nombre entier positif.";
        }
        if (!preg_match("#^\d+$#", $donnees["v_cout_revision"])) {
            $erreurs["cout_revision"] = "Le coût de la révision doit être un nombre entier positif.";
        }
        if (!preg_match("#^\d{1,2}:\d{1,2}(:\d{1,2})?$#", $donnees["v_duree_revision"])) {
            $erreurs["duree_revision"] = "La durée de la révision doit être un temps au format hh:mm.";
        }
        if (!preg_match("#^\d*$#", $donnees["v_cout_revision_instantanee"])) {
            $erreurs["cout_revision_instantanee"] = "Le coût de la révision instantanée doit être un nombre entier positif.";
        }
    }

    if (!preg_match("#^\d*$#", $donnees[$p."am_moteur".$s])) {
        $erreurs["am_moteur_max"] = "Le niveau maximum du moteur doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_transmission".$s])) {
        $erreurs["am_transmission_max"] = "Le niveau maximum de la transmission doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_carrosserie".$s])) {
        $erreurs["am_carrosserie_max"] = "Le niveau maximum de la carrosserie doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_suspension".$s])) {
        $erreurs["am_suspension_max"] = "Le niveau maximum de la suspension doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_pot".$s])) {
        $erreurs["am_pot_max"] = "Le niveau maximum du pot d'échappement doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_freins".$s])) {
        $erreurs["am_freins_max"] = "Le niveau maximum des freins doit être un nombre entier positif.";
    }
    if (!preg_match("#^\d*$#", $donnees[$p."am_roues".$s])) {
        $erreurs["am_roues_max"] = "Le niveau maximum des pneus et des roues doit être un nombre entier positif.";
    }

    return $erreurs;
}
