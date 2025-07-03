<?php require_once "../../templates/header.php" ?>

<body id="body-accueil" class="bg-warning bg-opacity-25">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>
    <header class="mb-5">
        <div class="container h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <img src="../../assets/images/Logo_AnnBeautyVisage.png" alt="Logo AnnBeautyVisage" class="img-fluid w-25" id="logo">
                    <figure class="text-center">
                        <blockquote class="blockquote">
                            <p>"<?= $randomQuote ?>"</p>
                        </blockquote>
                        <figcaption class="blockquote-footer"><?= $randomAuthor ?></figcaption>
                    </figure>
                </div>
                <hr class="w-75">
                <div class="col-lg-8 align-self-baseline">
                    <h1>Sublimer votre beauté naturelle !</h1>
                    <p>Des soins personnalisés pour révéler l'éclat de votre peau.</p>
                    <a href="../Controller/controller-prestation.php" class="btn btn-outline-light">Prendre rendez-vous</a>
                </div>
            </div>
        </div>
    </header>



    <section>
        <div class="rounded" id="prestations" data-bs-theme="light">
            <h2 class="text-center" data-bs-theme="light">
                Prestations
            </h2>
            <div class="row row-cols-lg-3 g-4">
                <?php foreach ($prestations as $value) { ?>
                    <div class="col-lg-4 d-flex justify-content-center">
                        <a href="../Controller/controller-reservation.php?prestation=<?= $value["prestation_id"] ?>" class="text-decoration-none">
                            <div class="prestation rounded shadow">
                                <div class="border border-bottom-0 rounded-top">
                                    <img src="../../assets/images/<?= $value["prestation_image"] ?>" class="rounded-top img-fluid"
                                        alt="Image de la prestation : <?= $value["prestation_nom"] ?>">
                                </div>
                                <div
                                    class="px-4 py-2 border border-top-0 rounded-bottom d-flex justify-content-between flex-column gap-3">
                                    <h3 class="text-center"><?= $value["prestation_nom"] ?></h3>
                                    <a href="../Controller/controller-reservation.php?prestation=<?= $value["prestation_id"] ?>" class="btn btn-outline-dark choisir">
                                        <i class="fa-regular fa-calendar-check"></i> Prendre RDV <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div id="horraires" class="d-lg-flex d-none">
            <div class="border border-light rounded shadow p-3 rounded">
                <h3>Horaire</h3>
                <div class="d-flex justify-content-between">
                    <p>Lundi</p>
                    <p>09:00 - 18:00</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Mardi</p>
                    <p>09:00 - 18:00</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Mercredi</p>
                    <p>09:00 - 18:00</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Jeudi</p>
                    <p>09:00 - 18:00</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Vendredi</p>
                    <p>09:00 - 18:00</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Samedi</p>
                    <p>Fermé</p>
                </div>

                <hr class="mt-0">
                <div class="d-flex justify-content-between">
                    <p>Dimanche</p>
                    <p>Fermé</p>
                </div>
            </div>

            <div id="note">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                            aria-selected="true">Note globale</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane"
                            aria-selected="false">Avis</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active p-3" id="home-tab-pane" role="tabpanel"
                        aria-labelledby="home-tab" tabindex="0">
                        <div class="text-center align-middle" id="note-content">
                            <p class="h1"><?= $stars ?></p>
                            <p class="h1 mt-4"><?= round($averageRating, 2) ?>/5</p>
                            <p>Avis : <?= $totalRatings ?> personnes ont données leurs avis</p>
                            <a class="btn btn-outline-dark" href="../Controller/controller-profil.php">Laisse ton avis !!!</a>
                        </div>

                    </div>
                    <div class="tab-pane fade pt-4" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                        tabindex="0">
                        <div class="overflow-auto">
                            <?php
                            if (empty($ratings)) {
                                echo "<p class='text-center'>Aucun avis pour le moment.</p>";
                            } else {
                                $compteur = 0;
                                foreach ($ratings as $value) {
                                    $compteur++;
                                    switch ($value["rating_score"]) {
                                        case 1:
                                            $user_stars =
                                                "<i class='fa-solid fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>";
                                            break;
                                        case 2:
                                            $user_stars =
                                                "<i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>";
                                            break;
                                        case 3:
                                            $user_stars =
                                                "<i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>";
                                            break;
                                        case 4:
                                            $user_stars =
                                                "<i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-regular fa-star'></i>";
                                            break;
                                        case 5:
                                            $user_stars =
                                                "<i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>
                                                <i class='fa-solid fa-star'></i>";
                                            break;
                                        default:
                                            $user_stars =
                                                "<i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>
                                                <i class='fa-regular fa-star'></i>";
                                            break;
                                    }
                            ?>
                                    <p><?= $value["user_mail"] ?> <span><?= $user_stars ?></span></p>
                                    <p>"<?= $value["rating_description"] ?>"</p>
                            <?php

                                    if ($compteur < count($ratings)) {
                                        echo "<hr class='mt-0'>";
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once "../../templates/footer.php" ?>
</body>

</html>