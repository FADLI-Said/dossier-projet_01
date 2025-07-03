<?php

session_start();

include_once "../../config.php";
include_once "../Model/model-rating.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: controller-connexion.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['description'])) {
        $errors["description"] = "<i class='fa-solid fa-circle-exclamation'></i> Votre avis est requis.";
    }

    if (empty($_POST['note'])) {
        $errors["note"] = "<i class='fa-solid fa-circle-exclamation'></i> Votre note est requise.";
    } elseif (!in_array($_POST['note'], ['1', '2', '3', '4', '5'])) {
        $errors["note"] = "La note sélectionnée n'est pas valide.";
    }

    $userRatings = Rating::getRatingsByUserId($_SESSION['user_id']);

    if (empty($errors)) {
        if (!empty($userRatings)) {
            if (Rating::updateRating($_SESSION['user_id'], $_POST['description'], $_POST['note'])) {
                header('Location: controller-profil.php');
                exit;
            } else {
                $errors[] = "<i class='fa-solid fa-circle-exclamation'></i> Une erreur est survenue lors de la modification de votre avis.";
            }
        } else {
            if (Rating::addRating($_SESSION['user_id'], $_POST['description'], $_POST['note'])) {
                header('Location: controller-profil.php');
                exit;
            } else {
                $errors[] = "<i class='fa-solid fa-circle-exclamation'></i> Une erreur est survenue lors de l'envoi de votre avis.";
            }
        }
    }
}

$userRatings = Rating::getRatingsByUserId($_SESSION['user_id']);



// Récupérer les rendez-vous de l'utilisateur
include_once "../Model/model-reservation.php";
$userRdv = getReservationsByUser($_SESSION['user_id']);

include_once "../View/view-profil.php";
