<?php
include("conexao.php");
// Verifique se foi fornecido um ID de produto válido
if (isset($_GET['id'])) {
    $idProduto = $_GET['id'];

    // Realize a consulta SQL para obter os dados do produto com base no ID
    // Substitua os nomes das tabelas e colunas conforme sua estrutura de banco de dados

    // Consulta para obter os dados do produto, incluindo os nomes de tipo, marca e fornecedor
    $sql = "SELECT p.nome_produto, tp.nome_tipo, m.nome_marca, f.nome_fornecedor, p.preco_custo, p.preco_venda, p.margem_lucro, p.quantidade
            FROM produto p
            INNER JOIN tipoproduto tp ON p.codtipo = tp.codtipoproduto
            INNER JOIN marca m ON p.codmarca = m.codmarca
            INNER JOIN fornecedor f ON p.codfornecedor = f.codfornecedor
            WHERE p.codproduto = $idProduto";

    // Execute a consulta
    $resultado = $conexao->query($sql);

    // Verifique se a consulta foi bem-sucedida e se retornou algum resultado
    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        // Construa um array com as informações do produto
        $response = [
            'success' => true,
            'nome_produto' => $row['nome_produto'],
            'nome_tipo' => $row['nome_tipo'],
            'nome_marca' => $row['nome_marca'],
            'nome_fornecedor' => $row['nome_fornecedor'],
            'preco_custo' => $row['preco_custo'],
            'preco_venda' => $row['preco_venda'],
            'margem_lucro' => $row['margem_lucro'],
            'quantidade' => $row['quantidade']
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Não foi possível encontrar o produto'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'ID de produto inválido'
    ];
}

// Converta o array em JSON e envie a resposta
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
