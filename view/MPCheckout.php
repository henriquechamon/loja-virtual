<?php
require '../vendor/autoload.php';
$connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");
$id = $_GET['id'];
$query = $connection->prepare("SELECT * FROM pedidos WHERE id = ?");
$query->execute([$id]);
$result = $query->fetch(PDO::FETCH_OBJ);
$valor = $result->valor;
$email = $result->email;
$query = $connection->prepare("SELECT * FROM conta WHERE email = ?");
$query->execute([$email]);
$result = $query->fetch(PDO::FETCH_OBJ);
$nome = $result->nome;



MercadoPago\SDK::setAccessToken("YOUR_KEY");
$acessToken = "YOUR_KEY";




$payment = new MercadoPago\Payment();
$payment->transaction_amount = $valor;
$payment->description = "$nome | Produto loja dumbo";
$payment->payer = array(
    "email" => "emailproduto@gmail.com"
);
$payment->payment_method_id = "pix";

$payment->save();


$copia_e_cola = $payment->point_of_interaction->transaction_data->qr_code;
$img_qrcode = 'data:image/png;base64, '.$payment->point_of_interaction->transaction_data->qr_code_base64;
$status = $payment->status;
$idpagamento = $payment->id;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f7ff;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .payment-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .qr-code {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Pagamento PIX</h1>
        <p>Escaneie o QR Code abaixo para efetuar o pagamento:</p>
        <div class="qr-code">
            <img width="400" src="<?php echo $img_qrcode ?>" alt="QR Code PIX">
        </div>
    </div>
    <script>
  function verificarPagamento() {
    var xmlhttp = new XMLHttpRequest();
    var url = "https://api.mercadopago.com/v1/payments/<?= $idpagamento ?>?access_token=<?= $acessToken ?>";
    xmlhttp.open("GET", url, true);
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var response = JSON.parse(xmlhttp.responseText);
        console.log("Status da resposta: " + response.status);
        if (response.status == "approved") {
          atualizarStatusPedido(<?= $id ?>); 
          Swal.fire({
  icon: 'success',
  title: 'Sucesso!',
  text: 'Pagamento registrado. Seus itens serão enviados em breve! 📦'
}).then((result) => {
  if (result.isConfirmed) {
    window.location.href = 'Dashboard.php'; 
  }
});

        } else {
          setTimeout(verificarPagamento, 3000);
        }
      } else if (xmlhttp.readyState == 4 && xmlhttp.status != 200) {
        console.log("Error: " + xmlhttp.statusText);
        setTimeout(verificarPagamento, 3000);
      }
    };
    xmlhttp.send();
  }

  function atualizarStatusPedido(id) {
    var xhr = new XMLHttpRequest();
    var url = "../controllers/StatusController.php?id=" + id; 
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        console.log("Status do pedido atualizado com sucesso.");
      }
    };
    xhr.send();
  }

  verificarPagamento();
</script>
</body>
</html>
