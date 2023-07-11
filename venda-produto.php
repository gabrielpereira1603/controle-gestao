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
    <link rel="stylesheet" href="assets/css/venda-produto.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
    <title>Somos Dev's</title>
</head>
<body>
    <?php
        include("cabecario.php");
    ?>

    <section class="main">
    <title>Leitor de Código de Barras</title>
    <script>
        // Função para processar a leitura do código de barras
        function processarLeitura(codigoBarras) {
            // Enviar o código de barras para o servidor para obter as informações do produto correspondente
            // Você pode usar AJAX para fazer uma solicitação ao servidor e processar a resposta

            // Exemplo de como enviar o código de barras para o servidor usando AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "processar_codigo_barras.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Processar a resposta do servidor
                        var resposta = JSON.parse(xhr.responseText);

                        // Exibir as informações do produto na tela
                        var nomeProduto = resposta.nome_produto;
                        var precoVenda = resposta.preco_venda;
                        var quantidade = resposta.quantidade;

                        document.getElementById("nome_produto").value = nomeProduto;
                        document.getElementById("preco_venda").value = precoVenda;
                        document.getElementById("quantidade").value = quantidade;
                    } else {
                        console.error("Erro na requisição: " + xhr.status);
                    }
                }
            };
            xhr.send("codigo_barras=" + encodeURIComponent(codigoBarras));
        }

        // Função para lidar com a leitura do código de barras
        function handleBarcodeScanner(event) {
            var codigoBarras = event.target.value;

            // Verificar se o campo de texto não está vazio
            if (codigoBarras.trim() !== "") {
                processarLeitura(codigoBarras);
            }

            // Limpar o campo de texto para a próxima leitura
            event.target.value = "";
        }
    </script>
</head>
<body>
    <h1>Leitor de Código de Barras</h1>
    <input type="text" id="codigo_barras_input" oninput="handleBarcodeScanner(event)">
    <br>
    <label for="nome_produto">Nome do Produto:</label>
    <input type="text" id="nome_produto" readonly>
    <br>
    <label for="preco_venda">Preço de Venda:</label>
    <input type="text" id="preco_venda" readonly>
    <br>
    <label for="quantidade">Quantidade:</label>
    <input type="text" id="quantidade" readonly>
    </section>
</body>
</html>