<?php
// config/database.php

$host = "localhost";
$usuario = "root"; // Padrão do XAMPP
$senha = "";       // Padrão do XAMPP é vazio
$banco = "sistema_reveste"; // O nome exato que criamos no Passo 1

// Cria a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu erro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>