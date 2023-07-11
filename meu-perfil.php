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
    <link rel="stylesheet" href="assets/css/meu-perfil.css">
    <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
    <title>Somos Dev's</title>
</head>
<body style="background-color: #e2e8f0;">
    <?php
        include("cabecario.php");
    ?>
    
    <section class="main">
        <div class="title">
            <h1>Meu Perfil</h1>
        </div>

        <div class="dados-usuario">
            <h3>Dados do Usuário</h3>
            <hr>
            <?php
                // Consulta para obter as informações do usuário, incluindo o caminho da foto
                $userId = $_SESSION['codusuario'];
                $sql = "SELECT nome_usuario, data_nascimento, telefone, login, email, nomecargo, foto_perfil FROM usuario 
                        INNER JOIN cargo ON usuario.codcargo = cargo.codcargo
                        WHERE codusuario = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->bind_result($nomeUsuario, $dataNascimento, $telefone, $login, $email, $nomeCargo, $fotoPerfil);
                $stmt->fetch();
                $stmt->close();
            ?>

            <form action="upload-foto-perfil.php" method="post">

                <div class="perfil-principal">
                    <div class="profile-picture" id="profile-picture">
                        <!-- Imagem de perfil -->
                        <img src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil" class="rounded-circle">
                        <!-- Ícone de lápis para edição -->
                        <div class="edit-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>                  
                        <!-- Elemento de input de arquivo oculto -->
                        <input type="file"  name="foto_perfil" id="file-input" style="display: none;" onchange="uploadFoto()">
                    </div>

                    <div class="info-perfil">
                        <p><strong>Nome: </strong><?php echo $nomeUsuario; ?></p>
                        <p><strong>Data de Nascimento: </strong><?php echo date('d/m/Y', strtotime($dataNascimento)); ?></p>                       
                        <p><strong>Telefone: </strong><?php echo $telefone; ?></p>
                        <p><strong>Login: </strong><?php echo $login; ?></p>
                        <p><strong>Email: </strong><?php echo $email; ?></p>
                        <p><strong>Cargo: </strong><?php echo $nomeCargo; ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger" type="submit" name="excluir-foto">Excluir Foto</button>
                    </div>
                </div>

                <div class="alterar-dados"> 
                    <!-- Exibe a mensagem de sucesso ao criar um novo usuário -->
                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                                echo $_SESSION['error_message']; 
                                unset($_SESSION['error_message']); 
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Exibe a mensagem de sucesso de alteracao de senha -->
                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['success_message']; 
                                unset($_SESSION['success_message']); 
                            ?>
                        </div>
                    <?php endif; ?>
                    <h3>Alterar dados da sua conta</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="login" class="form-label">Login:</label>
                                <input type="text" name="login" class="form-control" id="login" placeholder="<?php echo $login; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" name="nome" class="form-control" id="nome" placeholder="<?php echo $nomeUsuario; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone:</label>
                                <input type="number" name="telefone" class="form-control" id="telefone" placeholder="<?php echo $telefone; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="data" class="form-label">Data De Nascimento:</label>
                                <input type="date" name="data_nascimento" class="form-control" id="data" placeholder="<?php echo $dataNascimento; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="<?php echo $email; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                            // Verifique se há um usuário logado na sessão
                            if (isset($_SESSION['codusuario'])) {
                                $userId = $_SESSION['codusuario'];

                                // Consulta para obter o cargo do usuário logado
                                $sql = "SELECT cargo.codcargo, cargo.nomecargo FROM usuario INNER JOIN cargo ON usuario.codcargo = cargo.codcargo WHERE usuario.codusuario = ?";
                                $stmt = $conexao->prepare($sql);
                                $stmt->bind_param("i", $userId);
                                $stmt->execute();
                                $resultado = $stmt->get_result();
                                $stmt->close();

                                // Verifique se há registros retornados
                                if ($resultado->num_rows > 0) {
                                    echo '<div class="col-md-6">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="cargo" class="form-label">Cargo:</label>';
                                    echo '<select class="form-select" name="codcargo" aria-label="Default select example" disabled>';
                                    
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
                            }
                        ?>

                        <div class="col-md-6" style="margin-top:33px;">
                            <div class="mb-3">
                                <div class="d-grid mx-auto">
                                    <button class="btn btn-primary" name="alterar-usuario" type="submit">Alterar Dados</button>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>
    
    <script>
        document.getElementById('profile-picture').addEventListener('click', function() {
            // Abre o explorador de arquivos quando o usuário clica na foto de perfil
            document.getElementById('file-input').click();
        });

        // Função para realizar o upload da foto selecionada
        function uploadFoto() {
            var input = document.getElementById('file-input');
            var file = input.files[0];
            var formData = new FormData();
            formData.append('foto_perfil', file);

            // Envia a foto selecionada para o servidor usando AJAX ou outra técnica de envio de formulário assíncrono

            // Exemplo de uso do AJAX para enviar a foto para o servidor
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload-foto-perfil.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Sucesso ao enviar a foto
                    console.log('Foto enviada com sucesso!');
                } else {
                    // Erro ao enviar a foto
                    console.log('Erro ao enviar a foto.');
                }
            };
            xhr.send(formData);
        }
    </script>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>