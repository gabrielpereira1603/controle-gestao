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

// Obtém o ID do usuário logado (você pode adaptar essa parte conforme a sua lógica de autenticação)
$userId = $_SESSION['codusuario'];

// Consulta o cargo do usuário logado
$sql = "SELECT codcargo FROM usuario WHERE codusuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($codCargo);
$stmt->fetch();
$stmt->close();

// Verifica se o usuário possui o cargo 3
if ($codCargo != 3) {
    $_SESSION['error_message'] = 'Você não tem permissão para entrar!';
    // Redireciona o usuário de volta para a página de login
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/setting-users.css">
        <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
        <title>Somos Dev's</title>
    </head>
    <body style="background-color: #e2e8f0;">
        <?php include("cabecario.php"); ?>

        <section class="main">
            <div class="title">
                <h1>Gerenciar Usuários</h1>
            </div>

            <div class="gerenciar-user">
                <div class="criar-user">
                    <h3>Criar Novo Usuário</h3>
                    <form action="form-setting-users.php" method="post">
                        <!-- Exibe a mensagem de sucesso ao criar um novo usuário -->
                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger" style="text-aling:center;">
                                <?php 
                                    echo $_SESSION['error_message']; 
                                    unset($_SESSION['error_message']); 
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Exibe a mensagem de sucesso de alteracao de senha -->
                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success" style="text-aling:center;">
                                <?php 
                                    echo $_SESSION['success_message']; 
                                    unset($_SESSION['success_message']); 
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="login" class="form-label">Login:</label>
                                    <input type="text" name="login" class="form-control" id="login" placeholder="123">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome:</label>
                                    <input type="text" name="nome" class="form-control" id="nome" placeholder="Maria">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha:</label>
                                    <input type="password" name="senha" class="form-control" id="senha">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone:</label>
                                    <input type="number" name="telefone" class="form-control" id="telefone" placeholder="9 0000-0000">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data" class="form-label">Data De Nascimento:</label>
                                    <input type="date" name="data_nascimento" class="form-control" id="data" placeholder="00/00/0000">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="exemplo@gmail.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                                // Consulta para obter os cargos da tabela 'cargo'
                                $sql = "SELECT codcargo, nomecargo FROM cargo";
                                $resultado = $conexao->query($sql);

                                // Verifique se há registros retornados
                                if ($resultado->num_rows > 0) {
                                    echo '<div class="col-md-6">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="cargo" class="form-label">Cargo:</label>';
                                    echo '<select class="form-select" name="codcargo" aria-label="Default select example">';
                                    echo '<option selected>Selecione o cargo:</option>';

                                    // Itere sobre os registros e crie as opções no select
                                    while ($row = $resultado->fetch_assoc()) {
                                        $codcargo = $row["codcargo"];
                                        $nomecargo = $row["nomecargo"];
                                        echo "<option value='$codcargo'>$nomecargo</option>";
                                    }

                                    echo '</select>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            ?>
                            <div class="col-md-6" style="margin-top:33px;">
                                <div class="mb-3">
                                    <div class="d-grid mx-auto">
                                        <button class="btn btn-primary" name="criar-usuario" type="submit">Crira Usuário</button>
                                    </div> 
                               </div>
                            </div>
                        </div>
                        <hr>
                    </form>
                </div>

                <div class="gerenciar-cargo">
                    <form action="form-setting-users.php" method="post">
                        <h3>Alterar Cargo</h3>
                        <?php
                            // Consulta para obter os cargos da tabela 'cargo'
                            $sql = "SELECT codusuario, nome_usuario FROM usuario";
                            $resultado = $conexao->query($sql);

                            // Verifique se há registros retornados
                            if ($resultado->num_rows > 0) {
                                echo '<label for="cargo" class="form-label">Usuário:</label>';
                                echo '<select class="form-select" name="codusuario" aria-label="Default select example" id="select-usuario">';
                                echo '<option selected>Selecione um usuário:</option>';

                                // Itere sobre os registros e crie as opções no select
                                while ($row = $resultado->fetch_assoc()) {
                                    $codusuario = $row["codusuario"];
                                    $nomeusuario = $row["nome_usuario"];
                                    echo "<option value='$codusuario'>$nomeusuario</option>";
                                }

                                echo '</select>';
                            }
                        ?>
                        <input type="hidden" name="codusuario" id="codusuario" value="">

                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" name="nome-cargo" class="form-control" id="nome-cargo" placeholder="Maria" disabled>

                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email-cargo" class="form-control" id="email-cargo" placeholder="exemplo@gmail.com" disabled>
                                
                        <label for="cargo-atual" class="form-label">Cargo que este usuário possui:</label>
                        <input type="text" name="cargo-atual" class="form-control" id="cargo-atual" disabled>

                        <?php
                            // Consulta para obter os cargos da tabela 'cargo'
                            $sql = "SELECT codcargo, nomecargo FROM cargo";
                            $resultado = $conexao->query($sql);

                            // Verifique se há registros retornados
                            if ($resultado->num_rows > 0) {
                                echo '<label for="cargo" class="form-label">Selecione o novo cargo:</label>';
                                echo '<select class="form-select" name="codcargo" aria-label="Default select example">';
                                echo '<option selected>Selecionar novo cargo:</option>';

                                // Itere sobre os registros e crie as opções no select
                                while ($row = $resultado->fetch_assoc()) {
                                    $codcargo = $row["codcargo"];
                                    $nomecargo = $row["nomecargo"];
                                    echo "<option value='$codcargo'>$nomecargo</option>";
                                }

                                echo '</select>';
                            }
                        ?>
                        <div class="d-grid mx-auto" style="margin-top:20px;">
                            <button class="btn btn-primary" name="alterar-cargo" type="submit">Alterar Cargo</button>
                        </div> 
                        <hr>
                    </form>
                    

                    <div class="info-cargo">
                        <h3>Informações sobre alteração de cargo</h3>
                        
                        <div class="text-info">
                            <legend>Nome/Email</legend>
                            <p>Mostramos o email e o nome do usuário para que possa ter certeza de que é este usuário que deseja alterar o cargo</p>
                            
                            <legend>Cargo Atual</legend>
                            <p>Deixamos a amostra para que possa ver o cargo atual do usuário selecionado</p>
                                
                            <legend>Novo Cargo</legend>
                            <p>Basta selecionar o cargo desejado entre as opções disponíveis e clicar no botão alterar cargo que ira alterar o cargo do usuário selecionado.</p>
                        </div>
                    
                    </div>
                
                </div>
            </div>
        </section>

        <script>
document.getElementById('select-usuario').addEventListener('change', function() {
    var selectedUserId = this.value; // ID do usuário selecionado
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'buscar-usuario.php?codusuario=' + selectedUserId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            // Atualize os campos do formulário com as informações do usuário
            document.getElementById('nome-cargo').value = response.nome_usuario;
            document.getElementById('email-cargo').value = response.email;
            document.getElementById('cargo-atual').value = response.nomecargo;
            document.getElementById('codusuario').value = response.codusuario; 
        }
    };
    xhr.send();
});

        </script>

        <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>