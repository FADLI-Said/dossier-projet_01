<?php
session_start();
include_once "../../config.php";
include_once "../Model/model-prestation.php";

// if (!isset($_SESSION["admin_mail"])) {
//     header("Location: ../Controller/controller-accueil.php");
//     exit;
// }

// Récupérer les informations de la prestation à supprimer
$prestation = Prestations::getPrestationById($_GET["prestation"]);

if ($prestation && !empty($prestation['prestation_image'])) {
    $imagePath = "../../assets/images/" . $prestation['prestation_image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}
Prestations::deletePrestation($_GET["prestation"]);

header("Location: ../Controller/controller-admin.php");
exit;

?>