<?php
session_start();

$_SESSION = [];

session_destroy();

header('Location: controller-accueil.php');
exit;