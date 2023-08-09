<?php

require'../handlers/HandlerView.php'; 




function getemailGetPass($email, $pass) {

        $connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");
        $query = $connection->query("SELECT * FROM conta WHERE `email` = '$email' AND `senha` = '$pass'");
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $UserID = $result->id;
            setcookie("controllers_data", $UserID, time() + (86400 * 30), "/"); // O cookie expira em 30 dias
        DashboardPage();
        } else {
           echo"<script>alert('Credenciais inválidas')</script>";
        }
     
    }

function checkValidLogin(){
    if (isset($_COOKIE["controllers_data"])) {
        $controllersData = $_COOKIE["controllers_data"];
        if (!empty($controllersData)) {
            $connection = new PDO("mysql:host=localhost;dbname=lojavirtual", "root", "");

            $query = $connection->query("SELECT * FROM conta WHERE `id` = '$controllersData'");
            $result = $query->fetch(PDO::FETCH_OBJ);

            if (!$result) {
                header("Location: ../default.php");
            }
        } else {
            header("Location: ./view/Dashboard.php");
        }
    } else {
        header("Location: ../default.php");
    }
}
function Logout($url){
    setcookie("controllers_data", "", time() - 3600, "/");
    header("Location: $url");
}

?>
