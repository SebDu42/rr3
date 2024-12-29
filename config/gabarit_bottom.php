    <!-- Pied de page -->
    <div class="container bg-body-secondary">
        <footer class="align-items-center justify-content-center justify-content-md-between py-3 border-top">
            <p class="float-end mb-1">
                <a href="#">Retour en haut</a>
            </p>
            <p class="mb-1">Ce site utilise <a href="https://getbootstrap.com/" target="_blank">Bootstrap</a>.</p>
            <p class="mb-0">Real Racing 3 Wiki : <a href="https://rr3.fandom.com/" title="Real RAcing 3 Wiki"
                    target="_blank">https://rr3.fandom.com/</a> Site Officiel : </p>
        </footer>
    </div>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <?php if (isset($scripts)) { ?>
    <script language="javascript">
    <?php echo $scripts; ?>
    </script>
    <?php } ?>

    <script src="../config/fonctions.js"></script>
</body>

</html>
