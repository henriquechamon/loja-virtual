<?php
require '../db/connection.php'; 
$connection = DatabaseConnection();
$id = $_GET['id'];

$query = $connection->prepare("UPDATE pedidos SET status = 1 WHERE id = ?");
$query->bindParam(1, $id);

if ($query->execute()) {
    echo "Status do pedido atualizado com sucesso.";
} else {
    echo "Erro ao atualizar o status do pedido.";
}
?>
