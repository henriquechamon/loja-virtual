<?php
require 'db/connection.php'; 
require 'handlers/HandlerView.php';


DatabaseConnection(); 

function AliggPrint($message) {
    echo $message;
}

AliggPrint("Sucesso:\n\nConexão com o banco de dados feita com sucesso!\nPáginas view renderizadas!");
HandlerPage()
?>
