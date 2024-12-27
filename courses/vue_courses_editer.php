<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnuler = "controleur.php?action=lister_courses";

// Liste pour la sélection de la categorie
$idCategorie = isset($donnees["cca_id"]) ? $donnees["cca_id"] : $idCategorie;
$faireChoix = true;
$optionsCategorie = '';
if ($categories) {
    while ($categorie = $categories->fetch_object()) {
        $selection = $categorie->cca_id == $idCategorie ? " selected" : '';
        $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
        $faireChoix &= $categorie->cca_id != $idCategorie;
        $optionsCategorie .= "\t\t<option value=\"$categorie->cca_id\"$selection>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
    }
}
$optionsCategorie = ($faireChoix ? "\t\t<option hidden value='' selected>Choisissez une catégorie</option>\n" : '').$optionsCategorie;

// Liste pour la sélection de la série
$idSerie = isset($donnees["cs_id"]) ? $donnees["cs_id"] : $idSerie;
$faireChoix = true;
$optionsSerie = '';
if ($series) {
    while ($serie = $series->fetch_object()) {
        $selection = $serie->cs_id == $idSerie ? " selected" : '';
        $faireChoix &= $serie->cs_id != $idSerie;
        $optionsSerie .= "\t\t<option value=\"$serie->cs_id\"$selection>$serie->cs_rang_principal.$serie->cs_rang_secondaire- $serie->cs_nom</option>\n";
    }
}
$optionsSerie = ($faireChoix ? "\t\t<option hidden value='' selected>Choisissez une série</option>\n" : '').$optionsSerie;

// Liste pour la sélection de l'événement
$idEvenement = isset($donnees["c_fk_ce_id"]) ? $donnees["c_fk_ce_id"] : $idEvenement;
$faireChoix = true;
$optionsEvenement = '';
if ($evenements) {
    while ($evenement = $evenements->fetch_object()) {
        $selection = $evenement->ce_id == $idEvenement ? " selected" : '';
        $optionsEvenement .= "\t\t\t<option value=\"$evenement->ce_id\"$selection>$evenement->ce_rang- $evenement->ce_nom</option>\n";
    }
}
$optionsEvenement = ($faireChoix ? "\t\t\t<option hidden value='' selected>Choisissez un événement</option>\n" : '').$optionsEvenement;

// Liste pour la sélection du type de course
$idType = isset($donnees["c_fk_ct_id"]) ? $donnees["c_fk_ct_id"] : '';
$optionsType = ($idType == '') ? "\t\t\t<option hidden value='' selected>Choisissez un type</option>\n" : '';
while ($type = $types->fetch_object()) {
    $selection = $type->ct_id == $idType ? " selected" : '';
    $optionsType .= "\t\t\t<option value=\"$type->ct_id\"$selection>$type->ct_nom</option>\n";
}

// Liste pour la sélection du circuit
$idCircuit = isset($donnees["c_fk_cic_id"]) ? $donnees["c_fk_cic_id"] : '';
$optionsCircuit = ($idCircuit == '') ? "\t\t\t<option hidden value='' selected>Choisissez un circuit</option>\n": '';
while ($circuit = $circuits->fetch_object()) {
    $selection = $circuit->cic_id == $idCircuit ? " selected" : '';
    $nomCircuit = $circuit->ci_nom;
    $nomCircuit .= isset($circuit->cic_nom) ? " - ".$circuit->cic_nom : '';
    $optionsCircuit .= "\t\t\t<option value=\"$circuit->cic_id\"$selection>$nomCircuit</option>\n";
}

// Liste pour la sélection de la condition de course
$idCondition = isset($donnees["c_fk_cco_id"]) ? $donnees["c_fk_cco_id"] : '';
$optionsCondition = ($idCondition == '') ? "\t\t\t<option hidden value='' selected>Choisissez une condition</option>\n": '';
while ($condition = $conditions->fetch_object()) {
    $selection = $condition->cco_id == $idCondition ? " selected" : '';
    $optionsCondition .= "\t\t\t<option value=\"$condition->cco_id\"$selection>$condition->cco_nom</option>\n";
}

// Liste pour la sélection de la monnaie des récompenses
$idMonnaie = isset($donnees["c_fk_m_id"]) ? $donnees["c_fk_m_id"] : '';
$optionsMonnaie = '';
while ($monnaie = $monnaies->fetch_object()) {
    $selection = $monnaie->m_id == $idMonnaie ? " selected" : '';
    $optionsMonnaie .= "\t\t\t<option value=\"$monnaie->m_id\"$selection>$monnaie->m_nom</option>\n";
}

// Récupération des données
$id = isset($donnees["c_id"]) ? $donnees["c_id"] : '';
$rang = isset($donnees["c_rang"]) ? $donnees["c_rang"] : '';
$IPMin = isset($donnees["c_ip_min"]) ? $donnees["c_ip_min"] : '';
$nbTours = isset($donnees["c_nb_tours"]) ? $donnees["c_nb_tours"] : '';
$nbConcurrents = isset($donnees["c_nb_concurrents"]) ? $donnees["c_nb_concurrents"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["c_id"] = $id;
$_SESSION["ce_id"] = $idEvenement;
$_SESSION["cs_id"] = $idSerie;
$_SESSION["cca_id"] = $idCategorie;

// Récupération des éventuelles erreurs de saisie
$erreurRang = isset($erreurs["rang"]) ? $erreurs["rang"] : '';
$erreurIPMin = isset($erreurs["ip_min"]) ? $erreurs["ip_min"] : '';
$erreurNbTours = isset($erreurs["nb_tours"]) ? $erreurs["nb_tours"] : '';
$erreurNbConcurrents = isset($erreurs["nb_concurrents"]) ? $erreurs["nb_concurrents"] : '';

// Scripts utilisés pour le filtrage par catégorie et par série
$scripts = <<<EOT

function filtrerCategorie() {
   i = document.getElementById("categorie").selectedIndex;
   idCategorie = document.getElementById("categorie").options[i].value;
   parent.location.href = "controleur.php?action=ajouter_course&cca_id=" + idCategorie + "&cs_id=&ce_id=";
}

function filtrerSerie() {
   i = document.getElementById("serie").selectedIndex;
   idSerie = document.getElementById("serie").options[i].value;
   parent.location.href = "controleur.php?action=ajouter_course&cs_id=" + idSerie + "&ce_id=";
}

EOT;

// Construction du formulaire
$contenu .= <<<EOT

<h1>$titre</h1>
<div id="selection">
    <label for="categorie">Catégorie de courses&nbsp;:&nbsp;</label>
    <select id="categorie" name="cca_id" onChange="filtrerCategorie()">
$optionsCategorie
    </select>

EOT;

if ($idCategorie != '') {
    $contenu .= <<<EOT
    
    <br>
    <label for="serie">Série&nbsp;:&nbsp;</label>
    <select id="serie" name="cs_id" onChange="filtrerSerie()">
$optionsSerie
    </select>
    
EOT;
    if ($idSerie != '') {
        $contenu .= <<<EOT
    <br>
</div>
<form id="edition_course" name="edition_course" method="post" action="$urlAction">
    <fieldset>
        <input type="hidden" name="c_id" value="$id" />
        <label for="evenement">Événement&nbsp;:&nbsp;</label>
        <select id="evenement" name="c_fk_ce_id" required>
$optionsEvenement
        </select>
        <br />
        <label for="rang">Rang de la course&nbsp;:&nbsp;</label>
        <input id="rang" type="number" name="c_rang" value="$rang" required />
        <div class="erreur">$erreurRang</div>
        <label for="type">Type de course&nbsp;:&nbsp;</label>
        <select id="type" name="c_fk_ct_id" required>
$optionsType
        </select>
        <br />
        <label for="circuit">Circuit&nbsp;:&nbsp;</label>
        <select id="circuit" name="c_fk_cic_id" required>
$optionsCircuit
        </select>
        <br />
        <label for="condition">Condition de course&nbsp;:&nbsp;</label>
        <select id="condition" name="c_fk_cco_id" required>
$optionsCondition
        </select>
        <br />
        <label for="ip_min">IP Minimum&nbsp;:&nbsp;</label>
        <input id="ip_min" type="text" name="c_ip_min" value="$IPMin" />
        <div class="erreur">$erreurIPMin</div>
        <label for="nb_tours">Nombre de tours&nbsp;:&nbsp;</label>
        <input id="nb_tours" type="number" name="c_nb_tours" value="$nbTours" />
        <div class="erreur">$erreurNbTours</div>
        <label for="nb_concurrents">Nombre de concurrents&nbsp;:&nbsp;</label>
        <input id="nb_concurrents" type="number" name="c_nb_concurrents" value="$nbConcurrents" />
        <div class="erreur">$erreurNbConcurrents</div>
        <label for="monnaie">Monnaie des récompenses&nbsp;:&nbsp;</label>
        <select id="monnaie" name="c_fk_m_id" required>
$optionsMonnaie
        </select>
    </fieldset>
    <button id="submit" type="submit" />Valider</button>
    <button id="reset" type="reset" />Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'" />Annuler</button>
</form>

EOT;
    }
    else {
    $contenu .= <<<EOT
    
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'" />Annuler</button>
</div>

EOT;
    }
}
else {
    $contenu .= <<<EOT
    
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'" />Annuler</button>
</div>

EOT;
}

require "../config/gabarit.php";

?>