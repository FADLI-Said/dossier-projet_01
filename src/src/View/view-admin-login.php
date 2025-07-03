<?php require_once "../../templates/header.php" ?>

<body id="body-admin-login" class="bg-dark">
    <?php
    include_once "../../templates/deco-nav.php";
    ?>

    <section id="admin-login" class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow">
            <h2 class="mb-4 text-center">Connexion administrateur</h2>
            <?php // Affichage erreur globale connexion 
            ?>
            <?php if (!empty($error['connexion'])) { ?>
                <div class="error alert alert-danger text-center"> <?= $error['connexion'] ?> </div>
            <?php } ?>
            <form method="post" action="" novalidate>
                <div class="mb-3">
                    <label for="admin_mail" class="form-label">Identifiant</label>
                    <input type="text" name="admin_mail" id="admin_mail" class="form-control" required value="<?= isset($_POST['admin_mail']) ? htmlspecialchars($_POST['admin_mail']) : '' ?>">
                    <?php if (!empty($error['admin_mail'])) { ?>
                        <div class="text-danger small mt-1"> <?= $error['admin_mail'] ?> </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label for="admin_mdp" class="form-label">Mot de passe</label>
                    <input type="password" name="admin_mdp" id="admin_mdp" class="form-control" required>
                    <?php if (!empty($error['admin_mdp'])) { ?>
                        <div class="text-danger small mt-1"> <?= $error['admin_mdp'] ?> </div>
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-dark w-100">Se connecter</button>
            </form>
        </div>
    </section>

    <?php require_once "../../templates/footer.php" ?>
</body>

</html>