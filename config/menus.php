<?php

function genererMenuPrincipal(&$itemsMenu, $itemActif) {
    $itemsMenu[] = ["courses", "Courses", "Gestion des courses"];
    $itemsMenu[] = ["evenements", "Événement", "Gestion des événements"];
    $itemsMenu[] = ["series", "Séries", "Gestion des series de courses"];
    $itemsMenu[] = ["categories", "Catégories", "Gestion des catégories de courses"];
    $itemsMenu[] = ["voitures", "Voitures", "Gestion des voitures"];
    $itemsMenu[] = ["constructeurs", "Constructeurs", "Gestion des constructeurs"];
    $itemsMenu[] = ["circuits", "Circuits", "Gestion des circuits"];

    $menu = '';
    foreach ($itemsMenu as $item) {
        $menu .= "\t\t\t<a href=\"../".$item[0]."/controleur.php\" ";
        if ($item[0] == $itemActif) {
            $menu .= "class=\"active\" ";
        }
        $menu .= " title=\"".$item[2]."\">".$item[1]."</a></li>\n";
    }

    return $menu;

}

function genererMenuActions(&$itemsMenu, $controleur) {

    global $action;

    $menu = "<ul>\n";
    foreach ($itemsMenu as $item) {
        if ($item[0] == $action) {
            $menu .= "<li><span class=\"active\">".$item[1]."</span></li>\n";
        }
        else {
            $menu .= "<li><a href=\"../".$controleur."/controleur.php?action=".$item[0]."\" title=\"".$item[2]."\">".$item[1]."</a></li>\n";

        }

    }
    $menu .= "\t<li></li>";
    $menu .= "</ul>\n";

    return $menu;

}
