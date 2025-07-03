<?php

class Prestations
{
    public static function getAllPrestations()
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM 76_prestation";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);;
    }

    public static function deletePrestation($id)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM 76_prestation WHERE prestation_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getPrestationById($id)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM 76_prestation WHERE prestation_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePrestation($id, $image, $nom, $description, $prix, $duree)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 
        "UPDATE 76_prestation 
        SET prestation_image = :image, prestation_nom = :nom, prestation_description = :description, prestation_prix = :prix, prestation_duree = :duree 
        WHERE prestation_id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':duree', $duree, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function createPrestation($image, $nom, $description, $prix, $duree)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO 76_prestation (prestation_image, prestation_nom, prestation_description, prestation_prix, prestation_duree) VALUES (:image, :nom, :description, :prix, :duree)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':duree', $duree, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
