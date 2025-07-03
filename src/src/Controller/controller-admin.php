<?php

session_start();

include_once "../../config.php";
include_once "../Model/model-prestation.php";


$mois = [
    1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin",
    7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"
];
$currentMonth = date("n");
$prestations = Prestations::getAllPrestations();

$fmt = numfmt_create('fr_FR', NumberFormatter::CURRENCY);

// var_dump($_SESSION);

// Récupérer tous les rendez-vous à partir d'aujourd'hui pour affichage JS
include_once "../Model/model-reservation.php";
$allRdv = getAllReservations();

include_once "../View/view-admin.php";

?>