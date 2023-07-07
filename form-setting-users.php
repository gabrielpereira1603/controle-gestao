<?php
session_start();
include("conexao.php");

if (isset($_POST['alterar-cargo'])) {
    $userId = $_POST['codusuario'];
    $newCargo = $_POST['codcargo'];

    // Atualize o cargo do usuário no banco de dados
    $sql = "UPDATE usuario SET codcargo = ? WHERE codusuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $newCargo, $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = 'Cargo alterado com sucesso!';
    // Redirecione de volta para a página do formulário com uma mensagem de sucesso, se desejar
    header("Location: setting-users.php");
    exit;
}

if (isset($_POST['criar-usuario'])) {
    $newUsername = $_POST["login"];
    $newPassword = $_POST["senha"];
    $newName = $_POST["nome"];
    $newEmail = $_POST["email"];
    $newTelefone = $_POST["telefone"];
    $newDataNascimento = $_POST["data_nascimento"];
    $codCargo = $_POST["codcargo"];

    // Verifica se os campos estão preenchidos
    if (empty($newUsername) || empty($newPassword) || empty($newName) || empty($newEmail) || empty($codCargo)) {
        $_SESSION['error_message'] = 'Preencha todos os campos para criar um novo usuário!';
    } else {
        // Verifica se o email já está cadastrado
        $sql = "SELECT COUNT(*) AS count FROM usuario WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $newEmail);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        $emailExists = $count > 0;

        if ($emailExists) {
            $_SESSION['error_message'] = 'O email já está cadastrado!';
        } else {
            // Verifica se o login já está cadastrado
            $sql = "SELECT COUNT(*) AS count FROM usuario WHERE login = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $newUsername);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            $loginExists = $count > 0;

            if ($loginExists) {
                $_SESSION['error_message'] = 'O login já está cadastrado!';
            } else {
                // Cria o hash da senha
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco de dados
                $sql = "INSERT INTO usuario (nome_usuario, email, senha, login, codcargo, data_nascimento, telefone) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ssssiss", $newName, $newEmail, $hashedPassword, $newUsername, $codCargo, $newDataNascimento, $newTelefone);
                $stmt->execute();
                $stmt->close();

                if ($conexao->affected_rows > 0) {
                    $_SESSION['error_message'] = 'Error ao criar o usuário';
                } else {
                    $_SESSION['success_message'] = 'Usuário criado com sucesso!';
                }
            }
        }
    }

    // Redireciona para a página de formulário após criar o usuário ou exibir mensagens de erro
    header('Location: setting-users.php');
    exit;
}

$conexao->close();

?>