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
require_once 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/produtos.css">
        <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
        <title>Somos Dev's</title>
    </head>
    <body style="background-color: #e2e8f0;">
        <?php
            include("cabecario.php");
        ?>

        <section class="main">
            <div class="title">
                <h1>Entrada De Produtos</h1>
            </div>
            <form action="form-produtos.php" method="post">
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

                <div class="row">
                    <h4>Cadastro De Produtos:</h4>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome_produto" class="form-label">Nome Produto:</label>
                            <input type="text" name="nome_produto" class="form-control" id="nome_produto" onchange="preencherPrecos()">
                        </div>
                    </div>
                    <?php
                        // Consulta para obter os cargos da tabela 'cargo'
                        $sql = "SELECT codtipoproduto, nome_tipo FROM tipoproduto";
                        $resultado = $conexao->query($sql);

                        // Verifique se há registros retornados
                        if ($resultado->num_rows > 0) {
                            echo '<div class="col-md-6">';
                            echo '<div class="mb-3">';
                            echo '<label for="tipo_produto" class="form-label">Tipo Do Produto:</label>';
                            echo '<select class="form-select" name="tipo_produto" aria-label="Default select example">';
                            echo '<option selected>Selecione o tipo:</option>';

                            // Itere sobre os registros e crie as opções no select
                            while ($row = $resultado->fetch_assoc()) {
                                $codtipo = $row["codtipoproduto"];
                                $nometipo = $row["nome_tipo"];
                                echo "<option value='$codtipo'>$nometipo</option>";
                            }

                            echo '</select>';
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca_produto" class="form-label">Marca Do Produto:</label>
                                    <input type="text" name="marca_produto" class="form-control" id="marca_produto">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo_barras" class="form-label">Código de Barras:</label>
                                    <input type="text" name="codigo_barras" class="form-control" id="codigo_barras">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fornecedor_produto" class="form-label">Fornecedor:</label>
                            <select class="form-select" name="fornecedor_produto" id="fornecedor_produto">
                                <option value="">Selecione um fornecedor</option>
                                <?php
                                    $sql = "SELECT * FROM listar_fornecedores";
                                    $resultado = $conexao->query($sql);

                                    if ($resultado->num_rows > 0) {
                                        while ($row = $resultado->fetch_assoc()) {
                                            $codfornecedor = $row['codfornecedor'];
                                            $nomefornecedor = $row['nome_fornecedor'];
                                            echo "<option value='$codfornecedor'>$nomefornecedor</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="preco_custo" class="form-label">Preço de Custo:</label>
                            <input type="number" name="preco_custo" class="form-control preco-input" id="preco_custo" step="0.01" min="0" max="99999.99" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="preco_venda" class="form-label">Preço de Venda:</label>
                            <input type="number" name="preco_venda" class="form-control preco-input" id="preco_venda" step="0.01" min="0" max="99999.99" placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="margem-lucro" class="form-label">Margem de Lucro:</label>
                                    <input type="number" name="margem-lucro" class="form-control" id="margem-lucro" step="0.01" min="0" max="99999.99" placeholder="0.00" readonly>
                                </div>
                                <canvas id="barcode"></canvas>
                                <input type="hidden" name="codigo_barras_image" id="codigo_barras_image" value="">
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantidade" class="form-label">Quantidade:</label>
                                    <input type="number" name="quantidade" class="form-control" id="quantidade">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-grid mx-auto" style="margin-top: 32px;">
                                <button class="btn btn-primary" name="cadastrar-produto" type="submit">Cadastrar Produto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            
            <form action="form-produtos.php" method="post">
                <div class="row">
                    <h4>Cadastro De Fornecedores:</h4>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome_fornecedor" class="form-label">Nome do Fornecedor:</label>
                            <input type="text" name="nome_fornecedor" class="form-control" id="nome_fornecedor">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefone_fornecedor" class="form-label">Telefone do Fornecedor:</label>
                            <input type="number" name="telefone_fornecedor" class="form-control" id="telefone_fornecedor">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rua_fornecedor" class="form-label">Rua do Fornecedor:</label>
                            <input type="text" name="rua_fornecedor" class="form-control" id="rua_fornecedor">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bairro_fornecedor" class="form-label">Bairro do Fornecedor:</label>
                            <input type="text" name="bairro_fornecedor" class="form-control" id="bairro_fornecedor">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="numero_fornecedor" class="form-label">Numero do Fornecedor:</label>
                            <input type="text" name="numero_fornecedor" class="form-control" id="numero_fornecedor">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cep_fornecedor" class="form-label">CEP do Fornecedor:</label>
                            <input type="text" name="cep_fornecedor" class="form-control" id="cep_fornecedor">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cidade_fornecedor" class="form-label">Cidade do Fornecedor:</label>
                            <input type="text" name="cidade_fornecedor" class="form-control" id="cidade_fornecedor">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-grid mx-auto" style="margin-top:32px;">
                                <button class="btn btn-primary" name="cadastrar-fornecedor" type="submit">Cadastrar Fornecedor</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="JsBarcode-master/dist/JsBarcode.all.js"></script>
        <script>
            // Gerar o código de barras e preencher o campo
            function gerarCodigoBarras() {
                var codigoBarras = Math.floor(Math.random() * 9000000000000) + 1000000000000; // Gera um código de barras aleatório de 13 dígitos
                document.getElementById('codigo_barras').value = codigoBarras;

                // Usar a biblioteca JsBarcode para gerar o código de barras no elemento canvas
                JsBarcode("#barcode", codigoBarras);

                // Capturar a imagem do código de barras usando html2canvas
                html2canvas(document.querySelector("#barcode")).then(function(canvas) {
                    var imgData = canvas.toDataURL("image/png");
                    document.getElementById('codigo_barras_image').value = imgData;
                });
            }

            // Chamar a função para gerar o código de barras e a imagem ao carregar a página
            gerarCodigoBarras();
        </script>

        <script>
            function preencherPrecos() {
            var nomeProduto = document.getElementById('nome_produto').value;
            console.log(nomeProduto);

            // Crie um objeto FormData para enviar os dados
            var formData = new FormData();
            formData.append('nome_produto', nomeProduto);

            // Realize uma requisição AJAX para buscar os valores de preço de custo e preço de venda com base no nome do produto
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'buscar_precos.php', true); // Defina o arquivo PHP responsável por buscar os preços

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                    // Suponha que você recebeu os valores de preço de custo e preço de venda nas variáveis precoCusto e precoVenda
                    var precoCusto = response.preco_custo;
                    var precoVenda = response.preco_venda;

                    // Preencha os campos de preço de custo e preço de venda
                    document.getElementById('preco_custo').value = precoCusto;
                    document.getElementById('preco_venda').value = precoVenda;
                    // Chame a função calcularMargemLucro() para calcular e preencher a margem de lucro
                    calcularMargemLucro();
                } else {
                    // Exiba uma mensagem informando que os preços não foram encontrados
                    console.log('Os preços não foram encontrados');
                }
                }
            };

            // Envie os dados do produto para o arquivo PHP
            xhr.send(formData);
            }

        </script>

        <script>
            // Obtém os elementos dos campos de preço de custo, preço de venda e margem de lucro
            var precoCustoInput = document.getElementById('preco_custo');
            var precoVendaInput = document.getElementById('preco_venda');
            var margemLucroInput = document.getElementById('margem-lucro');

            // Adiciona um evento de input nos campos de preço de custo e preço de venda
            precoCustoInput.addEventListener('input', calcularMargemLucro);
            precoVendaInput.addEventListener('input', calcularMargemLucro);

            // Função para calcular e preencher o valor da margem de lucro
            function calcularMargemLucro() {
                var precoCusto = parseFloat(precoCustoInput.value);
                var precoVenda = parseFloat(precoVendaInput.value);

                if (!isNaN(precoCusto) && !isNaN(precoVenda)) {
                    var margemLucro = precoVenda - precoCusto;
                    margemLucroInput.value = margemLucro.toFixed(2);
                }
            }
        </script>
        <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
