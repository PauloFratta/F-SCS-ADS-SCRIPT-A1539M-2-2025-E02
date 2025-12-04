<?php
// config/database.php

// Dados extraídos do seu painel InfinityFree
$host = "sql304.infinityfree.com";        // MySQL Hostname
$usuario = "if0_40592186";                // MySQL Username
$banco = "if0_40592186_sistema_reveste";  // MySQL Database Name

// A SENHA VOCÊ PRECISA COPIAR DO PAINEL (clique em "Show" ou "Copy" lá)
$senha = "Mur1c0c4"; 

// Cria a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu erro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
// Define o charset para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
?>