<?php
function HandlerView($route) {
    $viewExtensions = ['php', 'html'];
    $pageName = rtrim($route, '/');
    $found = false;

    foreach ($viewExtensions as $extension) {
        $pagePath = "view/{$pageName}.{$extension}";
        if (file_exists($pagePath)) {
            include $pagePath;
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "Página não encontrada.";
    }
}
function HandlerPage() {
   header("Location: ./view/HomeBoard.php");
}
function DashboardPage() {
    header("Location: ../view/Dashboard.php");
 }
 function FinishCart() {
    header("Location: ../view/FinishCart.php");
 }
?>

