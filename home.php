<?php
// Define o tempo limite de inatividade da sessão em 30 minutos
$session_timeout = 1800; // 1800 30 minutos em segundos
session_set_cookie_params($session_timeout);

// Inicia a sessão
session_start();

// Verifica se a variável de sessão 'autenticado' está definida
if (!isset($_SESSION['autenticado']) || !$_SESSION['autenticado']) {
    // Redireciona o usuário de volta para a página de login
    header("Location: login.php");
    exit;
}

// Verifica se o tempo de inatividade da sessão expirou
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Destroi a sessão atual
    session_unset();
    session_destroy();
    // Redireciona o usuário de volta para a página de login
    header("Location: login.php");
    exit;
}

// Atualiza o último tempo de atividade da sessão
$_SESSION['last_activity'] = time();

include("conexao.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <title>Somos Dev's</title>
</head>
<body style="background-color: #e2e8f0;">
    <?php
        include("cabecario.php");
    ?>

    <section class="main">
        <div class="title">
            <h1>Gestão De Negócios</h1>
            <p>Sistema voltado para empreendedores, visando o maior controle da sua empresa.</p>
        </div>

        <!-- Exibe a mensagem de error -->
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" style="text-aling:center;">
                <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']); 
                ?>
            </div>
        <?php endif; ?>

        <!-- Exibe a mensagem de sucesso -->
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" style="text-aling:center;">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']); 
                ?>
            </div>
        <?php endif; ?>

        <div class="accordions">
            
            <div class="accordion accordion-flush custom-accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <i class="bi bi-currency-dollar"></i> Registro De Despesas
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body custom-accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                    </div>
                </div>
            </div>

            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <i class="bi bi-box-seam"></i> Produtos em falta
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <?php
                            // Recupere os produtos cuja quantidade é menor que 50
                            $sql = "SELECT p.*, tp.nome_tipo, f.nome_fornecedor, f.telefone_fornecedor, m.nome_marca
                            FROM produto p
                            JOIN tipoproduto tp ON p.codtipo = tp.codtipoproduto
                            JOIN fornecedor f ON p.codfornecedor = f.codfornecedor
                            JOIN marca m ON p.codmarca = m.codmarca
                            WHERE p.quantidade < 50";
                            $resultado = $conexao->query($sql);

                            if ($resultado->num_rows > 0) {
                                echo '<div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID Produto</th>
                                                    <th>Nome Produto</th>
                                                    <th>Preço Venda</th>
                                                    <th>Preço Custo</th>
                                                    <th>Margem Lucro</th>
                                                    <th>Tipo</th>
                                                    <th>Marca</th>
                                                    <th>Fornecedor</th>
                                                    <th>Quantidade</th>
                                                    <th>Pedir Mais</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                while ($row = $resultado->fetch_assoc()) {
                                    $idProduto = $row['codproduto'];
                                    $nomeProduto = $row['nome_produto'];
                                    $precoVenda = $row['preco_venda'];
                                    $precoCusto = $row['preco_custo'];
                                    $margemLucro = $row['margem_lucro'];
                                    $tipoProduto = $row['nome_tipo'];
                                    $marcaProduto = $row['nome_marca'];
                                    $fornecedorProduto = $row['nome_fornecedor'];
                                    $quantidade = $row['quantidade'];

                                    echo "<tr>
                                            <td>$idProduto</td>
                                            <td>$nomeProduto</td>
                                            <td>$precoVenda</td>
                                            <td>$precoCusto</td>
                                            <td>$margemLucro</td>
                                            <td>$tipoProduto</td>
                                            <td>$marcaProduto</td>
                                            <td>$fornecedorProduto</td>
                                            <td>$quantidade</td>
                                            <td>
                                                <a href='produtos.php'><i class='bi bi-plus-circle-fill'></i></a>
                                            </td>
                                        </tr>";
                                }

                                echo '</tbody>
                                    </table>
                                    </div>';
                            } else {
                                echo "Nenhum produto em falta encontrado.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTree" aria-expanded="false" aria-controls="flush-collapseTree">
                            <i class="bi bi-person-lines-fill"></i> Fornecedores
                        </button>
                    </h2>
                    <div id="flush-collapseTree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Telefone</th>
                                            <th>Endereço</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Recupere os dados dos fornecedores e seus respectivos endereços do banco de dados
                                        $sql = "SELECT f.*, e.* FROM fornecedor f INNER JOIN endereco e ON f.codendereco = e.codendereco";
                                        $resultado = $conexao->query($sql);

                                        if ($resultado->num_rows > 0) {
                                            while ($row = $resultado->fetch_assoc()) {
                                                $nomeFornecedor = $row['nome_fornecedor'];
                                                $telefoneFornecedor = $row['telefone_fornecedor'];
                                                $nomeRua = $row['nomerua'];
                                                $nomeBairro = $row['nomebairro'];
                                                $numero = $row['numero'];
                                                $nomeCidade = $row['nomecidade'];
                                                $cep = $row['cep'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $nomeFornecedor; ?></td>
                                                    <td><?php echo $telefoneFornecedor; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $nomeRua . ", " . $numero . "<br>";
                                                        echo $nomeBairro . "<br>";
                                                        echo $nomeCidade . " - CEP: " . $cep;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>Nenhum fornecedor encontrado.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>

        </div>
            
       
     
    </section>
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>