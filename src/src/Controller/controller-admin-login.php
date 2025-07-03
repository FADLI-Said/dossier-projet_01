<?php

session_start();

require_once '../../config.php';
require_once '../Model/model-admin.php';

// Si l'admin a besoin de créer un compte, décommenter les lignes suivantes pour insérer un admin par défaut
// $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $sql = 'INSERT INTO 76_admin (admin_mail, admin_mdp) VALUES (:mail, :mdp)';
// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':mail', "admin@admin.fr");
// $stmt->bindValue(':mdp', password_hash("00000000", PASSWORD_DEFAULT));
// $stmt->execute();



$error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs
    if (isset($_POST['admin_mail'])) {
        if (empty($_POST['admin_mail'])) {
            $error['admin_mail'] = "<i class='fa-solid fa-circle-exclamation'></i> L'identifiant est obligatoire.";
        }
    }
    if (isset($_POST['admin_mdp'])) {
        if (empty($_POST['admin_mdp'])) {
            $error['admin_mdp'] = "<i class='fa-solid fa-circle-exclamation'></i> Le mot de passe est obligatoire.";
        }
    }

    if (empty($error)) {
        $user = $_POST['admin_mail'] ?? '';
        $pass = $_POST['admin_mdp'] ?? '';
        $admin = getAdminByUsername($pdo, $user);
        if ($admin && password_verify($pass, $admin['admin_mdp'])) {
            $_SESSION['is_admin'] = true;
            header('Location: ../Controller/controller-admin.php');
            exit;
        } else {
            $error['connexion'] = "<i class='fa-solid fa-circle-exclamation'></i> Identifiants incorrects.";
        }
    }
}

include_once '../View/view-admin-login.php';
