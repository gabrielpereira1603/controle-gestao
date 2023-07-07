<?php
session_start();
include("conexao.php");

if (isset($_POST['excluir-foto'])) {
    // Obtém o ID do usuário logado (você pode adaptar essa parte conforme a sua lógica de autenticação)
    $userId = $_SESSION['codusuario'];

    // Atualiza a coluna 'foto_perfil' na tabela 'usuario' com NULL para excluir a foto
    $sql = "UPDATE usuario SET foto_perfil = NULL WHERE codusuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = 'Foto de perfil excluída com sucesso!';
    // Redireciona de volta para a página onde o formulário foi submetido
    header('Location: meu-perfil.php');
    exit;
}

// Verifica se foi enviado um arquivo
if(isset($_FILES['foto_perfil'])) {
    $file = $_FILES['foto_perfil'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    
    // Diretório de destino para salvar a foto
    $uploadDir = 'fotos-perfil/'; // Altere para o diretório desejado

    // Move o arquivo para o diretório de destino
    if(move_uploaded_file($fileTmpName, $uploadDir.$fileName)) {
        // Caminho completo do arquivo
        $filePath = $uploadDir.$fileName;

        // Obtém o ID do usuário logado (você pode adaptar essa parte conforme a sua lógica de autenticação)
        $userId = $_SESSION['codusuario'];

        // Atualiza a coluna 'foto_perfil' na tabela 'usuario' com o caminho do arquivo
        $sql = "UPDATE usuario SET foto_perfil = ? WHERE codusuario = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $filePath, $userId);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = 'Foto de perfil atualizada com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Erro ao fazer upload da foto de perfil.';
    }
    // Redireciona de volta para a página onde o upload foi feito
    header('Location: meu-perfil.php');
    exit;
}


session_start();
include("conexao.php");

if (isset($_POST['alterar-usuario'])) {
    $email = $_POST["email"];
    $userId = $_SESSION['codusuario'];
    $alteracoes = array();

    if (!empty($_POST["login"])) {
        $login = $_POST["login"];
        $alteracoes[] = "login = '$login'";
    }

    if (!empty($_POST["nome"])) {
        $nome = $_POST["nome"];
        $alteracoes[] = "nome_usuario = '$nome'";
    }

    if (!empty($_POST["telefone"])) {
        $telefone = $_POST["telefone"];
        $alteracoes[] = "telefone = '$telefone'";
    }

    if (!empty($_POST["data_nascimento"])) {
        $dataNascimento = $_POST["data_nascimento"];
        $alteracoes[] = "data_nascimento = '$dataNascimento'";
    }

    if (!empty($_POST["codcargo"])) {
        $codCargo = $_POST["codcargo"];
        $alteracoes[] = "codcargo = '$codCargo'";
    }

    // Verifica se há alterações a serem realizadas
    if (!empty($alteracoes)) {
        // Monta a consulta SQL dinamicamente
        $sql = "UPDATE usuario SET " . implode(", ", $alteracoes) . " WHERE codusuario = $userId";

        // Executa a consulta
        $resultado = $conexao->query($sql);

        if ($resultado) {
            if ($conexao->affected_rows > 0) {
                $_SESSION['success_message'] = 'Dados atualizados com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Nenhum dado foi atualizado.';
            }
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar os dados: ' . $conexao->error;
        }

    } else {
        $_SESSION['error_message'] = 'Nenhum campo preenchido para atualização.';
    }
    
}

// Redireciona de volta para a página onde o formulário foi submetido
header('Location: meu-perfil.php');
exit;
?>
