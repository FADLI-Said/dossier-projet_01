<?php include_once "../../templates/header.php" ?>

<body>
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="profil" data-bs-theme="dark">
        <a href="../Controller/controller-accueil.php" class="text-start retour"><i class="fas fa-arrow-left"></i>
            Accueil</a>
        <h1 class="py-3 text-center">Bonjour <?= $_SESSION["user_prenom"] ?></h1>
        <h2 class="pb-3 text-center">Voici votre profil</h2>


        <div class="profil-container text-center">
            <div class="profil-card border border-black rounded shadow rounded w-75 mx-auto py-3">
                <h3 class="pb-3">Informations personnelles</h3>
                <p><span class="fw-bold">Nom :</span> <?= $_SESSION["user_nom"] ?></p>
                <p><span class="fw-bold">Prénom :</span> <?= $_SESSION["user_prenom"] ?></p>
                <p><span class="fw-bold">Email :</span> <?= $_SESSION["user_mail"] ?></p>
                <p><span class="fw-bold">Numéro de téléphone :</span>
                    <?= implode('.', str_split($_SESSION["user_telephone"], 2)) ?>
                </p>

                <div>
                    <p class="h1">Tous vos RDV chez nous !</p>
                    <?php if (empty($userRdv)) { ?>
                        <p>Aucun RDV pour le moment</p>
                    <?php } else { ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 p-3">
                            <?php foreach ($userRdv as $rdv) {
                                // Format date
                                $date = date('d/m/Y', strtotime($rdv['reservation_date']));
                                // Format heure Hhmm
                                $start = ltrim(date('G\hi', strtotime($rdv['reservation_start'])), '0');
                                $end = ltrim(date('G\hi', strtotime($rdv['reservation_end'])), '0');
                                // Format durée
                                $duree = ltrim(date('G\hi', strtotime($rdv['prestation_duree'])), '0');
                            ?>
                                <div class="col">
                                    <div class="card shadow border text-dark h-100" style="background-color: #FFEFC1;">
                                        <div class="card-body">
                                            <h5 class="card-title mb-2"><?= htmlspecialchars($rdv['prestation_nom']) ?></h5>
                                            <p class="card-text mb-1"><strong>Date :</strong> <?= $date ?></p>
                                            <p class="card-text mb-1"><strong>Heure :</strong> <?= $start ?> - <?= $end ?></p>
                                            <p class="card-text mb-1"><strong>Durée :</strong> <?= $duree ?></p>
                                            <p class="card-text mb-1"><strong>Prix :</strong> <?= number_format($rdv['prestation_prix'], 2, ',', ' ') ?> €</p>
                                            <?php if (!empty($rdv['prestation_image'])) { ?>
                                                <img src="../../assets/images/<?= htmlspecialchars($rdv['prestation_image']) ?>" alt="Image prestation" class="img-fluid rounded mt-2">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <div class="d-flex flex-column align-items-center justify-content-center text-center action mt-3">
                <div class="d-flex flex-column align-items-center justify-content-center text-center action">
                    <h3>Actions</h3>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#supprimer">Supprimer le profil</button>
                    <button class="btn btn-outline-light mt-2" data-bs-toggle="modal" data-bs-target="#deconnecter">Se déconnecter</button>
                </div>

                <div class="modal fade" id="deconnecter" tabindex="-1"
                    aria-labelledby="deconnecterLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-dark" style="background-color: #FFEFC1;">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deconnecterLabel">Confirmation de déconnexion
                                </h1>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir vous déconnecter ? Vous serez redirigé vers la page d'accueil.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a class="btn btn-outline-danger" href="../Controller/controller-deconnexion.php">Confirmer
                                    la déconnexion</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="supprimer" tabindex="-1" aria-labelledby="supprimerLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-dark" style="background-color: #FFEFC1;">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="supprimerLabel">Confirmation de suppression
                                </h1>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer votre profil ? Cette action est irréversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a class="btn btn-outline-danger" href="../Controller/controller-suppProfil.php?user=<?= $_SESSION["user_id"] ?>">Confirmer
                                    la suppression</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $hasErrors = !empty($errors);
        $hasNoRating = empty($userRatings) || (is_array($userRatings) && count($userRatings) === 0);
        if (!$hasNoRating) {

            switch ($userRatings[0]["rating_score"]) {
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
            <div class="d-flex flex-column align-items-center justify-content-center text-center avis-container" id="avis-container">
                <h2 class='text-center'>Votre avis</h2>
                <p><?= $userRatings[0]["user_mail"] ?> <span><?= $user_stars ?></span></p>
                <p>"<?= $userRatings[0]["rating_description"] ?>"</p>
            </div>
            <button class="btn btn-outline-light container<?= $hasErrors ? ' d-none' : '' ?>" id="modifier-avis">Modifier</button>

            <form method="post" class="container avis-container d-none justify-content-center" id="form-modif-avis" data-bs-theme="dark" novalidate>
                <div class="flex-column gap-4 d-flex" onclick="event.stopPropagation();">
                    <div class="form-group">
                        <label for="avis">Votre avis :</label>
                        <textarea class="form-control" id="avis-textarea" rows="3" name="description"><?= $userRatings[0]["rating_description"] ?></textarea>
                        <p class="text-danger p-2 m-0">
                            <?= $errors["description"] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="note">Votre note :</label>
                        <select class="form-control" id="note" name="note">
                            <option value="" disabled>Choisissez une note</option>
                            <option value="1" <?= $userRatings[0]["rating_score"] == 1.00 ? "selected" : "" ?>>★☆☆☆☆</option>
                            <option value="2" <?= $userRatings[0]["rating_score"] == 2.00 ? "selected" : "" ?>>★★☆☆☆</option>
                            <option value="3" <?= $userRatings[0]["rating_score"] == 3.00 ? "selected" : "" ?>>★★★☆☆</option>
                            <option value="4" <?= $userRatings[0]["rating_score"] == 4.00 ? "selected" : "" ?>>★★★★☆</option>
                            <option value="5" <?= $userRatings[0]["rating_score"] == 5.00 ? "selected" : "" ?>>★★★★★</option>
                        </select>
                        <p class="text-danger p-2 m-0">
                            <?= $errors["note"] ?? "" ?>
                        </p>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-light">Envoyer</button>
                    </div>
                </div>
            </form>
        <?php } else {
        ?>
            <button class="btn btn-outline-light container<?= $hasErrors ? ' d-none' : '' ?>" id="avis-btn">Mettre un avis</button>
            <form method="post" class="container avis-form-nouveau justify-content-center avis-container<?= $hasErrors ? ' d-flex' : ' d-none' ?>" data-bs-theme="dark" novalidate>
                <div class="flex-column gap-4 d-flex" onclick="event.stopPropagation();">
                    <div class="form-group">
                        <label for="avis">Votre avis :</label>
                        <textarea class="form-control" id="avis-textarea-nouveau" rows="3" name="description"></textarea>
                        <p class="text-danger p-2 m-0">
                            <?= $errors["description"] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="note">Votre note :</label>
                        <select class="form-control" id="note" name="note">
                            <option value="" disabled selected>Choisissez une note</option>
                            <option value="1">★☆☆☆☆</option>
                            <option value="2">★★☆☆☆</option>
                            <option value="3">★★★☆☆</option>
                            <option value="4">★★★★☆</option>
                            <option value="5">★★★★★</option>
                        </select>
                        <p class="text-danger p-2 m-0">
                            <?= $errors["note"] ?? "" ?>
                        </p>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-light">Envoyer</button>
                    </div>
                </div>
            </form>
        <?php } ?>

    </section>


    <script>
        // Empêche la fermeture du formulaire d'avis lors d'un clic dans le textarea
        // et corrige l'affichage/masquage des blocs
        const avisBtn = document.getElementById('avis-btn');
        if (avisBtn) {
            avisBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // On cible le bon formulaire d'avis (celui pour "mettre un avis", pas le modif)
                const avisFormNouveau = document.querySelector('.avis-form-nouveau');
                if (avisFormNouveau) {
                    avisFormNouveau.classList.remove('d-none');
                    // avisFormNouveau.classList.add('d-flex');
                }
                avisBtn.classList.add('d-none');
            });
        }
        // Pour "Modifier" l'avis existant
        const modifBtn = document.getElementById('modifier-avis');
        const formModif = document.getElementById('form-modif-avis');
        const blocAvis = document.getElementById('avis-container');
        if (modifBtn && formModif && blocAvis) {
            modifBtn.addEventListener('click', function(e) {
                e.preventDefault();
                formModif.classList.remove('d-none');
                // formModif.classList.add('d-flex');
                blocAvis.classList.add('d-none');
                modifBtn.classList.add('d-none');
            });
        }
    </script>
    <?php include_once "../../templates/footer.php" ?>
</body>

</html>