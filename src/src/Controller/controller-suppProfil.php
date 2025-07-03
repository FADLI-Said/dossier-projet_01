<?php

session_start();
require_once "../../config.php";

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "DELETE FROM 76_users WHERE user_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_GET["user"], PDO::PARAM_INT);
$stmt->execute();
$pdo = "";

session_destroy();

header("Location: controller-accueil.php");
exit;

?>