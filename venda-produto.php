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
    
    <style>
    .viewport {
        position: relative;
        width: 640px; /* Ajuste o tamanho do quadrado de visualização da câmera */
        height: 480px; /* Ajuste o tamanho do quadrado de visualização da câmera */
        overflow: hidden;
    }

    #barcodeMold {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%; /* Ajuste o tamanho do molde de acordo com o tamanho do código de barras */
        height: 45%; /* Ajuste o tamanho do molde de acordo com o tamanho do código de barras */
        border: 2px dashed red; /* Estilo de borda para o molde */
        opacity: 0.8; /* Ajuste a opacidade do molde para torná-lo visível */
    }
</style>
</head>
<body>
    <?php
        include("cabecario.php");
    ?>

    <section class="main">
        <!-- <script src="node_modules/quagga/dist/quagga.min.js"></script>
        <div id="interactive" class="viewport">
            <div id="barcodeMold"></div>
        </div> -->

    </section>

    <script>
   Quagga.init({
    inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector("#interactive"),
        constraints: {
            facingMode: "user" // "user" para usar a câmera frontal (webcam)
        },
    },
    decoder: {
        readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader", "i2of5_reader"],
    },
}, function(err) {
    if (err) {
        console.error(err);
        return;
    }
    console.log("Iniciando a leitura do código de barras.");
    Quagga.start();
});

    Quagga.onDetected(function(result) {
        var codigoBarras = result.codeResult.code;
        console.log("Código de barras detectado:", codigoBarras);

        // Verifica se o código de barras completo já foi detectado e se está dentro do molde
        if (codigoBarras.length === 13 && isInsideBarcodeMold(result)) {
            // Aqui você pode fazer o processamento do código de barras e buscar as informações do produto no banco de dados.

            // Parar a leitura de códigos de barras
            Quagga.stop();
        }
    });

    // Função para verificar se o código de barras está dentro do molde
    function isInsideBarcodeMold(result) {
        var barcodeMold = document.getElementById('barcodeMold');
        var barcodeMoldRect = barcodeMold.getBoundingClientRect();
        var barcodeRect = result.line.reduce(function(rect, line) {
            return {
                top: Math.min(rect.top, line.y),
                left: Math.min(rect.left, line.x),
                bottom: Math.max(rect.bottom, line.y),
                right: Math.max(rect.right, line.x)
            };
        }, {
            top: Infinity,
            left: Infinity,
            bottom: -Infinity,
            right: -Infinity
        });

        var viewportRect = document.querySelector('.viewport').getBoundingClientRect();

        return (
            barcodeRect.top >= (barcodeMoldRect.top - viewportRect.top) &&
            barcodeRect.left >= (barcodeMoldRect.left - viewportRect.left) &&
            barcodeRect.bottom <= (barcodeMoldRect.bottom - viewportRect.top) &&
            barcodeRect.right <= (barcodeMoldRect.right - viewportRect.left)
        );
    }
</script>
</body>
</html>