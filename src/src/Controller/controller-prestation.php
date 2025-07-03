<?php

session_start();

include_once "../../config.php";
include_once "../Model/model-prestation.php";

$prestations = Prestations::getAllPrestations();
// var_dump($prestations);

$fmt = numfmt_create('fr_FR', NumberFormatter::CURRENCY);




include_once "../View/view-prestation.php";
