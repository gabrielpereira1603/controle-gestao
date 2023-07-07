<?php
include("conexao.php");

// Verifique se o campo nome_produto não está vazio no array $_POST
if (!empty($_POST['nome_produto'])) {
    $nomeProduto = trim($_POST['nome_produto']);

    // Prepare a consulta SQL para buscar os preços com base no nome do produto
    $sql = "SELECT preco_custo, preco_venda FROM produto WHERE nome_produto = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('s', $nomeProduto);
    $stmt->execute();
    $stmt->bind_result($precoCusto, $precoVenda);
    $stmt->fetch();
    $stmt->close();

    // Verifique se os preços foram encontrados
    if ($precoCusto !== null && $precoVenda !== null) {
        // Construa um array com os valores de preço de custo e preço de venda
        $response = [
            'success' => true,
            'preco_custo' => $precoCusto,
            'preco_venda' => $precoVenda
        ];
    } else {
        $response = [
            'success' => false
        ];
    }

    // Converta o array em JSON e envie a resposta
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    $response = [
        'success' => false
    ];

    // Converta o array em JSON e envie a resposta
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
