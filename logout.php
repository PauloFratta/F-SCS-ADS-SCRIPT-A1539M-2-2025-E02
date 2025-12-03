<?php
session_start();       // Inicia a sessão para poder acessá-la
session_unset();       // Limpa todas as variáveis da sessão
session_destroy();     // Destrói a sessão atual (desloga o usuário)

// Redireciona o usuário de volta para a página inicial
header("Location: index.php");
exit();
?>