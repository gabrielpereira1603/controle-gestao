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
        <link rel="stylesheet" href="assets/css/setting-users.css">
        <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
        <title>Somos Dev's</title>
    </head>
    <body style="background-color: #e4e3e3;">
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
            </div>
        </section>

        <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>