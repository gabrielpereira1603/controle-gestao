<?php
session_start();
include("conexao.php");

if (isset($_POST['criar-usuario'])) {
    $newUsername = $_POST["login"];
    $newPassword = $_POST["senha"];
    $newName = $_POST["nome"];
    $newEmail = $_POST["email"];

    // Verifica se os campos estão preenchidos
    if (empty($newUsername) || empty($newPassword) || empty($newName) || empty($newEmail)) {
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
                $sql = "INSERT INTO usuario (nome_usuario, email, senha, login) VALUES (?, ?, ?, ?)";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ssss", $newName, $newEmail, $hashedPassword, $newUsername);
                $stmt->execute();
                $stmt->close();

                if ($conexao->affected_rows > 0) {
                    $_SESSION['success_message'] = 'Usuário criado com sucesso!';
                } else {
                    $_SESSION['error_message'] = 'Erro ao criar usuário!';
                }
            }
        }
    }

    // Redireciona para a página login.php após criar o usuário ou exibir mensagens de erro
    header('Location: login.php');
    exit;
}


include("conexao.php");
// Verifica se o formulário de login foi enviado
if (isset($_POST['entrar'])) {
    $senha = $_POST["senha"];
    $login = $_POST["login"];

    // Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
    if (empty($login) || empty($senha)) {
        $_SESSION['error_message'] = 'Login ou Senha não podem estar vazios!';
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT codusuario, senha FROM usuario WHERE login = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $codusuario = $row['codusuario'];
        $senha_hash = $row['senha'];
        $codpermissao = $row['codpermissao'];

        // Verifica a senha
        if (!password_verify($senha, $senha_hash)) {
            $_SESSION['error_message'] = 'Senha incorreta!';
            header("Location: login.php");
            exit;
        }

        $_SESSION['autenticado'] = true;
        $_SESSION['login'] = $login;
        $_SESSION['codpermissao'] = $codpermissao;
        $_SESSION['codusuario'] = $codusuario;

        header("Location: home.php");
        exit;
    } else {
        $_SESSION['error_message'] = 'Login ou Senha incorretos!';
        header("Location: login.php");
        exit;
    }

    $stmt->close();
    $conexao->close();
}
?>
