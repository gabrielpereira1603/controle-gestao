<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/meu-perfil.css">
    <link rel="icon" href="assets/img/fiveicon.png" type="image/png">
    <title>Somos Dev's</title>
</head>
<body style="background-color: #e4e3e3;">
    <?php
        include("cabecario.php");
    ?>
    
    <section class="main">
        <div class="title">
            <h1>Meu Perfil</h1>
        </div>

        <div class="dados-usuario">
            <h3>Dados Usuário</h3>
            <hr>
            <form action="" method="post">

                <div class="profile-picture" id="profile-picture">
                    <!-- Imagem de perfil -->
                    <img src="assets/img/foto-my.jpg" alt="Foto de Perfil" class="rounded-circle">
                    <!-- Ícone de lápis para edição -->
                    <div class="edit-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                </div>
                
                <div id="profile-options">
                    <button class="btn btn-danger" name="excluir-foto" id="change-photo-btn" type="submit">Excluir Foto</button>
                </div>

                <div class="row align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Login:</label>
                                <input type="text" name="login" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                            </div>
                        </div>
                        </div>
                        <div class="col">
                        <div class="d-grid gap-2 col-6" style="margin-top: 15px;">
                            <button class="btn btn-primary" type="submit">Alterar Usuário</button>
                        </div>
                    </div>
                </div>
            </form>

           
        </div>

    </section>


    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>