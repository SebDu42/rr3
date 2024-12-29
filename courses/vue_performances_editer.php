<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=modifier_performance";
$urlAnnuler = "controleur.php?action=mes_performances";

// Liste pour la sélection de la voiture
$idVoiture = isset($donnees["up_fk_uv_id"]) ? $donnees["up_fk_uv_id"] : '';
$selection = $idVoiture == '' ? " selected" : '';
$optionsVoiture = "\t\t\t<option value='' $selection>Auncune</option>\n";
while ($voiture = $voitures->fetch_object()) {
    $selection = $voiture->uv_id == $idVoiture ? " selected" : '';
    $optionsVoiture .= "\t\t\t<option value=\"$voiture->uv_id\"$selection>$voiture->vco_nom - $voiture->v_modele</option>\n";
}

// Récupération des données
$id = isset($donnees["up_id"]) ? $donnees["up_id"] : '';
$ccaid = isset($donnees["cca_id"]) ? $donnees["cca_id"] : '';
$categorie = isset($donnees["cca_nom"]) ? $donnees["cca_nom"] : '';
$categorie = isset($donnees["cca_rang"]) ? $donnees["cca_rang"]."- ".$categorie : $categorie;
$csid = isset($donnees["cs_id"]) ? $donnees["cs_id"] : '';
$serie = isset($donnees["cs_nom"]) ? $donnees["cs_nom"] : '';
$serie = isset($donnees["cs_rang_principal"]) & isset($donnees["cs_rang_secondaire"]) ? $donnees["cs_rang_principal"].'.'.$donnees["cs_rang_secondaire"]."- ".$serie : $serie;
$ceid = isset($donnees["ce_id"]) ? $donnees["ce_id"] : '';
$evenement = isset($donnees["ce_nom"]) ? $donnees["ce_nom"] : '';
$evenement = isset($donnees["ce_rang"]) ? $donnees["ce_rang"]."- " .$evenement : $evenement;
$idCourse = isset($donnees["up_fk_c_id"]) ? $donnees["up_fk_c_id"] : '';
$course = isset($donnees["ct_nom"]) ? $donnees["ct_nom"] : '';
$course = isset($donnees["c_rang"]) ? $donnees["c_rang"]."- " .$course : $course;
$circuit = isset($donnees["ci_nom"]) ? $donnees["ci_nom"] : '';
$circuit = isset($donnees["cic_nom"]) ? $circuit.' - '.$donnees["cic_nom"] : $circuit;
$classement = isset($donnees["up_classement"]) ? $donnees["up_classement"] : '';
$recompense = isset($donnees["up_recompense"]) ? $donnees["up_recompense"] : '';
$monnaie = isset($donnees["m_nom"]) ? $donnees["m_nom"] : '';
$reputation =  isset($donnees["up_reputation"]) ? $donnees["up_reputation"] : '';
$temps = isset($donnees["up_temps"]) ? $donnees["up_temps"] : '';
$vitesse = isset($donnees["up_vitesse"]) ? $donnees["up_vitesse"] : '';
$distance = isset($donnees["up_distance"]) ? $donnees["up_distance"] : '';
$typeCourse = isset($donnees["ct_id"]) ? $donnees["ct_id"] : '';
$unite_distance = $typeCourse == -1 ? 'm' : "km";
$nbDepassements = isset($donnees["up_nb_depassements"]) ? $donnees["up_nb_depassements"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["up_id"] = $id;
$_SESSION["ce_id"] = $ceid;
$_SESSION["cs_id"] = $csid;
$_SESSION["cca_id"] = $ccaid;

// Récupération des éventuelles erreurs de saisie
$erreurClassement = isset($erreurs["classement"]) ? $erreurs["classement"] : '';
$erreurRecompense = isset($erreurs["recompense"]) ? $erreurs["recompense"] : '';
$erreurReputation = isset($erreurs["reputation"]) ? $erreurs["reputation"] : '';
$erreurTemps = isset($erreurs["temps"]) ? $erreurs["temps"] : '';
$erreurVitesse = isset($erreurs["vitesse"]) ? $erreurs["vitesse"] : '';
$erreurDistance = isset($erreurs["distance"]) ? $erreurs["distance"] : '';
$erreurNbDepassements = isset($erreurs["nb_depassements"]) ? $erreurs["nb_depassements"] : '';

$contenu .= <<<EOT

<h1>$titre</h1>
<fieldset id="course_performance">
    <legend>Course</legend>
    <label>Catégorie&nbsp;:&nbsp;</label>$categorie<br>
    <label>Série&nbsp;:&nbsp;</label>$serie<br>
    <label>Événement&nbsp;:&nbsp;</label>$evenement<br>
    <label>Course&nbsp;:&nbsp;</label>$course<br>
    <label>Circuit&nbsp;:&nbsp;</label>$circuit
</fieldset>
<form id="edition_performance" name="edition_performance" method="post" action="$urlAction">
    <input type="hidden" name="up_id" value="$id">
    <input type="hidden" name="cca_id" value="$ccaid">
    <input type="hidden" name="cca_nom" value="$categorie">
    <input type="hidden" name="cs_id" value="$csid">
    <input type="hidden" name="cs_nom" value="$serie">
    <input type="hidden" name="ce_id" value="$ceid">
    <input type="hidden" name="ce_nom" value="$evenement">
    <input type="hidden" name="ct_nom" value="$course">
    <input type="hidden" name="ci_nom" value="$circuit">
    <input type="hidden" name="up_fk_c_id" value="$idCourse">
    <fieldset>
        <legend>Performance</legend>
        <label for="voiture">Voiture&nbsp;:&nbsp;</label>
        <select id="voiture" name="up_fk_uv_id">
$optionsVoiture
        </select>
        <br>
        <label for="classement">Classement&nbsp;:&nbsp;</label>
        <input id="classement" type="number" name="up_classement" value="$classement" required>
        <div class="erreur">$erreurClassement</div>
        <label for="reputation">Réputation&nbsp;:&nbsp;</label>
        <input id="reputation" type="text" name="up_reputation" value="$reputation" required>
        <div class="erreur">$erreurReputation</div>
        <label for="recompense">Récompense&nbsp;:&nbsp;</label>
        <input id="recompense" type="text" name="up_recompense" value="$recompense" required>&nbsp;$monnaie
        <div class="erreur">$erreurRecompense</div>
        <label for="temps">Temps&nbsp;:&nbsp;</label>
        <input id="temps" type="text" name="up_temps" value="$temps">&nbsp;mm:ss.sss
        <div class="erreur">$erreurTemps</div>
        <label for="vitesse">Vitesse Max.&nbsp;:&nbsp;</label>
        <input id="vitesse" type="text" name="up_vitesse" value="$vitesse">&nbsp;km/h
        <div class="erreur">$erreurVitesse</div>
        <label for="distance">Distance&nbsp;:&nbsp;</label>
        <input id="distance" type="text" name="up_distance" value="$distance">&nbsp;$unite_distance
        <div class="erreur">$erreurDistance</div>
        <label for="nb_depassements">Nombre de dépassements&nbsp;:&nbsp;</label>
        <input id="nb_depassements" type="number" name="up_nb_depassements" value="$nbDepassements">
        <div class="erreur">$erreurNbDepassements</div>
    </fieldset>
    <button id="submit" type="submit">Valider</button>
    <button id="reset" type="reset">Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'">Annuler</button>
</form>

EOT;

require "../config/gabarit.php";
