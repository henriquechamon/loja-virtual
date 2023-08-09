
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

foreach ($productsData['products'] as $product) {
    $img = $product['image'];
    $name = $product['name'];
    $description = $product['description'];
    $price = $product['price'];
    $id = $product['id'];

    $inCart = false;
    $query = $connection->prepare("SELECT * FROM carrinho WHERE email = ? AND itens = ?");
    $query->execute([$email, $id]);
    if ($query->rowCount() > 0) {
        $inCart = true;
        $total += $price; 
    }

    if ($inCart) {
        $resultado .= "
        <form method='POST'>
        <div class='cart-item'>
                <img src='$img' alt='$name'>
                <div class='cart-item-details'>
                    <p class='cart-item-title'>$name</p>
                    <p class='cart-item-price'>R$ $price</p>
                </div>
                <button class='rem_cart' name='rem_cart' type='button' value='$id' style='background-color: red; color: white'>Remover</button>
            </div></form>
            ";
    }
}

$buttonKo = "";
if (empty($resultado)) {
    $buttonKo = "<button style='background-color: blue; color: white; padding: 10px; border: none; cursor: pointer;' onclick=\"window.location.href = 'Dashboard.php'\">Voltar</button>";
    $resultado = "Nenhum item no carrinho.";
} else {
    $buttonKo = "<form method='POST'><button type='submit' name='continue' class='continue' style='background-color: blue; color: white; padding: 10px; border: none; cursor: pointer;'>Continuar para o pagamento</button></form>";
}

if (isset($_POST['rem_cart'])) {
    $product_id = $_POST['rem_cart'];

    $deleteQuery = $connection->prepare("DELETE FROM carrinho WHERE email = ? AND itens = ?");
    $deleteQuery->bindParam(1, $email);
    $deleteQuery->bindParam(2, $product_id);

    if ($deleteQuery->execute()) {
        echo json_encode(["success" => true, "message" => "Produto removido do carrinho com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao remover o produto do carrinho"]);
    }
    exit();
}

if (isset($_POST['continue'])) {
        $dumbo = rand(1, 999999);
        $status = 0; 
        $query = $connection->prepare("INSERT INTO pedidos (email, status, id, valor) VALUES (?, ?, ?, ?)");
        $query->bindParam(1, $email);
        $query->bindParam(2, $status);
        $query->bindParam(3, $dumbo);
        $query->bindParam(4, $total);

        if ($query->execute()) {
            echo json_encode(["success" => true, "message" => "Redirecionando..."]);
            header("Location: FinishCart.php?id=$dumbo");
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao inserir o pedido"]);
        }
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Pagamento</title>
    <link rel="stylesheet" href="css/Checkout.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/jQuery2.js"></script>
</head>
<body>
    <div class="container">
        <h1>Seu carrinho</h1>
      <?php echo $resultado ?>
      <?php echo $buttonKo ?>

        <div class="cart-total">
            Total: <strong>R$ <?php echo $total ?></strong>
        </div>

    </div>
</body>
</html>
