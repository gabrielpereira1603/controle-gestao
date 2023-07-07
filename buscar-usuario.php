<?php
include("conexao.php");
// buscar-usuario.php

// Realize a consulta no banco de dados para obter as informações do usuário com base no ID fornecido
if (isset($_GET['codusuario'])) {
    $userId = $_GET['codusuario'];

    // Consulta para buscar as informações do usuário com base no ID
    // Substitua essa consulta pelo seu código para buscar as informações do usuário no banco de dados
    $sql = "SELECT usuario.codusuario, usuario.nome_usuario, usuario.email, cargo.nomecargo FROM usuario INNER JOIN cargo ON usuario.codcargo = cargo.codcargo WHERE usuario.codusuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Retorne as informações do usuário em formato JSON
    echo json_encode($user);
    exit;
}
?>
