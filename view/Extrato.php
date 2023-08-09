
<?php
    require '../controllers/VendasController.php';  //VENDAS CONTROLLER
    require '../controllers/DataController.php'; // DATA CONTROLLER
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

    $result = SellController($email);

    if (count($result) > 0) {
        
        foreach ($result as $order) {
            $content = '<tr>';
            $content .= '<td>' . $order['id'] . '</td>';
            $content .= '<td>' . ($order['status'] == 1 ? 'Aprovado' : 'Pendente') . '</td>';
            $content .= '<td>R$ ' . number_format($order['valor'], 2, ',', '.') . '</td>';
            $content .= '</tr>';
        }

    } else {
        $content = '<p>Não há compras no extrato.</p>';
    }
    ?>
<!DOCTYPE html>
<html>
    <title>Extrato</title>
<head>
    <style>
        /* Estilos do CSS */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f7ff;
        }

        .navbar {
            background-color: #007bff;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Estilos para a tabela de compras */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .products-table th, .products-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .products-table th {
            background-color: #f9f9f9;
        }

        .products-table th:first-child, .products-table td:first-child {
            text-align: left;
        }
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgb(150,10,20);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Extrato de Compras</h1>
        <a href="Dashboard.php"><button class="logout-button">Retornar</button></a>
    </div>
    
    <table class="products-table">
        <tr>
            <th>ID do Pedido</th>
            <th>Status</th>
            <th>Valor</th>
        </tr>
        <?php echo $content ?>
    </table>
</body>
</html>
