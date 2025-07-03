<?php require_once "../../templates/header.php" ?>

<body id="body-connexion">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="connexion">
        <a href="../Controller/controller-accueil.php" class="text-start retour"><i class="fas fa-arrow-left"></i>
            Accueil</a>
        <div class="d-flex justify-content-center align-items-center">
            <div class="w-25">
                <img src="../../assets/images/Logo_AnnBeautyVisage.png" alt="Logo AnnBeautyVisage" class="img-fluid">
            </div>
            <form type="submit" class="form-floating mt-5 w-50" method="POST" novalidate>
                <h1>Connexion</h1>
                <div class="form-floating">
                    <input type="email" class="form-control bg-dark" name="id" id="id" placeholder=""
                        value="<?= $_POST['id'] ?? '' ?>" required>
                    <label for="id" class="text-white"><i class="fas fa-envelope"></i> Adresse Mail</label>
                    <p class="text-danger p-2 m-0">
                        <?= $error["id"] ?? "" ?>
                    </p>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control bg-dark" name="password" id="password" placeholder=""
                        value="<?= $_POST['password'] ?? '' ?>" required>
                    <label for="password" class="text-white"><i class="fas fa-lock"></i> Mot de passe</label>
                    <p class="text-danger p-2 m-0">
                        <?= $error["password"] ?? "" ?>
                    </p>
                </div>
                <p class="text-danger p-2 m-0">
                    <?= $error["connexion"] ?? "" ?>
                </p>
                <div class="d-flex justify-content-between">
                    <a href="../Controller/controller-inscription.php">
                        <i class="fas fa-user-plus"></i> Pas encore de compte ? Inscrivez-vous !
                    </a>
                    <button type="" class="btn btn-outline-light">Connexion</button>
                </div>
            </form>
        </div>


    </section>

    <?php include_once "../../templates/footer.php" ?>
</body>

</html>