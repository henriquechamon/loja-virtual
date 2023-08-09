<?php
require '../controllers/DataController.php';
require '../controllers/LoginController.php';
require '../db/connection.php';

checkValidLogin();
$connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");
$controllersData = $_COOKIE["controllers_data"];
GetData($controllersData);
$jsonFile = file_get_contents('user_data.json');
$userData = json_decode($jsonFile, true);

if ($userData !== null) {
    $nome = $userData["nome"];
    $email = $userData["email"];
} else {
    echo "Erro ao carregar dados do usuário.";
}

$jsonData = file_get_contents('../products/products.json');
$productsData = json_decode($jsonData, true);

$resultado = '';
$total = 0;
$idpedido = $_GET['id'];

if(isset($_POST['submit_info'])) {
    $cpf = addslashes($_POST['cpf']);
    $endereco = addslashes($_POST['endereco']);
    $cidade = addslashes($_POST['cidade']);
    $estado = addslashes($_POST['estado']);

    $query = $connection->prepare("UPDATE pedidos 
    SET endereco = ?, cidade = ?, estado = ?, cpf = ? 
    WHERE id = ?");
    $query->bindParam(1, $endereco);
    $query->bindParam(2, $cidade);
    $query->bindParam(3, $estado);
    $query->bindParam(4, $cpf);
    $query->bindParam(5, $idpedido);

    if ($query->execute()) {
        header("Location: MPCheckout.php?id=$idpedido");
        echo json_encode(["success" => true, "message" => "Dados atualizados com sucesso", "last_updated_id" => $lastUpdatedId]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar os dados"]);
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Carrinho de Compras</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="./css/Checkout.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .cart-total {
            font-size: 18px;
            margin-top: 20px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-container label {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 16px;
            color: #888;
            transition: 0.2s ease-out;
            pointer-events: none;
        }


        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label {
            top: -12px;
            left: 5px;
            font-size: 12px;
            background-color: white;
            padding: 0 5px;
        }
    </style>
</head>

<body>
    <form method="POST">
    <div class="container">
        <h1>Suas informações</h1>
        <div class="input-container">
            <input name="cpf" type="text" id="cpf" required>
            <label for="cpf">CPF</label>
        </div>
        <div class="input-container">
            <input name="cidade" type="text" id="text" required>
            <label for="text">Cidade</label>
        </div>
        <div class="input-container">
            <input name="estado" type="text" id="text" required>
            <label for="text">Estado</label>
        </div>
        <div class="input-container">
            <input name="endereco" type="text" id="text" required>
            <label for="text">Endereço</label>
        </div>

        <button type="submit" name="submit_info" style='background-color: blue; color: white; padding: 10px; border: none; cursor: pointer;'>Pagar via PIX</button>
       <a href="Checkout.php"> <button type='button' style='background-color: red; color: white; padding: 10px; border: none; cursor: pointer;'>Cancelar</button></a>
    </button>

    </div>
</body>

</html>
