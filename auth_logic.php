<?php
session_start();
include 'config/database.php';

// Verifica se alguma ação foi enviada
$acao = $_POST['acao'] ?? '';

// --- LÓGICA DE CADASTRO ---
if ($acao == 'cadastrar') {
    // Limpa os dados para evitar problemas de segurança básicos
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $tipo = $conn->real_escape_string($_POST['tipo']);

    // Criptografa a senha (nunca salvamos senha pura no banco!)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara dados padrão para o perfil não ficar vazio
    $titulo_padrao = $nome; 
    $desc_padrao = "Olá! Este é meu perfil na ReVeste.";

    // Insere no Banco de Dados
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo, titulo_anuncio, descricao) 
            VALUES ('$nome', '$email', '$senhaHash', '$tipo', '$titulo_padrao', '$desc_padrao')";

    if ($conn->query($sql) === TRUE) {
        // Se deu certo, manda pro login com aviso
        echo "<script>
                alert('Cadastro realizado com sucesso! Faça seu login.');
                window.location.href='login.php';
              </script>";
    } else {
        // Se deu erro (ex: email repetido)
        echo "Erro ao cadastrar: " . $conn->error;
        echo "<br><a href='cadastro.php'>Tentar novamente</a>";
    }
}

// --- LÓGICA DE LOGIN ---
elseif ($acao == 'login') {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Busca o usuário pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifica se a senha bate com a criptografia
        if (password_verify($senha, $user['senha'])) {
            // LOGIN SUCESSO: Salva dados na sessão (memória do navegador)
            $_SESSION['id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['tipo'] = $user['tipo']; // Vamos usar isso para saber se é brechó ou costureiro
            
            header("Location: dashboard.php"); // Manda pro painel
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('E-mail não encontrado!'); window.history.back();</script>";
    }
}
// Se alguém tentar acessar direto sem enviar formulário
else {
    header("Location: index.php");
}
?>