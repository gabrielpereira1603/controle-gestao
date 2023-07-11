<?php
session_start();
include("conexao.php");
// Função para obter o código da marca
function obterCodigoMarca($nomeMarca, $conexao) {
    // Verifica se a marca já está cadastrada
    $sql = "SELECT codmarca FROM marca WHERE nome_marca = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $nomeMarca);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // A marca já existe, retorna o código da marca existente
        $stmt->bind_result($codMarca);
        $stmt->fetch();
        $stmt->close();
        return $codMarca;
    } else {
        // A marca não existe, cria uma nova marca e retorna o código da nova marca
        $sql = "INSERT INTO marca (nome_marca) VALUES (?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $nomeMarca);
        $stmt->execute();
        $codMarca = $conexao->insert_id;
        $stmt->close();
        return $codMarca;
    }
}

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha o valor do codproduto do campo oculto
    $codproduto = $_POST['codproduto'];

    // Obtenha os valores dos outros campos
    $nome_produto = $_POST['nome_produto'];
    $tipo_produto = $_POST['tipo_produto'];
    $marca_produto = $_POST['marca_produto'];
    $fornecedor_produto = $_POST['fornecedor_produto'];
    $preco_custo = $_POST['preco_custo'];
    $preco_venda = $_POST['preco_venda'];
    $margem_lucro = $_POST['margem-lucro'];
    $quantidade = $_POST['quantidade'];

    // Realize as operações de atualização no banco de dados

    // 1. Verificar e atualizar o campo nome_produto na tabela produto
    if ($nome_produto != "") {
        // Atualizar o campo nome_produto
        $sql = "UPDATE produto SET nome_produto = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $nome_produto, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 2. Verificar e atualizar o campo codtipo na tabela produto
    if ($tipo_produto != "") {
        // Atualizar o campo codtipo
        $sql = "UPDATE produto SET codtipo = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $tipo_produto, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 3. Verificar e atualizar o campo codmarca na tabela produto
    if ($marca_produto != "") {
        // Obter o código da marca
        $codmarca = obterCodigoMarca($marca_produto, $conexao);

        // Atualizar o campo codmarca
        $sql = "UPDATE produto SET codmarca = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $codmarca, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 4. Verificar e atualizar o campo codfornecedor na tabela produto
    if ($fornecedor_produto != "") {
        // Atualizar o campo codfornecedor
        $sql = "UPDATE produto SET codfornecedor = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $fornecedor_produto, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 5. Verificar e atualizar o campo preco_custo na tabela produto
    if ($preco_custo != "") {
        // Atualizar o campo preco_custo
        $sql = "UPDATE produto SET preco_custo = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("di", $preco_custo, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 6. Verificar e atualizar o campo preco_venda na tabela produto
    if ($preco_venda != "") {
        // Atualizar o campo preco_venda
        $sql = "UPDATE produto SET preco_venda = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("di", $preco_venda, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 7. Verificar e atualizar o campo margem_lucro na tabela produto
    if ($preco_venda != "" && $preco_custo != "") {
        // Calcular a margem de lucro
        $margem_lucro = ($preco_venda - $preco_custo);

        // Atualizar o campo margem_lucro
        $sql = "UPDATE produto SET margem_lucro = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("di", $margem_lucro, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }

    // 8. Verificar e atualizar o campo quantidade na tabela produto
    if ($quantidade != "") {
        // Atualizar o campo quantidade
        $sql = "UPDATE produto SET quantidade = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $quantidade, $codproduto);
        if (!$stmt->execute()) {
            echo "Erro na atualização do produto: " . $stmt->error;
            exit();
        }
        $stmt->close();
    }
    // Após concluir as operações de atualização, defina a mensagem de sucesso na sessão
    $_SESSION['success_message'] = "Produto atualizado com sucesso!";

    // Após concluir as operações de atualização, redirecione o usuário para uma página de sucesso ou exiba uma mensagem de sucesso
    header("Location: setting.php");
    exit();
}
?>
