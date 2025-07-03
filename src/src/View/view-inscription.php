<?php require_once "../../templates/header.php" ?>

<body id="body-inscription">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="inscription">
        <a href="../Controller/controller-connexion.php" class="text-start retour"><i
                class="fas fa-arrow-left"></i>
            Connexion</a>
        <form type="submit" class="container form-floating mt-5 p-4 rounded" method="POST" novalidate>
            <h1>Inscription</h1>
            <p>Inscrivez-vous pour réserver vos soins de beauté.</p>
            <div class="double">
                <div class="form-floating">
                    <input type="text" class="form-control bg-dark" id="nom" name="nom" placeholder=""
                        value="<?= $_POST['nom'] ?? '' ?>" required>
                    <label class="text-white" for="nom"><i class="fas fa-user"></i> Nom</label>
                    <p class="text-danger m-0 p-2">
                        <?= $error["nom"] ?? "" ?>
                    </p>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control bg-dark" id="prenom" name="prenom" placeholder=""
                        value="<?= $_POST['prenom'] ?? '' ?>" required>
                    <label class="text-white" for="prenom"><i class="fas fa-user"></i> Prénom</label>
                    <p class="text-danger m-0 p-2">
                        <?= $error["prenom"] ?? "" ?>
                    </p>
                </div>
            </div>

            <div class="form-floating">
                <input type="tel" class="form-control bg-dark" id="telephone" name="telephone" placeholder=""
                    value="<?= $_POST['telephone'] ?? '' ?>" required>
                <label class="text-white" for="telephone"><i class="fas fa-phone"></i> Téléphone</label>
                <p class="text-danger m-0 p-2">
                    <?= $error["telephone"] ?? "" ?>
                </p>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control bg-dark" id="email" name="email" placeholder=""
                    value="<?= $_POST['email'] ?? '' ?>" required>
                <label class="text-white" for="email"><i class="fas fa-envelope"></i> Adresse Mail</label>
                <p class="text-danger m-0 p-2">
                    <?= $error["email"] ?? "" ?>
                </p>
            </div>

            <div class="double">
                <div class="form-floating">
                    <input type="password" class="form-control bg-dark" id="mot_de_passe" name="mot_de_passe"
                        placeholder="" value="<?= $_POST['mot_de_passe'] ?? '' ?>" required>
                    <label class="text-white" for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                    <p class="text-danger m-0 p-2">
                        <?= $error["mot_de_passe"] ?? "" ?>
                    </p>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control bg-dark" id="confirmation_mot_de_passe"
                        name="confirmation_mot_de_passe" placeholder=""
                        value="<?= $_POST['confirmation_mot_de_passe'] ?? '' ?>" required>
                    <label class="text-white" for="confirmation_mot_de_passe"><i class="fas fa-lock"></i> Confirmation mot de
                        passe</label>
                    <p class="text-danger m-0 p-2">
                        <?= $error["confirmation_mot_de_passe"] ?? "" ?>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-light text-end">Inscription</button>
        </form>
    </section>

    <?php include_once "../../templates/footer.php" ?>
</body>

</html>