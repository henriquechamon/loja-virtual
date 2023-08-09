<?php

function GetData($userId) {
    $connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");
    $query = $connection->prepare("SELECT * FROM conta WHERE id = ?");
    $query->execute([$userId]);

    if ($query->rowCount() > 0) {
        $userData = $query->fetch(PDO::FETCH_ASSOC);
        $jsonFilePath = 'user_data.json';
        $jsonData = json_encode($userData, JSON_PRETTY_PRINT);
        file_put_contents($jsonFilePath, $jsonData);
        
        return $userData;
    } else {
        return null;
    }
}

?>
