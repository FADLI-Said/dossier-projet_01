<?php

class Rating
{

    public static function averageScore()
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT AVG(rating_score) AS average_rating FROM 76_rating";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $averageRating = $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
        return $averageRating;
    }

    public static function getParticipantNumber()
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) AS total_ratings FROM 76_rating";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $totalRatings = $stmt->fetch(PDO::FETCH_ASSOC)['total_ratings'];
        if ($totalRatings === null) {
            $totalRatings = 0;
        }
        return $totalRatings;
    }

    public static function getTenRatings()
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT user_mail, rating_score, rating_description FROM 76_rating NATURAL JOIN 76_users ORDER BY rating_score DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ratings;
    }

    public static function addRating($userId, $description, $ratingScore)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO 76_rating (user_id, rating_description, rating_score) VALUES (:user_id, :description, :rating_score)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':rating_score', $ratingScore);
        return $stmt->execute();
    }

    public static function getRatingsByUserId($userId)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT user_id, user_mail, rating_score, rating_description FROM 76_rating NATURAL JOIN 76_users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateRating($userId, $description, $ratingScore)
    {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE 76_rating SET rating_description = :description, rating_score = :rating_score WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':rating_score', $ratingScore);
        return $stmt->execute();
    }
}
