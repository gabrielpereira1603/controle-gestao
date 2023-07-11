<?php
session_start();
include_once "conexao.php";

// Verifica se o ID do produto foi fornecido na URL
if (isset($_GET['id'])) {
    $codproduto = $_GET['id'];

    // Cria a consulta SQL para excluir o produto
    $sql = "DELETE FROM produto WHERE codproduto = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codproduto);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao excluir o produto: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
} else {
    $_SESSION['error_message'] = "ID do produto não fornecido!";
}

// Redireciona de volta para a página anterior
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
