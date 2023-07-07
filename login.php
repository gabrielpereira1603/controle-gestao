<?php
// Inicia a sessão
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="shortcut icon" href="assets/img/fiveicon.png" />
    <title>Integração Somos Dev's</title>
</head>
    <body>
        <div class="container">
            <div class="form-section">
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

                <form action="validar-login.php" method="post" id="user-existente">
                    <!-- Adicione os campos do formulário de login aqui -->
                    <h2>Iniciar Sessão</h2>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="login" id="floatingInput" placeholder="Login" required>
                        <label for="floatingInput">Login:</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" name="senha" id="floatingPassword" placeholder="Senha" required>
                        <label for="floatingPassword">Senha:</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" name="entrar">Login</button>
                    </div>

                    <p>Não tem uma conta? <a href="#" onclick="showUserNovo()">Criar novo usuário</a></p>
                </form>

                <form action="validar-login.php" method="post" id="user-novo" style="display: none;">
                    <!-- Adicione os campos do formulário de novo usuário aqui -->
                    <h2>Registre-se</h2>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nome" id="floatingInput" placeholder="Nome" required>
                        <label for="floatingInput">Nome:</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="email" id="floatingInput" placeholder="Email@gmail.com" required>
                        <label for="floatingInput">Email:</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="login" id="floatingInput" placeholder="Login" required>
                        <label for="floatingInput">Login:</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" name="senha" id="floatingPassword" placeholder="Senha" required>
                        <label for="floatingPassword">Senha:</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" name="criar-usuario">Criar usuário</button>
                    </div>
                    <p>Já tem uma conta? <a href="#" onclick="showUserExistente()">Fazer login</a></p>
                </form>
                
            </div>
            
            <div class="image-section">
                <!-- Adicione a imagem e o texto desejados aqui -->
                <div class="img">
                    <img src="assets/img/login.jpg" alt="Imagem">
                </div>
            </div>
        </div>



        <script>
            function showUserNovo() {
                document.getElementById("user-existente").style.display = "none";
                document.getElementById("user-novo").style.display = "block";
            }

            function showUserExistente() {
                document.getElementById("user-novo").style.display = "none";
                document.getElementById("user-existente").style.display = "block";
            }
        </script>
    </body>
</html>
