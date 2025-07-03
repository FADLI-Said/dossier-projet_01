<?php

session_start();

include_once "../../config.php";
include_once "../Model/model-user.php";

if (isset($_SESSION["user_mail"])) {
    header("Location: ../Controller/controller-profil.php");
    exit;
}

$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["id"])) {
        if (empty($_POST["id"])) {
            $error["id"] = "<i class='fa-solid fa-circle-exclamation'></i> L'adresse mail est obligatoire.";
        }
    }

    if (isset($_POST["password"])) {
        if (empty($_POST["password"])) {
            $error["password"] = "<i class='fa-solid fa-circle-exclamation'></i> Le mot de passe est obligatoire.";
        }
    }

    if (empty($error)) {
        $info = User::getUserByMail($_POST["id"]);

        if ($info["found"] == false) {
            $error["connexion"] = "<i class='fa-solid fa-circle-exclamation'></i> L'identifiant ou le mot de passe est incorrect.";
        } else {
            if (password_verify($_POST["password"], $info["user"]["user_mdp"])) {
                $_SESSION = $info["user"];
                header("Location: ../Controller/controller-profil.php");
                exit;
            } else {
                $error["connexion"] = "<i class='fa-solid fa-circle-exclamation'></i> L'identifiant ou le mot de passe est incorrect.";
            }
        }

    }

}

include_once "../View/view-connexion.php";

?>