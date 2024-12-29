<?php

require_once '../config/gabarit_top.php';

?>

<main>
    <div class="container bg-body-tertiary py-3 mt-0">

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

                <!-- Corps -->
                <div id="contenu">
                    <?php echo $contenu; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php

require_once '../config/gabarit_bottom.php';
