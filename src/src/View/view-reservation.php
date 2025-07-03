<?php require_once "../../templates/header.php" ?>

<body>
    <?php if (isset($_SESSION["user_mail"])) {
        include_once "../../templates/co-nav.php";
    } else {
        include_once "../../templates/deco-nav.php";
    } ?>

    <section id="reservation" class="bg-dark">
        <a href="../Controller/controller-accueil.php" class="text-start retour"><i class="fas fa-arrow-left"></i>
            Accueil</a>
        <h1 class="text-center">Réservation</h1>

        <div class="border border-black rounded shadow p-3 mb-5 rounded m-auto w-75">
            <p class="h2">Prestation sélectionnée</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2 w-50">
                    <img src="../../assets/images/<?= $prestation["prestation_image"] ?>" class="rounded img-fluid w-25" alt="">
                    <p class="m-0 h4"><?= $prestation["prestation_nom"] ?> <br> <span class="fs-6 fw-normal"><?= $prestation["prestation_description"] ?></span></p>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2 w-50">
                    <p class="m-0"><?= $duree ?> -</p>
                    <p class="m-0">à partir de <?= numfmt_format_currency($fmt, $prestation["prestation_prix"], "EUR") ?></p>
                    <a href="../Controller/controller-prestation.php" class="rounded btn btn-outline-light">Modifier</a>
                </div>
            </div>
        </div>
        <input type="hidden" id="prestationId" value="<?= $prestation["prestation_id"] ?>">

        <ul class="nav nav-pills mb-3 p-2" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#mois0" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><?= $mois[$currentMonth] ?></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#mois1" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><?= $mois[$currentMonth + 1] ?></button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
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
                $firstDayOfWeek = date("N", $firstDay); // 1 (Lundi) à 7 (Dimanche)

                // Pour une comparaison de date robuste (pas seulement le numéro du jour)
                $today = new DateTime('now', new DateTimeZone('Europe/Paris'));
                $today->setTime(0, 0, 0);
            ?>

                <div class="tab-pane fade <?= $offset == 0 ? ' show active' : '' ?>" id="mois<?= $offset ?>" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                    <div class="calendar-container dark p-5 tab-content border border-black rounded shadow rounded" id="calendrier">
                        <div id="mois">
                            <div class="calendar-header justify-content-center">
                                <span class="calendar-month"><?= $mois[$Month] . " " . $Year ?></span>
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
                                for ($i = 1; $i < $firstDayOfWeek; $i++) { ?>
                                    <a class="calendar-day calendar-empty"></a>
                                    <?php
                                }

                                for ($dayNum = 1; $dayNum <= $daysInMonth; $dayNum++) {
                                    $currentDayDate = new DateTime("$Year-$Month-$dayNum", new DateTimeZone('Europe/Paris'));
                                    $dayOfWeek = (int)$currentDayDate->format("N");

                                    $isPastDay = ($currentDayDate < $today);
                                    $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);

                                    if ($isPastDay || $isWeekend) {
                                        $classNames = "week-end";
                                    ?>
                                        <a class="<?= $classNames ?>"><?= $dayNum ?></a>
                                    <?php } else { ?>
                                        <a class="calendar-day calendar-selectable-day" href="#" data-date="<?= $Year ?>-<?= sprintf('%02d', $Month) ?>-<?= sprintf('%02d', $dayNum) ?>"><?= $dayNum ?></a>
                                    <?php }
                                }

                                $lastDayOfWeek = date("N", mktime(0, 0, 0, $Month, $daysInMonth, $Year));
                                for ($i = $lastDayOfWeek; $i < 7; $i++) { ?>
                                    <a class="calendar-day calendar-empty"></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div id="time-slots-mois<?= $offset ?>" class="time-slots-container">
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- On place la div horraires ici, en dehors de la boucle des mois -->
        <div id="horraires" class="horraires-list"></div>
    </section>

    <?php include_once "../../templates/footer.php" ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pillsTab = document.getElementById('pills-tab');
            const prestationId = document.getElementById('prestationId').value; // Récupère l'ID de la prestation

            async function loadTimeSlots(selectedDate) {

                const horairesDiv = document.getElementById('horraires');
                // Affichage de la date sélectionnée au format français
                if (selectedDate) {
                    const dateObj = new Date(selectedDate);
                    const options = {
                        day: 'numeric',
                        month: 'long'
                    };
                    const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                    horairesDiv.innerHTML = `<div style="font-weight:bold;font-size:1.2rem;margin-bottom:0.5rem;">${dateFr.charAt(0).toUpperCase() + dateFr.slice(1)}</div><div>Chargement des créneaux...</div>`;
                } else {
                    horairesDiv.innerHTML = '<div>Chargement des créneaux...</div>';
                }

                try {
                    const prestationId = document.getElementById('prestationId').value;
                    const response = await fetch(`../Controller/controller-reservation.php?action=getTimeSlots&date=${selectedDate}&prestation=${prestationId}`);
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    const data = await response.json();

                    if (data.error) {
                        // Réaffiche la date sélectionnée si erreur
                        if (selectedDate) {
                            const dateObj = new Date(selectedDate);
                            const options = {
                                day: 'numeric',
                                month: 'long'
                            };
                            const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                            horairesDiv.innerHTML = `<div style=\"font-weight:bold;font-size:1.2rem;margin-bottom:0.5rem;\">${dateFr.charAt(0).toUpperCase() + dateFr.slice(1)}</div><div>Erreur: ${data.error}</div>`;
                        } else {
                            horairesDiv.innerHTML = `<div>Erreur: ${data.error}</div>`;
                        }
                        return;
                    }
                    if (data.message) {
                        if (selectedDate) {
                            const dateObj = new Date(selectedDate);
                            const options = {
                                day: 'numeric',
                                month: 'long'
                            };
                            const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                            horairesDiv.innerHTML = `<div style=\"font-weight:bold;font-size:1.2rem;margin-bottom:0.5rem;\">${dateFr.charAt(0).toUpperCase() + dateFr.slice(1)}</div><div>${data.message}</div>`;
                        } else {
                            horairesDiv.innerHTML = `<div>${data.message}</div>`;
                        }
                        return;
                    }
                    if (data.length === 0) {
                        if (selectedDate) {
                            const dateObj = new Date(selectedDate);
                            const options = {
                                day: 'numeric',
                                month: 'long'
                            };
                            const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                            horairesDiv.innerHTML = `<div style=\"font-weight:bold;font-size:1.2rem;margin-bottom:0.5rem;\">${dateFr.charAt(0).toUpperCase() + dateFr.slice(1)}</div><div>Aucun créneau disponible pour ce jour.</div>`;
                        } else {
                            horairesDiv.innerHTML = `<div>Aucun créneau disponible pour ce jour.</div>`;
                        }
                        return;
                    }

                    let slotsHtml = '<div>';
                    data.forEach(slot => {
                        // Ajout de la classe 'time-slot-btn' pour styliser si besoin
                        slotsHtml += `<a href=\"#\" class=\"time-slot time-slot-btn\" data-full-datetime=\"${slot.full_datetime}\">${slot.time}</a>`;
                    });
                    slotsHtml += '</div>';
                    // Réaffiche la date sélectionnée au-dessus des créneaux
                    if (selectedDate) {
                        const dateObj = new Date(selectedDate);
                        const options = {
                            day: 'numeric',
                            month: 'long'
                        };
                        const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                        horairesDiv.innerHTML = `<div style=\"font-weight:bold;font-size:1.2rem;margin-bottom:0.5rem;\">${dateFr.charAt(0).toUpperCase() + dateFr.slice(1)}</div>` + slotsHtml;
                    } else {
                        horairesDiv.innerHTML = slotsHtml;
                    }

                    attachTimeSlotClickListeners(horairesDiv);
                } catch (error) {
                    console.error("Erreur lors du chargement des créneaux:", error);
                    horairesDiv.innerHTML = '<div>Erreur lors du chargement des créneaux.</div>';
                }
            }

            function clearTimeSlots(containerId) {
                const container = document.getElementById(containerId);
                if (container) {
                    container.innerHTML = '';
                }
            }

            function attachDayClickListeners() {
                const selectableDays = document.querySelectorAll('.calendar-selectable-day');
                selectableDays.forEach(day => {
                    day.addEventListener('click', function(e) {
                        e.preventDefault();
                        const selectedDate = this.dataset.date;

                        document.querySelectorAll('.calendar-selectable-day.active-selected').forEach(activeDay => {
                            activeDay.classList.remove('active-selected');
                        });
                        this.classList.add('active-selected');

                        // Appel direct de loadTimeSlots sans utiliser le paramètre containerId
                        loadTimeSlots(selectedDate);
                    });
                });
            }

            function attachTimeSlotClickListeners(timeSlotsContainer) {
                const selectableSlots = timeSlotsContainer.querySelectorAll('.time-slot');
                selectableSlots.forEach(slot => {
                    slot.addEventListener('click', function(e) {
                        e.preventDefault();
                        const fullDatetime = this.dataset.fullDatetime;

                        // Formatage FR de la date pour confirmation
                        const dateObj = new Date(fullDatetime.replace(' ', 'T'));
                        const options = {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        };
                        const dateFr = dateObj.toLocaleDateString('fr-FR', options);
                        const heureFr = dateObj.toLocaleTimeString('fr-FR', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        const message = `Confirmez-vous la réservation pour le ${dateFr} à ${heureFr} ?`;

                        // --- Confirmation et envoi de la réservation ---
                        if (confirm(message)) {
                            fetch(`../Controller/controller-reservation.php`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: new URLSearchParams({
                                        action: 'makeReservation',
                                        fullDatetime: fullDatetime,
                                        prestationId: prestationId // Envoyé depuis le champ caché
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert(data.message);
                                        window.location.href = '../Controller/controller-profil.php';
                                    } else {
                                        alert('Erreur: ' + (data.message || JSON.stringify(data)));
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur lors de la réservation:', error);
                                    alert('Une erreur est survenue lors de la tentative de réservation. (catch) ' + error);
                                });
                        }
                    });
                });
            }

            pillsTab.addEventListener('shown.bs.tab', function(e) {
                const activeTabPane = document.querySelector('.tab-pane.show.active');
                const monthOffset = activeTabPane.id.replace('mois', '');
                const timeSlotsContainerId = 'time-slots-mois' + monthOffset;

                document.querySelectorAll('.time-slots-container').forEach(container => {
                    if (container.id !== timeSlotsContainerId) {
                        clearTimeSlots(container.id);
                    }
                });
            });

            attachDayClickListeners();

            const initialActiveTabPane = document.querySelector('.tab-pane.show.active');
            const initialMonthOffset = initialActiveTabPane.id.replace('mois', '');
            document.querySelectorAll('.time-slots-container').forEach(container => {
                if (container.id !== `time-slots-mois${initialMonthOffset}`) {
                    clearTimeSlots(container.id);
                }
            });
        });
    </script>
</body>

</html>