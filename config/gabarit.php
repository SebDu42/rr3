<!DOCTYPE html>
<html>
<head>
    <title>Real Racing 3 - <?php echo $titre; ?></title>
    <meta http-equiv="centent-type" content="txt/html; charset=utf-8" />
    <link rel="stylesheet" href="../config/gabarit.css" />

    <?php if (isset($scripts)) { ?>

    <!-- Scripts -->
    <script language="javascript">
        <?php echo $scripts; ?>
    </script>
    <?php } ?>

    <script src="../config/fonctions.js"></script>

</head>

<body>
    <!-- Entête -->
    <header>
        <h1 id="titre_page">
            Real Racing 3 - <?php echo $titre; ?>
        </h1>
        <div id="contributeur">
            <?php if (isset($_SESSION["utilisateur"])) { ?>
            Bienvenue, <?php echo $_SESSION["utilisateur"]->u_prenom." ".$_SESSION["utilisateur"]->u_nom; ?>
            <a href="../authentification/controleur.php?action=deconnecter">Déconnexion</a>
            <?php } ?>
        </div>
    </header>

    <div id="corps">
        <?php if (isset($menuPrincipal)) { ?>

        <script>
            function myFunction() {
                var x = document.getElementById("menu_principal");
                if (x.className === "") {
                    x.className = "responsive";
                } else {
                    x.className = "";
                }
            }
        </script>

        <!-- Menu principal -->
        <div class="" id="menu_principal">
<?php echo $menuPrincipal; ?>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
        </div>
        <?php } ?>

        <div id="centre">
            <?php if (isset($menuActions)) { ?>

            <!-- Menu horizontal -->
            <div id="menu_actions"><?php echo $menuActions; ?></div>
            <?php } ?>

            <?php if (isset($contenu)) { ?>

            <!-- Corps -->
            <div id="contenu"><?php echo $contenu; ?></div>
            <?php } ?>
        </div>
    </div>

    <!-- Pied de page -->
    <footer>
        <div>Icons made by <a href="http://www.freepik.com" title="Freepik" target="_blank">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon" target="_blank">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        <div>Real Racing 3 Wiki : <a href="https://rr3.fandom.com/" title="Real RAcing 3 Wiki" target="_blank">https://rr3.fandom.com/</a></div>
    </footer>
</body>
</html>
