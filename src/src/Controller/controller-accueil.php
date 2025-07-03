<?php

session_start();

include_once "../../config.php";
include_once "../Model/model-prestation.php";
include_once "../Model/model-rating.php";

$prestations = Prestations::getAllPrestations();
// var_dump($prestations);

$fmt = numfmt_create('fr_FR', NumberFormatter::CURRENCY);

$quote = [
    "La beauté commence au moment où vous décidez d'être vous-même.",
    "La beauté est une lumière dans le cœur.",
    "La vraie beauté d'une femme se reflète dans son âme.",
    "La beauté est éternelle quand elle vient de l'intérieur.",
    "La beauté est une promesse de bonheur."
];

$auteur = ["Coco Chanel", "Khalil Gibran", "Audrey Hepburn", "Sophia Loren", "Stendhal"];

$randomIndex = array_rand($quote);
$randomQuote = $quote[$randomIndex];
$randomAuthor = $auteur[$randomIndex];

foreach ($prestations as $value) {
    $duree = "Non défini";
    if ($value["prestation_duree"] != null) {
        $date = new DateTimeImmutable($value["prestation_duree"]);
        if ($date->format('H') == 0 && $date->format('i') != 0) {
            $duree = $date->format('i') . " min";
        } elseif ($date->format('H') >= 1 && $date->format('i') == 0) {
            $duree = $date->format('H') . " h";
        } elseif ($date->format('H:i:s') == "00:00:00") {
            $duree = "Non défini";
        } else {
            $duree = $date->format('H') . " h " . $date->format('i') . " min";
        }
    }
}

$averageRating = Rating::averageScore();
$totalRatings = Rating::getParticipantNumber();
$ratings = Rating::getTenRatings();

if ($averageRating === null) {
    $averageRating = 0;
}

switch (true) {
    case $averageRating <= 0.5:
        $stars =
            "<i class='fa-regular fa-star-half-stroke'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating == 1:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating < 2:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star-half-stroke'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating == 2:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating < 3:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star-half-stroke'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating == 3:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating < 4:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star-half-stroke'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating == 4:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star'></i>";
        break;

    case $averageRating < 5:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-regular fa-star-half-stroke'></i>";
        break;

    case $averageRating <= 5:
        $stars =
            "<i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>
        <i class='fa-solid fa-star'></i>";
        break;


    default:
        # code...
        break;
}

include_once "../View/view-accueil.php";
