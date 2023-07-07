<?php
session_start();

// Destrua todas as variáveis de sessão
session_unset();

// Destrua a sessão
session_destroy();

// Redirecione o usuário para a página de login ou para outra página desejada
header('Location: login.php'); // Substitua "login.php" pelo caminho da página desejada
exit();
?>