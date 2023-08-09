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

foreach ($productsData['products'] as $product) {
    $img = $product['image'];
    $name = $product['name'];
    $description = $product['description'];
    $price = $product['price'];
    $id = $product['id'];

    $resultado .= "
    <form method='POST'>
    <li class=\"product\">
        <img width='200' src=\"$img\" alt=\"$name\">
        <h3>$name</h3>
        <p>$description</p>
        <p><b>Preço</b>: R$ $price</p>
        <button class='buy-button' type='button' id='buy' name='buy' value='$id'>Adicionar ao carrinho</button>
    </li></form>";
}

if (isset($_POST['product_id'])) { 
    $product_id = $_POST['product_id'];
    

    $checkQuery = $connection->prepare("SELECT * FROM carrinho WHERE `email` = '$email' AND `itens` = '$product_id'");
    $checkQuery->execute();
    $existingProduct = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if (!$existingProduct) {
        $insertQuery = $connection->prepare("INSERT INTO carrinho (name, email, itens) VALUES (?, ?, ?)");
        $insertQuery->bindValue(1, $nome);
        $insertQuery->bindValue(2, $email);
        $insertQuery->bindValue(3, $product_id);

        if ($insertQuery->execute()) {
            echo json_encode(["success" => true, "message" => "Produto adicionado ao carrinho com sucesso"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao adicionar o produto ao carrinho"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Produto já está no carrinho"]);
    }

    exit();
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/jQuery.js"></script>

    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f7ff;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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

        .profile {
            display: flex;
            align-items: center;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile p {
            margin: 0;
        }

        .main {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .products {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .product img {
            max-width: 100%;
            height: auto;
        }

        .product h3 {
            margin-top: 10px;
        }

        .product p {
            margin-top: 5px;
            color: #666;
        }
        .buy-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buy-button:hover {
            background-color: #0056b3;
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
    <div class="header">
        <h1>Dashboard</h1>
       <button type="button" class="logout-button" onclick="Logout()" style="color:white">🚪Fazer log-out</button>
       <button type="button" class="logout-button" style="background-color: rgb(10,50,200)" onclick="Extrato()" style="color:white">💳Extrato</button>
       <button type="button" class="logout-button" style="background-color: green" onclick="Carrinho()" style="color:white">🛒Meu carrinho</button>
    </div>
    <div class="navbar">
        <div class="profile">
            <img src="https://placekitten.com/40/40" alt="Profile">
            <p><?php echo $nome ?></p>
        </div>
    </div>
    <div class="main">
    <h2>Produtos da Dumbo Store:</h2>
    <ul class="products">
        <?php echo $resultado; ?>
    </ul>
</div>
<script>
    function Logout(){
let timerInterval
Swal.fire({
  title: 'Fazendo log-out...',
  html: 'Aguarde alguns segundos...',
  timer: 2000,
  timerProgressBar: true,
  didOpen: () => {
    Swal.showLoading()
    const b = Swal.getHtmlContainer().querySelector('b')
    timerInterval = setInterval(() => {
      b.textContent = Swal.getTimerLeft()
    }, 100)
  },
  willClose: () => {
    clearInterval(timerInterval)
  }
}).then((result) => {
  if (result.dismiss === Swal.DismissReason.timer) {
    window.location.href = "Logout.php"
  }
})
    }
    </script>
    <script>
    function Carrinho(){
let timerInterval
Swal.fire({
  title: 'Abrindo carrinho...',
  html: 'Aguarde alguns segundos...',
  timer: 2000,
  timerProgressBar: true,
  didOpen: () => {
    Swal.showLoading()
    const b = Swal.getHtmlContainer().querySelector('b')
    timerInterval = setInterval(() => {
      b.textContent = Swal.getTimerLeft()
    }, 100)
  },
  willClose: () => {
    clearInterval(timerInterval)
  }
}).then((result) => {
  if (result.dismiss === Swal.DismissReason.timer) {
    window.location.href = "Checkout.php"
  }
})
    }
    </script>
    <script>
    function Extrato(){
let timerInterval
Swal.fire({
  title: 'Abrindo extrato...',
  html: 'Aguarde alguns segundos...',
  timer: 2000,
  timerProgressBar: true,
  didOpen: () => {
    Swal.showLoading()
    const b = Swal.getHtmlContainer().querySelector('b')
    timerInterval = setInterval(() => {
      b.textContent = Swal.getTimerLeft()
    }, 100)
  },
  willClose: () => {
    clearInterval(timerInterval)
  }
}).then((result) => {
  if (result.dismiss === Swal.DismissReason.timer) {
    window.location.href = "Extrato.php"
  }
})
    }
    </script>
    
        
</body>


</html>

