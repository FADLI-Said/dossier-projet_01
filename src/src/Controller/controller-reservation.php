<?php
// DEBUG: Log fatal errors and script start for AJAX debug
file_put_contents(__DIR__ . '/debug.log', date('c') . "\nSTART\n", FILE_APPEND);
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error) {
        file_put_contents(__DIR__ . '/debug.log', date('c') . "\nFATAL: " . print_r($error, true) . "\n", FILE_APPEND);
    }
});


// --- PARAMÈTRES DE DÉBOGAGE (À DÉSACTIVER EN PRODUCTION) ---
// Ces lignes affichent toutes les erreurs PHP directement sur la page.
// Pour la production, commentez-les ou retirez-les.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- FONCTION UNIVERSELLE POUR FORCER LE JSON EN AJAX ---
function forceJsonError($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
    exit();
}

// --- DÉMARRAGE DE LA SESSION ---
// session_start() doit être appelé une seule fois au tout début du script.
session_start();

// --- VÉRIFICATION DE LA CONNEXION UTILISATEUR ---
// Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion (sauf pour AJAX, on renvoie du JSON)
if (!isset($_SESSION["user_id"])) {
    if ((isset($_GET['action']) && $_GET['action'] == 'getTimeSlots') || (isset($_POST['action']) && $_POST['action'] == 'makeReservation') ||
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    ) {
        forceJsonError(['error' => 'Utilisateur non connecté. Veuillez vous reconnecter.']);
    } else {
        header("Location: controller-connexion.php");
        exit();
    }
}

// Vérifie si une prestation a été sélectionnée via l'URL (paramètre 'prestation').
// Si non, redirige vers la page de sélection des prestations (sauf pour AJAX POST makeReservation)
if (!isset($_GET["prestation"])) {
    // Si ce n'est pas une requête AJAX POST de réservation, on redirige ou erreur JSON
    if (!(isset($_GET['action']) && $_GET['action'] == 'getTimeSlots') && !(isset($_POST['action']) && $_POST['action'] == 'makeReservation')) {
        if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            forceJsonError(['error' => 'Aucune prestation sélectionnée.']);
        }
        header("Location: controller-prestation.php");
        exit();
    }
}

// --- INCLUSIONS DES FICHIERS NÉCESSAIRES ---
include_once "../../config.php";
include_once "../Model/model-prestation.php";

// --- GESTION DES REQUÊTES AJAX (GET et POST) ---
// Ces blocs sont dédiés aux requêtes AJAX envoyées par le JavaScript de la vue.
// Ils doivent exit() après avoir renvoyé une réponse JSON.

// --- GESTION DE LA RÉCUPÉRATION DES CRÉNEAUX HORAIRES (GET) ---
// Cette section est appelée par l'AJAX pour obtenir les créneaux disponibles pour une date donnée.

if (isset($_GET['action']) && $_GET['action'] == 'getTimeSlots' && isset($_GET['date'])) {
    header('Content-Type: application/json');

    $selectedDate = $_GET['date'];
    $allPossibleSlots = [];
    $availableSlots = [];

    // Vérification de la connexion PDO. Si $pdo n'est pas disponible, c'est une erreur critique.
    if (!isset($pdo) || !$pdo instanceof PDO) {
        error_log("Erreur: La variable $pdo n'est pas initialisée ou n'est pas un objet PDO.");
        forceJsonError(['error' => 'Erreur interne du serveur : connexion base de données non établie.']);
    }

    // Récupérer la durée de la prestation sélectionnée (depuis GET ou POST ou SESSION)
    $prestationId = null;
    if (isset($_GET['prestation'])) {
        $prestationId = $_GET['prestation'];
    } elseif (isset($_POST['prestationId'])) {
        $prestationId = $_POST['prestationId'];
    } elseif (isset($_SESSION['prestation_id'])) {
        $prestationId = $_SESSION['prestation_id'];
    }
    if (!$prestationId) {
        forceJsonError(['error' => 'Impossible de déterminer la prestation pour la durée.']);
    }
    $prestation = Prestations::getPrestationById($prestationId);
    if (!$prestation || empty($prestation['prestation_duree'])) {
        forceJsonError(['error' => 'Durée de prestation introuvable.']);
    }
    $dureeParts = explode(':', $prestation['prestation_duree']);
    $prestationDureeInterval = new DateInterval('PT' . (int)$dureeParts[0] . 'H' . (int)$dureeParts[1] . 'M' . (int)$dureeParts[2] . 'S');

    // Définition des paramètres pour la génération des créneaux.
    $intervalMinutes = 30;
    $startHour = 9;
    $endHour = 18;

    // Récupération des réservations existantes pour la date sélectionnée (avec heure de début et de fin)
    try {
        $stmt = $pdo->prepare("SELECT reservation_start, reservation_end FROM 76_reservation WHERE reservation_date = :date_rdv");
        $stmt->execute([':date_rdv' => $selectedDate]);
        $bookedSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        forceJsonError(['error' => 'Erreur SQL: ' . $e->getMessage()]);
    }

    // Génération de tous les créneaux possibles pour la journée sélectionnée.
    for ($h = $startHour; $h <= $endHour; $h++) {
        for ($m = 0; $m < 60; $m += $intervalMinutes) {
            if ($h == $endHour && $m > 0) {
                continue;
            }
            if ($h > $endHour) {
                continue;
            }

            $slotTime = sprintf('%02d:%02d', $h, $m);
            $fullSlotDatetime = $selectedDate . ' ' . $slotTime . ':00';
            $slotDateTimeObj = new DateTime($fullSlotDatetime, new DateTimeZone('Europe/Paris'));
            $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $isPast = ($slotDateTimeObj < $now);

            // Calculer l'heure de fin du créneau proposé
            $slotEndDateTime = clone $slotDateTimeObj;
            $slotEndDateTime->add($prestationDureeInterval);
            $slotEnd = $slotEndDateTime->format('H:i:s');
            $slotStart = $slotDateTimeObj->format('H:i:s');

            // Vérifier que le créneau ne tombe pas pendant la pause déjeuner (12h00-13h00)
            $pauseStart = new DateTime($selectedDate . ' 12:00:00', new DateTimeZone('Europe/Paris'));
            $pauseEnd = new DateTime($selectedDate . ' 13:00:00', new DateTimeZone('Europe/Paris'));
            // Si le créneau commence avant la fin de la pause et finit après le début de la pause, il chevauche la pause
            if ($slotDateTimeObj < $pauseEnd && $slotEndDateTime > $pauseStart) {
                continue;
            }

            // Vérifier que le créneau ne dépasse pas l'heure de fermeture (18h00)
            $closingTime = new DateTime($selectedDate . ' 18:00:00', new DateTimeZone('Europe/Paris'));
            if ($slotEndDateTime > $closingTime) {
                continue;
            }

            // Vérifier le chevauchement avec toutes les réservations existantes
            $isBooked = false;
            foreach ($bookedSlots as $resa) {
                // Si (début < resa_fin) ET (fin > resa_debut) => chevauchement
                if ($slotStart < $resa['reservation_end'] && $slotEnd > $resa['reservation_start']) {
                    $isBooked = true;
                    break;
                }
            }

            if (!$isPast && !$isBooked) {
                $availableSlots[] = [
                    'time' => $slotTime,
                    'full_datetime' => $fullSlotDatetime
                ];
            }
        }
    }

    if (empty($availableSlots)) {
        forceJsonError(['message' => 'Aucun créneau disponible pour ce jour.']);
    } else {
        header('Content-Type: application/json');
        echo json_encode($availableSlots);
        exit();
    }
}

// --- NOUVEAU : GESTION DE LA CRÉATION DE RÉSERVATION (POST) ---
// Cette section gère l l'enregistrement d'une nouvelle réservation dans la base de données.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'makeReservation') {
    header('Content-Type: application/json');
    // DEBUG LOGS
    error_log('SESSION: ' . print_r($_SESSION, true));
    error_log('POST: ' . print_r($_POST, true));

    // Vérification de la session utilisateur.
    if (!isset($_SESSION["user_id"])) {
        forceJsonError(['success' => false, 'message' => 'Utilisateur non connecté. Veuillez vous reconnecter.']);
    }
    $userId = $_SESSION["user_id"];

    // Vérification des données POST requises pour créer la réservation.
    if (!isset($_POST['fullDatetime']) || !isset($_POST['prestationId'])) {
        forceJsonError(['success' => false, 'message' => 'Données de réservation manquantes (date/heure ou ID prestation).']);
    }

    $fullDatetime = $_POST['fullDatetime']; // Ex: "YYYY-MM-DD HH:MM:SS"
    $prestationId = $_POST['prestationId'];

    // Parse la date et l'heure de la réservation.
    try {
        $reservationDateTime = new DateTime($fullDatetime, new DateTimeZone('Europe/Paris'));
        $reservationDate = $reservationDateTime->format('Y-m-d');
        $reservationStart = $reservationDateTime->format('H:i:s');
    } catch (Exception $e) {
        forceJsonError(['success' => false, 'message' => 'Format de date/heure invalide.']);
    }

    // Récupère la durée de la prestation depuis la BDD pour calculer l'heure de fin.
    try {
        $stmt = $pdo->prepare("SELECT prestation_duree FROM 76_prestation WHERE prestation_id = :prestation_id");
        $stmt->execute([':prestation_id' => $prestationId]);
        $prestationDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestationDetails || empty($prestationDetails['prestation_duree'])) {
            forceJsonError(['success' => false, 'message' => 'Durée de prestation introuvable pour la prestation sélectionnée.']);
        }

        // Convertit la durée (format TIME 'HH:MM:SS') en un objet DateInterval.
        $dureeParts = explode(':', $prestationDetails['prestation_duree']);
        // 'PT' est requis pour le format des intervalles de temps (Period Time).
        $prestationDureeInterval = new DateInterval('PT' . (int)$dureeParts[0] . 'H' . (int)$dureeParts[1] . 'M' . (int)$dureeParts[2] . 'S');

        // Calcule l'heure de fin du rendez-vous.
        $reservationEndDateTime = clone $reservationDateTime;
        $reservationEndDateTime->add($prestationDureeInterval);
        $reservationEnd = $reservationEndDateTime->format('H:i:s');
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération de la durée de prestation ou du calcul de fin: " . $e->getMessage());
        forceJsonError(['success' => false, 'message' => 'Erreur interne lors du calcul de la durée du rendez-vous.']);
    }

    // --- Vérification des chevauchements (très important pour éviter les doubles réservations) ---
    // Cette requête vérifie si la nouvelle réservation chevauche une réservation existante.
    try {
        $stmtOverlap = $pdo->prepare(
        "SELECT COUNT(*) 
        FROM 76_reservation 
        WHERE reservation_date = :reservation_date 
        AND ((reservation_start < :reservation_end AND reservation_end > :reservation_start))");

        $stmtOverlap->execute([
            ':reservation_date' => $reservationDate,
            ':reservation_start' => $reservationStart,
            ':reservation_end' => $reservationEnd
        ]);
        $overlapCount = $stmtOverlap->fetchColumn();

        if ($overlapCount > 0) {
            forceJsonError(['success' => false, 'message' => 'Ce créneau est déjà réservé ou chevauche une autre réservation existante. Veuillez choisir un autre créneau.']);
        }
    } catch (PDOException $e) {
        error_log("Erreur BDD lors de la vérification de chevauchement: " . $e->getMessage());
        forceJsonError(['success' => false, 'message' => 'Erreur interne lors de la vérification des disponibilités.']);
    }

    // --- Insertion de la réservation dans la base de données ---
    try {
        $stmtInsert = $pdo->prepare(
            "INSERT INTO 76_reservation (reservation_date, reservation_start, reservation_end, prestation_id, user_id)
            VALUES (:reservation_date, :reservation_start, :reservation_end, :prestation_id, :user_id)");
            
        $stmtInsert->execute([
            ':reservation_date' => $reservationDate,
            ':reservation_start' => $reservationStart,
            ':reservation_end' => $reservationEnd,
            ':prestation_id' => $prestationId,
            ':user_id' => $userId
        ]);

        if ($stmtInsert->rowCount() > 0) {
            forceJsonError(['success' => true, 'message' => 'Réservation effectuée avec succès !']);
        } else {
            forceJsonError(['success' => false, 'message' => 'Échec de la réservation. Aucune ligne insérée.']);
        }
    } catch (PDOException $e) {
        error_log('ERREUR SQL RESA: ' . $e->getMessage());
        forceJsonError(['success' => false, 'message' => 'Erreur SQL: ' . $e->getMessage()]);
    }
    // Très important d'arrêter l'exécution après avoir traité la requête POST.
    exit();
}

// --- RENDU DE LA VUE (POUR LES REQUÊTES GET INITIALES NON AJAX) ---
// Ce bloc s'exécute lorsque la page est chargée normalement (pas via AJAX).

$mois = [
    1 => "Janvier",
    2 => "Février",
    3 => "Mars",
    4 => "Avril",
    5 => "Mai",
    6 => "Juin",
    7 => "Juillet",
    8 => "Août",
    9 => "Septembre",
    10 => "Octobre",
    11 => "Novembre",
    12 => "Décembre"
];


// Récupère les détails de la prestation sélectionnée.
// Pour l'affichage classique (GET), on utilise $_GET["prestation"]
// Pour l'AJAX POST (makeReservation), on utilise $_POST["prestationId"]
if (isset($_GET["prestation"])) {
    $prestation = Prestations::getPrestationById($_GET["prestation"]);
    if (!$prestation) {
        header("Location: controller-prestation.php");
        exit();
    }
} elseif (isset($_POST['action']) && $_POST['action'] === 'makeReservation' && isset($_POST['prestationId'])) {
    $prestation = Prestations::getPrestationById($_POST['prestationId']);
    if (!$prestation) {
        forceJsonError(['success' => false, 'message' => 'Prestation introuvable pour la réservation.']);
    }
}

// Formatage de la monnaie pour l'affichage.
$fmt = numfmt_create('fr_FR', NumberFormatter::CURRENCY);

// Formatage de la durée de la prestation pour l'affichage.
$duree = "Non défini"; // Valeur par défaut
if ($prestation["prestation_duree"] != null && $prestation["prestation_duree"] !== "00:00:00") {
    try {
        $date = new DateTimeImmutable($prestation["prestation_duree"]);
        if ($date->format('H') == 0 && $date->format('i') != 0) {
            $duree = $date->format('i') . " min";
        } elseif ($date->format('H') >= 1 && $date->format('i') == 0) {
            $duree = $date->format('H') . " h";
        } else {
            $duree = $date->format('H') . " h " . $date->format('i') . " min";
        }
    } catch (Exception $e) {
        error_log("Erreur de formatage de la durée de prestation: " . $e->getMessage());
        $duree = "Non défini (Erreur)";
    }
}


// Récupère le mois actuel pour l'affichage du calendrier.
$currentMonth = date("n");

// Inclut le fichier de la vue pour afficher le contenu HTML.
include_once "../View/view-reservation.php";

// Pas d'accolade fermante en trop ici ! Le fichier se termine ici.