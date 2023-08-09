<?php
function DatabaseConnection() {
    return new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");
}
?>
