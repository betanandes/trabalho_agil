<?php
// Credenciais de conexão com o banco de dados
$servername = 'localhost';
$username = 'root'; 
$password = '';
$dbname = 'gerenciar_pessoas';

// Cria conexão com o banco de dados utilizando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou e, em caso afirmativo, encerra o script com uma mensagem de erro
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
