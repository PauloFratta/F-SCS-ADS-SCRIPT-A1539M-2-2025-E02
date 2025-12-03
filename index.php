<?php
session_start(); // INICIA A SESSÃO
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReVeste</title>
    <link rel="stylesheet" href="styles/globals.css" />
    <link rel="shortcut icon" href="images/RV.png"/>
</head>

<body>
    <div class="navbar">
        <div class="header-inner-content">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="costureiros.php">Costureiros</a></li>
                    <li><a href="brechos.php">Brechós</a></li>
                    
                    <?php if (isset($_SESSION['id'])): ?>
                        <li>
                            <a href="dashboard.php" style="color: #B9735B; font-weight: bold;">
                                Olá, <?php echo explode(' ', $_SESSION['nome'])[0]; ?> (Painel)
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" style="color: red; font-size: 0.9rem;" onclick="return confirm('Sair da sua conta?');">
                                Sair
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" style="color: #B9735B; font-weight: bold;">Login / Cadastro</a></li>
                    <?php endif; ?>
                    </ul>
            </nav>
        </div>
    </div>

    <header>
        <div class="header-inner-content">
            <div class="header-bottom-side">
                <div class="logoReveste">
                    <img src="images/ReVeste.png" alt="Logo ReVeste Upcycling" />
                </div>

                <div class="header-text">
                    <h2><strong>Upcycling e Moda Sustentável</strong></h2><br>
                    <p>Todos os anos, <strong>mais de 92 toneladas de roupas</strong> são descartadas no mundo!
                    Um volume que sufoca o planeta e revela o lado obscuro da moda: <strong>impactos ambientais,
                    desperdício e trabalho precário.</strong><br>
                    Mas e se a gente pudesse mudar essa história — <strong>com estilo</strong>? 
                    <br>
                    A <strong>ReVeste</strong> nasceu para transformar o consumo de moda em um gesto de
                    <strong>sustentabilidade e criatividade</strong>.
                    <br>
                    Aqui, você se conecta com <strong>brechós cheios de personalidade</strong> e <strong>costureiros
                    que dão nova vida às peças</strong>, criando looks únicos, com propósito e alma.
                    
                    <p>
                    Consuma diferente, <strong>ReVeste</strong> o que tem significado!
                    </p>

                    <a href="costureiros.php" class="header-link">CONHEÇA OS COSTUREIROS PARCEIROS</a>
                    <a href="brechos.php" class="header-link">CONHEÇA NOSSO ACERVO DE BRECHÓS</a>
                </div>
            </div>
        </div>
    </header>

</body>
</html>