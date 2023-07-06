<?php
// Dados de conexão com o banco de dados
$servername = "localhost"; // Nome do servidor
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "controle-gestao"; // Nome do banco de dados

// Cria a conexão
$conexao = new mysqli($servername, $username, $password, $dbname);

// Verifica se ocorreu algum erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Executa consultas ou operações no banco de dados...

// Fecha a conexão com o banco de dados
$conexao->close();
?>