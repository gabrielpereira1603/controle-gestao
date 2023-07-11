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
        <link rel="stylesheet" href="assets/css/ver-produtos.css">
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
                <h1>Alterar Produtos</h1>
                <p>Aqui voçe pode vizualizar e alterar produtos cadastrados</p>
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
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID Produto</th>
                            <th scope="col">Nome Produto</th>
                            <th scope="col">Preço Venda</th>
                            <th scope="col">Preço Custo</th>
                            <th scope="col">Margem Lucro</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Realize a consulta SQL para obter os dados da tabela de produtos com junção nas tabelas relacionadas
                        $sql = "SELECT p.codproduto, p.nome_produto, p.preco_venda, p.preco_custo, p.margem_lucro, tp.nome_tipo, m.nome_marca, f.nome_fornecedor, p.quantidade
                                FROM produto p
                                INNER JOIN tipoproduto tp ON p.codtipo = tp.codtipoproduto
                                INNER JOIN marca m ON p.codmarca = m.codmarca
                                INNER JOIN fornecedor f ON p.codfornecedor = f.codfornecedor";
                        $resultado = $conexao->query($sql);

                        // Itere sobre os registros e exiba os dados na tabela
                        while ($row = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['codproduto'] . '</td>';
                            echo '<td>' . $row['nome_produto'] . '</td>';
                            echo '<td>' . $row['preco_venda'] . '</td>';
                            echo '<td>' . $row['preco_custo'] . '</td>';
                            echo '<td>' . $row['margem_lucro'] . '</td>';
                            echo '<td>' . $row['nome_tipo'] . '</td>';
                            echo '<td>' . $row['nome_marca'] . '</td>';
                            echo '<td>' . $row['nome_fornecedor'] . '</td>';
                            echo '<td>' . $row['quantidade'] . '</td>';
                            echo '<td>';
                            echo '<div class="icon-container">';
                            echo '<a href="#" onclick="mostrarFormulario(' . $row['codproduto'] . ')" style="color:green;"><i class="bi bi-pencil-fill"></i></a>';
                            echo '<a href="excluir_produto.php?id=' . $row['codproduto'] . '" style="color:red;"><i class="bi bi-trash3-fill"></i></a>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pop-up -->
            <div id="popup">
                <div class="popup-content">
                    <!-- Formulário de edição -->
                    <!-- Ícone de fechamento -->
                    <span class="popup-close" onclick="fecharPopup()">&times;</span>
                    <form id="" action="form-alterar-produto.php" method="post">
                        <!-- Exemplo de campo oculto para armazenar o código do produto -->
                        <input type="hidden" name="codproduto" id="codproduto" value="">

                        <div class="row">
                            <h4>Editar Produto:</h4>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome_produto" class="form-label">Nome Produto:</label>
                                    <input type="text" name="nome_produto" class="form-control" id="nome_produto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_produto" class="form-label">Tipo Do Produto:</label>
                                    <select class="form-select" name="tipo_produto" id="tipo_produto" aria-label="Default select example">
                                        <option selected>Selecione o tipo:</option>
                                        <?php
                                            $sql = "SELECT codtipoproduto, nome_tipo FROM tipoproduto";
                                            $resultado = $conexao->query($sql);

                                            while ($row = $resultado->fetch_assoc()) {
                                                $codtipo = $row["codtipoproduto"];
                                                $nometipo = $row["nome_tipo"];
                                                echo "<option value='$codtipo'>$nometipo</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca_produto" class="form-label">Marca Do Produto:</label>
                                    <input type="text" name="marca_produto" class="form-control" id="marca_produto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fornecedor_produto" class="form-label">Fornecedor:</label>
                                    <select class="form-select" name="fornecedor_produto" id="fornecedor_produto">
                                        <option selected>Selecione um fornecedor</option>
                                        <?php
                                            $sql = "SELECT * FROM listar_fornecedores";
                                            $resultado = $conexao->query($sql);

                                     
                                            while ($row = $resultado->fetch_assoc()) {
                                                $codfornecedor = $row['codfornecedor'];
                                                $nomefornecedor = $row['nome_fornecedor'];
                                                echo "<option value='$codfornecedor'>$nomefornecedor</option>";
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
                                    <input type="number" name="preco_custo" class="form-control preco-input" id="preco_custo" step="0.01" min="0" max="99999.99" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preco_venda" class="form-label">Preço de Venda:</label>
                                    <input type="number" name="preco_venda" class="form-control preco-input" id="preco_venda" step="0.01" min="0" max="99999.99" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="margem-lucro" class="form-label">Lucro:</label>
                                            <input type="number" name="margem-lucro" class="form-control" id="margem-lucro" step="0.01" min="0" max="99999.99" placeholder="0.00" readonly>
                                        </div>
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
                                        <button class="btn btn-primary" name="alterar-produto" type="submit">Alterar Produto</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                </div>
            </div>

        </section>
        <script>
            $(document).ready(function() {
                $('.table').DataTable();
            });
        </script>
        <script>
            function fecharPopup() {
                // Ocultar o pop-up
                var popup = document.getElementById('popup');
                popup.style.display = 'none';

                // Remover a classe para exibir a barra de rolagem do corpo da página
                document.body.classList.remove('popup-open');
            }
            
            function mostrarFormulario(codproduto) {
                // Exibir o pop-up
                var popup = $('#popup');
                popup.css('display', 'block');

                // Adicionar a classe para ocultar a barra de rolagem do corpo da página
                $('body').addClass('popup-open');
                
                // Definir o valor do campo oculto 'codproduto'
                $('#codproduto').val(codproduto);

                // Realizar uma requisição AJAX para obter os dados do produto com base no ID
                $.ajax({
                    url: 'obter_dados_produto.php',
                    type: 'GET',
                    data: { id: codproduto },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Resposta da requisição AJAX:', response);
                        if (response.success) {
                            preencherFormularioProduto(response);
                            // Armazene o codproduto em uma variável para uso posterior
                            var codProdutoSelecionado = codproduto;
                        } else {
                            console.log('Falha ao obter dados do produto');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Erro na requisição AJAX: ' + error);
                    }
                });
            }

            function preencherFormularioProduto(data) {
                console.log(data); // Verificar o valor de data no console

                document.getElementById('nome_produto').value = data.nome_produto;
                document.getElementById('marca_produto').value = data.nome_marca;
                document.getElementById('preco_custo').value = data.preco_custo;
                document.getElementById('preco_venda').value = data.preco_venda;
                document.getElementById('margem-lucro').value = data.margem_lucro;
                document.getElementById('quantidade').value = data.quantidade;

                // Preencher o campo 'tipo_produto' como um elemento <select>
                var tipoProdutoSelect = document.getElementById('tipo_produto');
                for (var i = 0; i < tipoProdutoSelect.options.length; i++) {
                    if (tipoProdutoSelect.options[i].text === data.nome_tipo) {
                        tipoProdutoSelect.selectedIndex = i;
                        break;
                    }
                }

                // Preencher o campo 'fornecedor_produto' como um elemento <select>
                var fornecedorSelect = document.getElementById('fornecedor_produto');
                for (var j = 0; j < fornecedorSelect.options.length; j++) {
                    if (fornecedorSelect.options[j].text === data.nome_fornecedor) {
                        fornecedorSelect.selectedIndex = j;
                        break;
                    }
                }
            }

        </script>

    </body>
</html>