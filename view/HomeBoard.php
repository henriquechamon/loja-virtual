<?php
require '../controllers/LoginController.php';

if (isset($_COOKIE["controllers_data"])) {
    $controllersData = $_COOKIE["controllers_data"];
    if (!empty($controllersData)) {
        $connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");

        $query = $connection->query("SELECT * FROM conta WHERE `id` = '$controllersData'");
        $result = $query->fetch(PDO::FETCH_OBJ);

        if (!$result) {
        } else {
            header("Location: ../view/Dashboard.php");
        }
    } else {
    }
} else {
}

if (isset($_POST['submit_LOGIN'])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        getemailGetPass($email,$password);
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <div class="login-container">
        <h2 class="login-heading">Login - Dumbo Store</h2>
        <form action="" method="post">
            <input type="text" class="login-input" name="email" placeholder="User ou E-mail" required>
            <input type="password" class="login-input" md5 name="password" placeholder="Senha" required>
            <button  type="submit" name="submit_LOGIN" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
