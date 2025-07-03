<?php include_once "../../templates/header.php" ?>

<body id="body-confirmation">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>
    <section class="text-center d-flex flex-column align-items-center justify-content-center vh-100 text-white" style="background-color: #555;">
        <h1 class="pt-3">
            Merci de votre inscription ! Vous pouvez dorénavant vous connecter.
            <i class="fa-solid fa-face-smile-wink"></i>
        </h1>
        <p>Vous allez être redirigé vers la page de connexion dans <span id="compteur">10</span> secondes.</p>
        <p>Si vous ne souhaitez pas attendre, cliquez <a href="../Controller/controller-connexion.php">ici</a>.</p>
    </section>

    <script>
        let count = 10;

        function updateCompteur() {
            document.getElementById('compteur').textContent = count;
            count--;
            if (count >= 0) {
                setTimeout(updateCompteur, 1000);
            }
        }

        updateCompteur();
    </script>

    <?php include_once "../../templates/footer.php" ?>
</body>

</html>