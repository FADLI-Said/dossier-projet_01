<?php require_once "../../templates/header.php" ?>

<body id="body-prestation" data-bs-theme="dark">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="modifier-prestation">
        <h1 class="text-center">Modifier la prestation <?= $prestations["prestation_nom"] ?></h1>
        <form type="submit" class="container form-floating mt-5" method="POST" enctype="multipart/form-data" novalidate>
            <div>
                <label class="text-light p-3" for="prestation_image"><i class="fas fa-image"></i> Image de la prestation</label>
                <input type="file" class="form-control bg-dark" id="prestation_image" name="prestation_image"
                    placeholder="">
                <p class="text-danger m-0 p-2"><?= $error["prestation_image"] ?? "" ?></p>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="prestation_nom" name="prestation_nom"
                    placeholder="Nom de la prestation" value="<?= $prestations['prestation_nom'] ?>">
                <label class="text-light" for="prestation_nom"><i class="fas fa-tag"></i> Nom de la prestation</label>
                <p><?= $error["prestation_nom"] ?? "" ?></p>
            </div>

            <div class="form-floating">
                <input class="form-control" id="prestation_description" name="prestation_description"
                    placeholder="Description" value="<?= $prestations['prestation_description'] ?>"></input>
                <label class="text-light" for="prestation_description"><i class="fas fa-align-left"></i> Description</label>
                <p><?= $error["prestation_description"] ?? "" ?></p>
            </div>

            <div class="form-floating">
                <input type="number" class="form-control" id="prestation_prix" name="prestation_prix" placeholder="Prix"
                    value="<?= $prestations['prestation_prix'] ?>" step="0.01">
                <label class="text-light" for="prestation_prix"><i class="fas fa-euro-sign"></i> Prix</label>
                <p><?= $error["prestation_prix"] ?? "" ?></p>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="prestation_duree" name="prestation_duree"
                    placeholder="Durée (en minutes)"
                    value="<?= $prestations["prestation_duree"] != null ? $prestations['prestation_duree'] : "00:00:00" ?>">
                <label class="text-light" for="prestation_duree"><i class="fas fa-clock"></i> Durée (HH:MM:SS)</label>
                <p><?= $error["prestation_duree"] ?? "" ?></p>
            </div>

            <button type="submit" class="mt-3 btn btn-primary">Enregistrer les modifications</button>
        </form>
    </section>


    <?php include_once "../../templates/footer.php" ?>
</body>

</html>