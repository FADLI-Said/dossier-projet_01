<?php

// Définition des constantes de connexion à la base de données
define("DB_HOST", "db");
define("DB_NAME", "annbeautyvisage");
define("DB_USER", "root"); // Il est recommandé de changer ce nom d'utilisateur en 'Said' (sans le tréma) si possible
define("DB_PASS", "root");

// --- AJOUT IMPORTANT : Création de la connexion PDO ---
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active le mode exception pour les erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Définit le mode de récupération par défaut
        PDO::ATTR_EMULATE_PREPARES   => false,                // Désactive l'émulation des requêtes préparées pour de meilleures performances et sécurité
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());
    // Détection AJAX universelle (header ou paramètre action)
    $isAjax = (
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
        (isset($_POST['action'])) || (isset($_GET['action']))
    );
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erreur connexion BDD: ' . $e->getMessage()]);
        exit();
    } else {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Erreur BDD</title></head><body>Désolé, une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.</body></html>';
        exit();
    }
}

?>