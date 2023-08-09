<?php
function SellController($email){
    require '../db/connection.php'; 
    $connection = DatabaseConnection();

    $query = $connection->prepare("SELECT * FROM pedidos WHERE status = 1 AND email = ?");
    $query->bindParam(1, $email);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result; 
} 
?>
