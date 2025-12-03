<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReVeste</title>
    <link rel="stylesheet" href="styles/globals.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #F5E7D4;
        }
        .auth-container {
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
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
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #5C8070;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #B9735B;
        }
        .links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        .links a {
            color: #B9735B;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <h2>Entrar na ReVeste</h2>
        
        <form action="auth_logic.php" method="POST">
            <input type="hidden" name="acao" value="login">

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>

            <button type="submit">Entrar</button>
        </form>

        <div class="links">
            <p>Ainda não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
            <p><a href="index.php">Voltar para a página inicial</a></p>
        </div>
    </div>

</body>
</html>