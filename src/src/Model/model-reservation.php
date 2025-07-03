<?php

// Récupérer tous les rendez-vous d'un utilisateur
function getReservationsByUser($user_id)
{
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT r.reservation_id, r.reservation_date, r.reservation_start, r.reservation_end, r.prestation_id, r.user_id,
                   p.prestation_nom, p.prestation_image, p.prestation_prix, p.prestation_description, p.prestation_duree
            FROM 76_reservation r
            JOIN 76_prestation p ON r.prestation_id = p.prestation_id
            WHERE r.user_id = :user_id
            ORDER BY r.reservation_date DESC, r.reservation_start DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer tous les rendez-vous de tous les utilisateurs
function getAllReservations()
{
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT r.reservation_id, r.reservation_date, r.reservation_start, r.reservation_end, r.prestation_id, r.user_id,
                   p.prestation_nom, p.prestation_image, p.prestation_prix, p.prestation_description, p.prestation_duree, u.user_nom, u.user_prenom
            FROM 76_reservation r
            JOIN 76_prestation p ON r.prestation_id = p.prestation_id
            JOIN 76_users u ON r.user_id = u.user_id
            WHERE r.reservation_date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            ORDER BY r.reservation_date ASC, r.reservation_start ASC';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
