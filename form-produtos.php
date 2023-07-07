<?php
session_start();
include("conexao.php");

if (isset($_POST['cadastrar-fornecedor'])) {
    // Verifica se todos os campos foram preenchidos
    if (
        empty($_POST['nome_fornecedor']) ||
        empty($_POST['telefone_fornecedor']) ||
        empty($_POST['rua_fornecedor']) ||
        empty($_POST['bairro_fornecedor']) ||
        empty($_POST['numero_fornecedor']) ||
        empty($_POST['cep_fornecedor']) ||
        empty($_POST['cidade_fornecedor'])
    ) {
        $_SESSION['error_message'] = 'Preencha todos os campos para cadastrar o fornecedor.';
        header('Location: form-produtos.php');
        exit;
    }

    // Obtém os valores dos campos do formulário
    $nomeFornecedor = $_POST['nome_fornecedor'];
    $telefoneFornecedor = $_POST['telefone_fornecedor'];
    $ruaFornecedor = $_POST['rua_fornecedor'];
    $bairroFornecedor = $_POST['bairro_fornecedor'];
    $numeroFornecedor = $_POST['numero_fornecedor'];
    $cepFornecedor = $_POST['cep_fornecedor'];
    $cidadeFornecedor = $_POST['cidade_fornecedor'];

    // Insere os dados na tabela "endereco"
    $sqlEndereco = "INSERT INTO endereco (nomerua, nomebairro, numero, nomecidade, cep) VALUES (?, ?, ?, ?, ?)";
    $stmtEndereco = $conexao->prepare($sqlEndereco);
    $stmtEndereco->bind_param("ssiss", $ruaFornecedor, $bairroFornecedor, $numeroFornecedor, $cidadeFornecedor, $cepFornecedor);
    $stmtEndereco->execute();
    $enderecoId = $stmtEndereco->insert_id; // ID do endereço inserido
    $stmtEndereco->close();

    // Insere os dados na tabela "fornecedor" com a referência ao ID do endereço
    $sqlFornecedor = "INSERT INTO fornecedor (nome_fornecedor, telefone_fornecedor, codendereco) VALUES (?, ?, ?)";
    $stmtFornecedor = $conexao->prepare($sqlFornecedor);
    $stmtFornecedor->bind_param("ssi", $nomeFornecedor, $telefoneFornecedor, $enderecoId);
    $stmtFornecedor->execute();
    $stmtFornecedor->close();

    $_SESSION['success_message'] = 'Fornecedor cadastrado com sucesso!';
    header('Location: produtos.php');
    exit;
}

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

// Verifica se o formulário foi submetido
if (isset($_POST['cadastrar-produto'])) {
    $nomeProduto = strtoupper($_POST["nome_produto"]);
    $precoCusto = $_POST["preco_custo"];
    $precoVenda = $_POST["preco_venda"];
    $codTipo = $_POST["tipo_produto"];
    $nomeMarca = strtoupper($_POST["marca_produto"]);
    $codFornecedor = $_POST["fornecedor_produto"];
    $quantidade = $_POST["quantidade"];

    // Cálculo da margem de lucro
    $margemLucro = ($precoVenda - $precoCusto);

    // Obtém o código da marca
    $codMarca = obterCodigoMarca($nomeMarca, $conexao);

    // Verifica se o produto já existe na tabela
    $sql = "SELECT codproduto, quantidade FROM produto WHERE nome_produto = ? AND codtipo = ? AND codmarca = ? AND codfornecedor = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("siii", $nomeProduto, $codTipo, $codMarca, $codFornecedor);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // O produto já existe, atualiza a quantidade
        $stmt->bind_result($codProduto, $quantidadeExistente);
        $stmt->fetch();
        $stmt->close();

        $novaQuantidade = $quantidadeExistente + $quantidade;

        // Atualiza a quantidade do produto existente
        $sql = "UPDATE produto SET quantidade = ? WHERE codproduto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $novaQuantidade, $codProduto);
        $stmt->execute();
        $stmt->close();

        if ($conexao->affected_rows > 0) {
            $_SESSION['success_message'] = 'Quantidade do produto atualizada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar a quantidade do produto.';
        }
    } else {
        // O produto não existe, insere um novo produto
        $sql = "INSERT INTO produto (nome_produto, preco_venda, preco_custo, margem_lucro, codtipo, codmarca, codfornecedor, quantidade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssiidi", $nomeProduto, $precoVenda, $precoCusto, $margemLucro, $codTipo, $codMarca, $codFornecedor, $quantidade);
        $stmt->execute();
        $stmt->close();

        if ($conexao->affected_rows > 0) {
            $_SESSION['error_message'] = 'Erro ao cadastrar o produto.';
        } else {
            $_SESSION['success_message'] = 'Produto cadastrado com sucesso!';
        }
    }

    // Redireciona de volta para a página onde o formulário foi submetido
    header('Location: produtos.php');
    exit;
}
