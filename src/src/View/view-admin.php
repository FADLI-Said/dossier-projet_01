<?php require_once "../../templates/header.php" ?>

<body id="body-admin" data-bs-theme="dark">
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="admin">
        <div id="calendrier-admin">
            <ul class="nav nav-pills mb-3 p-2 justify-content-center" id="admin-pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="admin-pills-home-tab" data-bs-toggle="pill" data-bs-target="#admin-mois0" type="button" role="tab" aria-controls="admin-pills-home" aria-selected="true"><?php echo $mois[$currentMonth]; ?></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="admin-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#admin-mois1" type="button" role="tab" aria-controls="admin-pills-profile" aria-selected="false"><?php echo $mois[$currentMonth + 1]; ?></button>
                </li>
            </ul>
            <div class="tab-content text-dark" id="admin-pills-tabContent" style="background-color: #FFEFC1;">
                <?php
                for ($offset = 0; $offset <= 1; $offset++) {
                    $Year = date("Y");
                    $Month = $currentMonth + $offset;
                    if ($Month > 12) {
                        $Month = $Month - 12;
                        $Year = $Year + 1;
                    }
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
                    $firstDay = mktime(0, 0, 0, $Month, 1, $Year);
                    $firstDayOfWeek = date("N", $firstDay);
                    $today = new DateTime('now', new DateTimeZone('Europe/Paris'));
                    $today->setTime(0, 0, 0);
                ?>
                    <div class="tab-pane fade <?= $offset == 0 ? ' show active' : '' ?>" id="admin-mois<?= $offset ?>" role="tabpanel" aria-labelledby="admin-pills-home-tab" tabindex="0">
                        <div class="calendar-container dark p-5 tab-content border border-black rounded shadow rounded" id="admin-calendrier">
                            <div id="mois">
                                <div class="calendar-header justify-content-center">
                                    <span class="calendar-month"><?= $mois[$Month] . " " . $Year; ?></span>
                                </div>
                                <div class="calendar-grid">
                                    <div class="calendar-day calendar-day-label">Lun</div>
                                    <div class="calendar-day calendar-day-label">Mar</div>
                                    <div class="calendar-day calendar-day-label">Mer</div>
                                    <div class="calendar-day calendar-day-label">Jeu</div>
                                    <div class="calendar-day calendar-day-label">Ven</div>
                                    <div class="calendar-day calendar-day-label">Sam</div>
                                    <div class="calendar-day calendar-day-label">Dim</div>
                                    <?php

                                    for ($i = 1; $i < $firstDayOfWeek; $i++) {
                                    ?><a class="calendar-day calendar-empty"></a>
                                        <?php
                                    }
                                    for ($dayNum = 1; $dayNum <= $daysInMonth; $dayNum++) {
                                        $currentDayDate = new DateTime("$Year-$Month-$dayNum", new DateTimeZone('Europe/Paris'));
                                        $dayOfWeek = (int)$currentDayDate->format("N");
                                        $isPastDay = ($currentDayDate < $today);
                                        $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);
                                        if ($isPastDay || $isWeekend) {
                                        ?><a class="week-end"><?php echo $dayNum; ?></a>
                                        <?php
                                        } else {
                                        ?>
                                            <a class="calendar-day admin-calendar-selectable-day" href="#" data-date="<?php echo $Year . '-' . sprintf('%02d', $Month) . '-' . sprintf('%02d', $dayNum); ?>"><?php echo $dayNum; ?></a>
                                        <?php
                                        }
                                    }
                                    $lastDayOfWeek = date("N", mktime(0, 0, 0, $Month, $daysInMonth, $Year));
                                    for ($i = $lastDayOfWeek; $i < 7; $i++) {
                                        ?><a class="calendar-day calendar-empty"></a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div id="admin-time-slots-mois<?= $offset ?>" class="admin-time-slots-container"></div>
                    </div>
                <?php } ?>
            </div>
            <div class="text-center my-3">
                <span id="admin-selected-date" class="h4"></span>
            </div>
            <div id="admin-horraires" class="horraires-list"></div>
        </div>
        <div class="border border-black rounded shadow p-3 mt-5 rounded mx-auto text-dark" id="prestations" style="background-color: #FFEFC1;">
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

                        <a href="../Controller/controller-modif-prestation.php?prestation=<?= $value["prestation_id"] ?>" class="rounded btn btn-outline-dark">Modifier</a>
                        <a href="#" class="rounded btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmModal<?= $value["prestation_id"] ?>">Supprimer</a>

                        <div data-bs-theme="light" class="modal fade" id="confirmModal<?= $value["prestation_id"] ?>" tabindex="-1"
                            aria-labelledby="confirmModalLabel<?= $value["prestation_id"] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background-color: #FFEFC1;">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel<?= $value["prestation_id"] ?>">
                                            Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette prestation ?
                                        <p class="text-danger">
                                            "<?= $value["prestation_nom"] ?>"
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Annuler</button>
                                        <a href="../Controller/controller-suppr-prestation.php?prestation=<?= $value["prestation_id"] ?>"
                                            class="btn btn-outline-danger">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($value !== $lastElement) { ?>
                    <hr>
                <?php } ?>
            <?php } ?>
        </div>
        <a class="rounded btn btn-outline-light w-75 mx-auto" href="../Controller/controller-add-prestation.php">Ajouter une prestation</a>

        <script>
            const allRdv = <?php echo json_encode($allRdv); ?>;
            console.log(allRdv);
            

            function formatHeure(hms) {
                const d = new Date('1970-01-01T' + hms);
                return d.getHours() + 'h' + (d.getMinutes() ? d.getMinutes().toString().padStart(2, '0') : '00');
            }

            function formatDuree(hms) {
                const d = new Date('1970-01-01T' + hms);
                let res = '';
                if (d.getHours()) res += d.getHours() + 'h';
                if (d.getMinutes()) res += (res ? '' : '') + d.getMinutes();
                if (d.getMinutes()) res += 'min';
                return res || 'Non défini';
            }
            document.addEventListener('DOMContentLoaded', function() {
                function formatDateFr(dateStr) {
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' });
                }
                function setSelectedDateLabel(dateStr) {
                    const label = document.getElementById('admin-selected-date');
                    if (label) label.textContent = formatDateFr(dateStr);
                }
                function attachAdminDayClickListeners() {
                    const selectableDays = document.querySelectorAll('.admin-calendar-selectable-day');
                    selectableDays.forEach(day => {
                        day.addEventListener('click', function(e) {
                            e.preventDefault();
                            const selectedDate = this.dataset.date;
                            document.querySelectorAll('.admin-calendar-selectable-day.active-selected').forEach(activeDay => {
                                activeDay.classList.remove('active-selected');
                            });
                            this.classList.add('active-selected');
                            setSelectedDateLabel(selectedDate);
                            showAdminRdvForDate(selectedDate);
                        });
                    });
                }

                function showAdminRdvForDate(date) {
                    const horairesDiv = document.getElementById('admin-horraires');
                    horairesDiv.innerHTML = '';
                    const rdvs = allRdv.filter(r => r.reservation_date === date);
                    if (rdvs.length === 0) {
                        horairesDiv.innerHTML = '<p class="text-center w-100">Aucun rendez-vous pour cette date.</p>';
                        return;
                    }
                    let html = '<div class="d-flex flex-wrap justify-content-center gap-4 mt-4">';
                    rdvs.forEach(rdv => {
                        html += `<div class="card shadow border border-dark" style="min-width:260px;max-width:340px;background-color: #FFEFC1;color:white;">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-dark">${rdv.prestation_nom}</h5>
                            <p class="card-text mb-1 text-dark"><strong>Client :</strong> ${rdv.user_nom} ${rdv.user_prenom}</p>
                            <p class="card-text mb-1 text-dark"><strong>Heure :</strong> ${formatHeure(rdv.reservation_start)} - ${formatHeure(rdv.reservation_end)}</p>
                            <p class="card-text mb-1 text-dark"><strong>Durée :</strong> ${formatDuree(rdv.prestation_duree)}</p>
                            <p class="card-text mb-1 text-dark"><strong>Prix :</strong> ${Number(rdv.prestation_prix).toLocaleString('fr-FR', {minimumFractionDigits:2})} €</p>
                            ${rdv.prestation_image ? `<img src="../../assets/images/${rdv.prestation_image}" alt="Image prestation" class="img-fluid rounded mt-2" style="object-fit:cover;">` : ''}
                        </div>
                    </div>`;
                    });
                    html += '</div>';
                    horairesDiv.innerHTML = html;
                }
                attachAdminDayClickListeners();
                // Affiche la date du jour sélectionnée par défaut
                const today = new Date();
                const todayYmd = today.toISOString().slice(0,10);
                const btnToday = document.querySelector('.admin-calendar-selectable-day[data-date="' + todayYmd + '"]');
                if (btnToday) {
                    btnToday.classList.add('active-selected');
                    setSelectedDateLabel(todayYmd);
                    showAdminRdvForDate(todayYmd);
                }
            });
        </script>
    </section>
    <?php include_once "../../templates/footer.php" ?>
</body>

</html>