<?php
session_start();
include_once "../../config.php";
include_once "../Model/model-prestation.php";
$regex_name = "/^[a-zA-ZÀ-ú\s'-]+$/";
$regex_description = "/^[a-zA-ZÀ-ú0-9\s.,'()-]+$/";
$regex_price = "/^[0-9]{1,3}(?:\.[0-9]{1,2})?$/";
$regex_duration = "/^(?:[01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/";
$regex_image = "/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|gif)$/";

// if ($_SESSION["user_role"] != "admin") {
//     header("Location: ../Controller/controller-accueil.php");
//     exit;
// }

$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["prestation_image"])) {
        if (empty($_FILES["prestation_image"])) {
            $error["prestation_image"] = "<i class='fa-solid fa-circle-exclamation'></i> Veuillez renseigner l'image de la prestation.";
        } elseif (!preg_match($regex_image, $_FILES["prestation_image"]["name"])) {
            $error["prestation_image"] = "<i class='fa-solid fa-circle-exclamation'></i> L'image doit être au format jpg, jpeg, png ou gif.";
        }
    }

    if (isset($_POST["prestation_nom"])) {
        if (empty($_POST["prestation_nom"])) {
            $error["prestation_nom"] = "<i class='fa-solid fa-circle-exclamation'></i> Veuillez renseigner le nom de la prestation.";
        } elseif (!preg_match($regex_name, $_POST["prestation_nom"])) {
            $error["prestation_nom"] = "<i class='fa-solid fa-circle-exclamation'></i> Le nom de la prestation doit contenir uniquement des lettres, espaces, tirets et apostrophes.";
        }
    }

    if (isset($_POST["prestation_description"])) {
        if (empty($_POST["prestation_description"])) {
            $error["prestation_description"] = "<i class='fa-solid fa-circle-exclamation'></i> Veuillez renseigner la description de la prestation.";
        } elseif (!preg_match($regex_description, $_POST["prestation_description"])) {
            $error["prestation_description"] = "<i class='fa-solid fa-circle-exclamation'></i> La description de la prestation contient des caractères non autorisés (lettres, chiffres, espaces, points, virgules, apostrophes, parenthèses et tirets uniquement).";
        }
    }

    if (isset($_POST["prestation_prix"])) {
        if (empty($_POST["prestation_prix"])) {
            $error["prestation_prix"] = "<i class='fa-solid fa-circle-exclamation'></i> Veuillez renseigner le prix de la prestation.";
        } elseif (!preg_match($regex_price, $_POST["prestation_prix"])) {
            $error["prestation_prix"] = "<i class='fa-solid fa-circle-exclamation'></i> Le prix de la prestation doit être un nombre positif (ex: 10, 50.50, 120.00 max: 999.99).";
        }
    }

    if (isset($_POST["prestation_duree"])) {
        if (empty($_POST["prestation_duree"])) {
            $error["prestation_duree"] = "<i class='fa-solid fa-circle-exclamation'></i> Veuillez renseigner la durée de la prestation (au format HH:MM:SS).";
        } elseif (!preg_match($regex_duration, $_POST["prestation_duree"])) {
            $error["prestation_duree"] = "<i class='fa-solid fa-circle-exclamation'></i> La durée de la prestation doit être au format HH:MM:SS (ex: 01:30:00 pour 1h30).";
        }
    }

    if (empty($error)) {
        $target_dir = "../../assets/images/";
        $newName = null;
        if (isset($_FILES["prestation_image"]) && isset($_FILES["prestation_image"]["name"]) && !empty($_FILES["prestation_image"]["name"])) {
            $newName = uniqid() . "_" . basename($_FILES["prestation_image"]["name"]);
            $target_file = $target_dir . $newName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        }

        if ($newName && !empty($_POST["prestation_description"])) {
            if (move_uploaded_file($_FILES["prestation_image"]["tmp_name"], $target_file)) {
                // echo "The file " . htmlspecialchars(basename($_FILES["prestation_image"]["name"])) . " a été téléchargé.";
            } else {
                // echo "Désolé, il y a eu une erreur lors du téléchargement de votre fichier.";
            }

            Prestations::createPrestation($newName, $_POST["prestation_nom"], $_POST["prestation_description"], $_POST["prestation_prix"], $_POST["prestation_duree"]);
            header("Location: ../Controller/controller-admin.php");
            exit;
        }
    }
}

include_once "../View/view-add-prestation.php";
