<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - ReVeste</title>
    <link rel="stylesheet" href="styles/globals.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #F5E7D4;
            padding: 20px;
        }
        .auth-container {
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .auth-container h2 {
            color: #5C8070;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #B9735B; /* Cor diferente para destacar cadastro */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #5C8070;
        }
        .links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        .links a {
            color: #5C8070;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <h2>Crie sua Conta</h2>
        
        <form action="auth_logic.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">

            <div class="form-group">
                <label>Nome do Usuário ou Negócio:</label>
                <input type="text" name="nome" required placeholder="Ex: Brechó da Maria">
            </div>

            <div class="form-group">
                <label>Eu sou:</label>
                <select name="tipo" required>
                    <option value="" disabled selected>Selecione...</option>
                    <option value="brecho">Brechó</option>
                    <option value="costureiro">Costureiro(a)</option>
                </select>
            </div>

            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required placeholder="Crie uma senha">
            </div>

            <button type="submit">Cadastrar</button>
        </form>

        <div class="links">
            <p>Já tem uma conta? <a href="login.php">Faça Login</a></p>
            <p><a href="index.php">Voltar para a página inicial</a></p>
        </div>
    </div>

</body>
</html>