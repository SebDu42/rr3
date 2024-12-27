<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnule = isset($donnees["uv_id"]) ? "controleur.php?action=mes_voitures" : "controleur.php?action=lister";

// Préfixe des tables suivant qu'il s'agit d'un modèle de voiture ou d'une voiture de l'utilisateur
$p = isset($donnees["uv_id"]) ? "uv_" : "v_";

// Idem pour le suffixe des améliorations
$s = isset($donnees["uv_id"]) ? "" : "_max";

// Utilisé pour rendre invisible ou inactif certains champs non pertinents pour une voiture de l'utilisateur
$inactif = isset($donnees["uv_id"]) ? "disabled" : '';
$invisible = isset($donnees["uv_id"]) ? "style=\"display:none\"" : '';

// Titre de la zone amélioration suivant qu'il s'agit d'un modèle ou d'une voiture de l'utilisateur
$titreAmeliorations = isset($donnees["uv_id"]) ? "Améliorations" : "Améliorations maximales";

// Liste pour la sélection du statut
if (isset($donnees["uv_id"])) {
    $idStatut = $donnees["uv_fk_uvs_id"];
    $optionsStatut = '';
    while ($statut = $statuts->fetch_object()) {
        $selection = $statut->uvs_id == $idStatut ? " selected" : '';
        $optionsStatut .= "\t\t\t<option value=\"$statut->uvs_id\"$selection>$statut->uvs_nom</option>\n";
    }
}

// Liste pour la sélection du constructeur
$idConstructeur = isset($donnees["v_fk_vco_id"]) ? $donnees["v_fk_vco_id"] : $idConstructeur;
$optionsConstructeur = ($idConstructeur == '') ? "\t\t\t<option hidden value='' selected>Choisissez un constructeur</option>\n" : '';
while ($constructeur = $constructeurs->fetch_object()) {
    $selection = $constructeur->vco_id == $idConstructeur ? " selected" : '';
    $optionsConstructeur .= "\t\t\t<option value=\"$constructeur->vco_id\"$selection>$constructeur->vco_nom</option>\n";
}

// Liste pour la sélection de la classe
$idClasse = isset($donnees["v_fk_vcl_id"]) ? $donnees["v_fk_vcl_id"] : '';
$optionsClasse = ($idClasse == '') ? "\t\t\t<option hidden value='' selected>Choisissez une classe</option>\n" : '';
while ($classe = $classes->fetch_object()) {
    $selection = $classe->vcl_id == $idClasse ? " selected" : '';
    $optionsClasse .= "\t\t\t<option value=\"$classe->vcl_id\"$selection>$classe->vcl_nom</option>\n";
}

// Liste pour la sélection du type de transmission
$idTransmission = isset($donnees["v_fk_vt_id"]) ? $donnees["v_fk_vt_id"] : '';
$optionsTransmission = ($idTransmission == '') ? "\t\t\t<option hidden value='' selected>Choisissez une transmission</option>\n" : '';
while ($transmission = $transmissions->fetch_object()) {
    $selection = $transmission->vt_id == $idTransmission ? " selected" : '';
    $optionsTransmission .= "\t\t\t<option value=\"$transmission->vt_id\"$selection>$transmission->vt_nom</option>\n";
}

// Liste pour la sélection de la monnaie du prix
$idMonnaie = isset($donnees["v_fk_m_id"]) ? $donnees["v_fk_m_id"] : '';
$optionsMonnaie = '';
while ($monnaie = $monnaies->fetch_object()) {
    $selection = $monnaie->m_id == $idMonnaie ? " selected" : '';
    $optionsMonnaie .= "\t\t\t<option value=\"$monnaie->m_id\"$selection>$monnaie->m_nom</option>\n";
}

// Récupération des données
$id = isset($donnees[$p."id"]) ? $donnees[$p."id"] : '';
$modele = isset($donnees["v_modele"]) ? $donnees["v_modele"] : '';
$prix = isset($donnees["v_prix"]) ? $donnees["v_prix"] : '';
$url = isset($donnees["v_url"]) ? $donnees["v_url"] : '';
$vitesse = isset($donnees[$p."vitesse"]) ? $donnees[$p."vitesse"]: '';
$acceleration = isset($donnees[$p."acceleration"]) ? $donnees[$p."acceleration"]: '';
$freinage = isset($donnees[$p."freinage"]) ? $donnees[$p."freinage"]: ''; 
$adherence = isset($donnees[$p."adherence"]) ? $donnees[$p."adherence"]: '';
$ip = isset($donnees[$p."ip"]) ? $donnees[$p."ip"]: '';
$coutRevision = isset($donnees["v_cout_revision"]) ? $donnees["v_cout_revision"]: '';
$dureeRevision = isset($donnees["v_duree_revision"]) ? $donnees["v_duree_revision"]: '';
$revisionInstantanee = isset($donnees["v_cout_revision_instantanee"]) ? $donnees["v_cout_revision_instantanee"]: '';
$aMoteur = isset($donnees[$p."am_moteur".$s]) ? $donnees[$p."am_moteur".$s]: '';
$aTransmission = isset($donnees[$p."am_transmission".$s]) ? $donnees[$p."am_transmission".$s]: '';
$aCarrosserie = isset($donnees[$p."am_carrosserie".$s]) ? $donnees[$p."am_carrosserie".$s]: '';
$aSuspension = isset($donnees[$p."am_suspension".$s]) ? $donnees[$p."am_suspension".$s]: '';
$aPot = isset($donnees[$p."am_pot".$s]) ? $donnees[$p."am_pot".$s]: '';
$aFreins = isset($donnees[$p."am_freins".$s]) ? $donnees[$p."am_freins".$s]: '';
$aRoues = isset($donnees[$p."am_roues".$s]) ? $donnees[$p."am_roues".$s]: '';

// Mémorisation des enregistrements actifs
$_SESSION[$p."id"] = $id;
$_SESSION["vco_id"] = $idConstructeur;
$_SESSION["vcl_id"] = $idClasse;
$_SESSION["vt_id"] = $idTransmission;

// Récupération des éventuelles erreurs de saisie
$erreurPrix = isset($erreurs["prix"]) ? $erreurs["prix"] : '';
$erreurVitesse = isset($erreurs["vitesse"]) ? $erreurs["vitesse"] : '';
$erreurAcceleration = isset($erreurs["acceleration"]) ? $erreurs["acceleration"] : '';
$erreurFreinage = isset($erreurs["freinage"]) ? $erreurs["freinage"] : '';
$erreurAdherence = isset($erreurs["adherence"]) ? $erreurs["adherence"] : '';
$erreurIp = isset($erreurs["ip"]) ? $erreurs["ip"] : '';
$erreurCoutRevision = isset($erreurs["cout_revision"]) ? $erreurs["cout_revision"] : '';
$erreurDureeRevision = isset($erreurs["duree_revision"]) ? $erreurs["duree_revision"] : '';
$erreurRevisionInstantanee = isset($erreurs["cout_revision_instantanee"]) ? $erreurs["cout_revision_instantanee"] : '';
$erreurAMoteur = isset($erreurs["am_moteur_max"]) ? $erreurs["am_moteur_max"] : '';
$erreurATransmission = isset($erreurs["am_transmission_max"]) ? $erreurs["am_transmission_max"] : '';
$erreurACarrosserie = isset($erreurs["am_carrosserie_max"]) ? $erreurs["am_carrosserie_max"] : '';
$erreurASuspension = isset($erreurs["am_suspension_max"]) ? $erreurs["am_suspension_max"] : '';
$erreurAPot = isset($erreurs["am_pot_max"]) ? $erreurs["am_pot_max"] : '';
$erreurAFreins = isset($erreurs["am_freins_max"]) ? $erreurs["am_freins_max"] : '';
$erreurARoues = isset($erreurs["am_roues_max"]) ? $erreurs["am_roues_max"] : '';

// Construction du formulaire
$contenu .= <<<EOT
<h1>$titre</h1>
<form id="edition_voiture" name="edition_voiture" method="post" action="$urlAction">
    <input type="hidden" name="{$p}id" value="$id">
EOT;

if (isset($donnees["uv_id"])) {
    $contenu .= <<<EOT
    <fieldset>
        <legend>Statut</legend>
        <label for="statut">Statut&nbsp;:&nbsp;</label>
        <select id="statut" name="uv_fk_uvs_id" required>
$optionsStatut
        </select>
    </fieldset>
EOT;
}

$contenu .= <<<EOT
    <fieldset $inactif>
        <legend>Modèle</legend>
        <label for="constructeur">Constructeur&nbsp;:&nbsp;</label>
        <select id="constructeur" name="v_fk_vco_id" required>
$optionsConstructeur
        </select>
        <br>
        <label for="modele">Modèle&nbsp;:&nbsp;</label>
        <input id="modele" type="text" name="v_modele" value="$modele" required>
        <br>
        <label for="classe">Classe de véhicule&nbsp;:&nbsp;</label>
        <select id="classe" name="v_fk_vcl_id" required>
$optionsClasse
        </select>
        <br>
        <label for="transmission">Type de transmission&nbsp;:&nbsp;</label>
        <select id="transmission" name="v_fk_vt_id" required>
$optionsTransmission
        </select>
        <br>
        <label for="prix">Prix&nbsp;:&nbsp;</label>
        <input id="prix" type="text" name="v_prix" value="$prix" required>
        <div class="erreur">$erreurPrix</div>
        <label for="monnaie">Monnaie&nbsp;:&nbsp;</label>
        <select id="monnaie" name="v_fk_m_id" required>
$optionsMonnaie
        </select>
        <br>
        <label for="url">URL&nbsp;:&nbsp;</label>
        <input id="url" type="text" name="v_url" value="$url">
    </fieldset>
    <fieldset>
        <legend>Performances</legend>
        <label for="vitesse">Vitesse maximale&nbsp;:&nbsp;</label>
        <input id="vitesse" type="text" name="{$p}vitesse" value="$vitesse" required> km/h
        <div class="erreur">$erreurVitesse</div>
        <label for="acceleration">Accélération&nbsp;:&nbsp;</label>
        <input id="acceleration" type="text" name="{$p}acceleration" value="$acceleration" required> s
        <div class="erreur">$erreurAcceleration</div>
        <label for="freinage">Freinage&nbsp;:&nbsp;</label>
        <input id="freinage" type="text" name="{$p}freinage" value="$freinage" required> m
        <div class="erreur">$erreurFreinage</div>
        <label for="adherence">Adhérence&nbsp;:&nbsp;</label>
        <input id="adherence" type="text" name="{$p}adherence" value="$adherence" required> g
        <div class="erreur">$erreurAdherence</div>
        <label for="ip">Indice de Performance&nbsp;:&nbsp;</label>
        <input id="ip" type="text" name="{$p}ip" value="$ip" required>
        <div class="erreur">$erreurIp</div>
    </fieldset>
    <fieldset $inactif $invisible>
        <legend>Révision</legend>
        <label for="cout_revision">Coût de la révision&nbsp;:&nbsp;</label>
        <input id="cout_revision" type="text" name="v_cout_revision" value="$coutRevision" required> R$
        <div class="erreur">$erreurCoutRevision</div>
        <label for="duree_revision">Durée de la révision&nbsp;:&nbsp;</label>
        <input id="duree_revision" type="text" name="v_duree_revision" value="$dureeRevision" required> hh:mm
        <div class="erreur">$erreurDureeRevision</div>
        <label for="cout_revision_instantanee">Coût de la révision instantanée&nbsp;:&nbsp;</label>
        <input id="cout_revision_instantanee" type="number" name="v_cout_revision_instantanee" value="$revisionInstantanee"> Or
        <div class="erreur">$erreurRevisionInstantanee</div>
    </fieldset>
    <fieldset>
        <legend>$titreAmeliorations</legend>
        <label for="am_moteur">Moteur&nbsp;:&nbsp;</label>
        <input id="am_moteur" type="number" name="{$p}am_moteur{$s}" value="$aMoteur">
        <div class="erreur">$erreurAMoteur</div>
        <label for="am_transmission">Transmission&nbsp;:&nbsp;</label>
        <input id="am_transmission" type="number" name="{$p}am_transmission{$s}" value="$aTransmission">
        <div class="erreur">$erreurATransmission</div>
        <label for="am_carrosserie">Carrosserie&nbsp;:&nbsp;</label>
        <input id="am_carrosserie" type="number" name="{$p}am_carrosserie{$s}" value="$aCarrosserie">
        <div class="erreur">$erreurACarrosserie</div>
        <label for="am_suspension">Suspension&nbsp;:&nbsp;</label>
        <input id="am_suspension" type="number" name="{$p}am_suspension{$s}" value="$aSuspension">
        <div class="erreur">$erreurASuspension</div>
        <label for="am_pot">Pot d'échappement&nbsp;:&nbsp;</label>
        <input id="am_pot" type="number" name="{$p}am_pot{$s}" value="$aPot">
        <div class="erreur">$erreurAPot</div>
        <label for="am_freins">Freins&nbsp;:&nbsp;</label>
        <input id="am_freins" type="number" name="{$p}am_freins{$s}" value="$aFreins">
        <div class="erreur">$erreurAFreins</div>
        <label for="am_roues">Pneus et roues&nbsp;:&nbsp;</label>
        <input id="am_roues" type="number" name="{$p}am_roues{$s}" value="$aRoues">
        <div class="erreur">$erreurARoues</div>
    </fieldset>
    <button id="submit" type="submit">Valider</button>
    <button id="reset" type="reset">Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnule'">Annuler</button>
</form>

EOT;

require "../config/gabarit.php";

?>