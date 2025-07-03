<?php

session_start();

include_once "../../config.php";

if (isset($_SESSION["user_mail"])) {
    header("Location: ../Controller/controller-profil.php");
    exit;
}

$regex_name = "/^[a-zA-ZÀ-ú]+$/";
$regex_password = "/^[a-zA-Z0-9]{8,30}+$/";
$regex_phone = "/^0[6-7]([-. ]?[0-9]{2}){4}$/";


$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["nom"])) {
        if (empty($_POST["nom"])) {
            $error["nom"] = "<i class='fa-solid fa-circle-exclamation'></i> Le nom est obligatoire.";
        } elseif (!preg_match($regex_name, $_POST["nom"])) {
            $error["nom"] = "<i class='fa-solid fa-circle-exclamation'></i> Le nom doit contenir uniquement des lettres.";
        }
    }



    if (isset($_POST["prenom"])) {
        if (empty($_POST["prenom"])) {
            $error["prenom"] = "<i class='fa-solid fa-circle-exclamation'></i> Le prénom est obligatoire.";
        } elseif (!preg_match($regex_name, $_POST["prenom"])) {
            $error["prenom"] = "<i class='fa-solid fa-circle-exclamation'></i> Le prénom doit contenir uniquement des lettres.";
        }
    }



    if (isset($_POST["telephone"])) {
        if (empty($_POST["telephone"])) {
            $error["telephone"] = "<i class='fa-solid fa-circle-exclamation'></i> Le téléphone est obligatoire.";
        } elseif (!preg_match($regex_phone, $_POST["telephone"])) {
            $error["telephone"] = "<i class='fa-solid fa-circle-exclamation'></i> Le téléphone doit être au format 06/07.";
        } else {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT user_telephone FROM 76_users WHERE user_telephone = :telephone";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":telephone", $_POST["telephone"]);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $error["telephone"] = "<i class='fa-solid fa-circle-exclamation'></i> Le numéro de téléphone existe déjà.";
            }
        }
    }

    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT user_mail FROM 76_users WHERE user_mail = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":email", $_POST["email"]);
    $stmt->execute();
    $stmt->rowCount() == 0 ? $found = false : $found = true;

    if (isset($_POST["email"])) {
        if (empty($_POST["email"])) {
            $error["email"] = "<i class='fa-solid fa-circle-exclamation'></i> L'adresse mail est obligatoire.";
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $error["email"] = "<i class='fa-solid fa-circle-exclamation'></i> L'adresse mail n'est pas valide.";
        } elseif ($found == true) {
            $error["email"] = "<i class='fa-solid fa-circle-exclamation'></i> L'adresse mail existe déjà.";
        }
    }



    if (isset($_POST["mot_de_passe"])) {
        if (empty($_POST["mot_de_passe"])) {
            $error["mot_de_passe"] = "<i class='fa-solid fa-circle-exclamation'></i> Le mot de passe est obligatoire.";
        } elseif (!preg_match($regex_password, $_POST["mot_de_passe"])) {
            $error["mot_de_passe"] = "<i class='fa-solid fa-circle-exclamation'></i> Le mot de passe doit contenir entre 8 et 30 caractères.";
        }
    }



    if (isset($_POST["confirmation_mot_de_passe"])) {
        if (empty($_POST["confirmation_mot_de_passe"])) {
            $error["confirmation_mot_de_passe"] = "<i class='fa-solid fa-circle-exclamation'></i> La confirmation du mot de passe est obligatoire.";
        } elseif ($_POST["confirmation_mot_de_passe"] != $_POST["mot_de_passe"]) {
            $error["confirmation_mot_de_passe"] = "<i class='fa-solid fa-circle-exclamation'></i> Les mots de passe ne correspondent pas.";
        }
    }


    if (empty($error)) {

        $sql = "INSERT INTO 76_users (user_nom, user_prenom, user_telephone, user_mail, user_mdp) VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":nom", $_POST["nom"]);
        $stmt->bindValue(":prenom", $_POST["prenom"]);
        $stmt->bindValue(":telephone", $_POST["telephone"]);
        $stmt->bindValue(":email", $_POST["email"]);
        $stmt->bindValue(":mot_de_passe", password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT));
        $stmt->execute();

        $pdo = "";

        header("Location: ../Controller/controller-confirmation.php");
        exit;
    }
}


include_once "../View/view-inscription.php";
