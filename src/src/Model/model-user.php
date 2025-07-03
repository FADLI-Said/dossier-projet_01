<?php

class User
{

    public static function getUserByMail($id)
    {

        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM 76_users WHERE user_mail = :mail";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":mail", $id);
        $stmt->execute();

        $stmt->rowCount() == 0 ? $found = false : $found = true;
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $info = [
            "found" => $found,
            "user" => $user
        ];
        return $info;
    }
}
