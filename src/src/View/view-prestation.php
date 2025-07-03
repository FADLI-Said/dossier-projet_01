<?php require_once "../../templates/header.php" ?>

<body>
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>


    <section id="prestation">
        <h1 class="text-center">Choix de la préstation</h1>
        <div class="border border-black rounded shadow p-3 mb-5 rounded" id="prestations">
            <?php
            $lastElement = end($prestations);
            foreach ($prestations as $value) {
                $duree = "Non défini";
                if ($value["prestation_duree"] != null) {
                    $date = new DateTimeImmutable($value["prestation_duree"]);
                    if ($date->format('H') == 0 && $date->format('i') != 0) {
                        $duree = $date->format('i') . " min";
                    } elseif ($date->format('H') >= 1 && $date->format('i') == 0) {
                        $duree = $date->format('H') . " h";
                    } elseif ($date->format('H:i:s') == "00:00:00") {
                        $duree = "Non défini";
                    } else {
                        $duree = $date->format('H') . " h " . $date->format('i') . " min";
                    }
                }
            ?>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2 w-50">
                        <img src="../../assets/images/<?= $value["prestation_image"] ?>" class="rounded img-fluid w-25" alt="">
                        <p class="m-0 h4"><?= $value["prestation_nom"] ?> <br> <span class="fs-6 fw-normal"><?= $value["prestation_description"] ?></span></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2 w-50">
                        <p class="m-0"><?= $duree ?> -</p>
                        <p class="m-0">à partir de <?= numfmt_format_currency($fmt, $value["prestation_prix"], "EUR") ?></p>
                        <a href="../Controller/controller-reservation.php?prestation=<?= $value["prestation_id"] ?>" class="rounded btn btn-outline-light">Choisir</a>
                    </div>
                </div>
                <?php if ($value !== $lastElement) { ?>
                    <hr>
                <?php } ?>
            <?php
            }
            ?>
        </div>
    </section>


    <?php include_once "../../templates/footer.php" ?>
</body>

</html>