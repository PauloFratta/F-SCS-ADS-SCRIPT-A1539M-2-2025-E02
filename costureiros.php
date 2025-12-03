<?php
session_start(); // Inicia a sessão para verificar o login
include 'config/database.php';

// Busca apenas usuários que são do tipo 'costureiro'
$sql = "SELECT * FROM usuarios WHERE tipo = 'costureiro' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReVeste - Costureiros</title>
    <link rel="stylesheet" href="styles/costureiros.css" />
    <link rel="shortcut icon" href="images/RV.png"/>
</head>
<body>
    <div class="navbar">
        <div class="header-inner-content">
            <h2 class="navbar-title">Conheça Nossos Costureiros</h2>
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
                            <a href="logout.php" style="color: red; font-size: 0.9rem;" onclick="return confirm('Tem certeza que deseja sair?');">
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

    <main class="page-content">
        <div class="header-inner-content">
            
            <?php if ($result->num_rows > 0): ?>
                <div class="carousel-container">
                    <img src="images/ramo.png" alt="Ramo decorativo" class="decor-ramo top-left">
                    
                    <div class="carousel-viewport">
                        <div class="carousel-track">
                            <?php while($row = $result->fetch_assoc()) { 
                                // --- LÓGICA DE IMAGEM BLINDADA ---
                                // Define a imagem padrão (Logo)
                                $padrao = 'images/RV.png';

                                // Verifica FOTO DE PERFIL
                                // Se estiver vazio OU se for o valor antigo "default-user.png", usa a logo
                                $foto_perfil = $row['foto_perfil'];
                                if (empty($foto_perfil) || $foto_perfil == 'images/default-user.png') {
                                    $foto_perfil = $padrao;
                                }

                                // Verifica PROJETOS (Mesma lógica para as 4 fotos)
                                $p1 = (empty($row['foto_proj1']) || $row['foto_proj1'] == 'images/default-proj.png') ? $padrao : $row['foto_proj1'];
                                $p2 = (empty($row['foto_proj2']) || $row['foto_proj2'] == 'images/default-proj.png') ? $padrao : $row['foto_proj2'];
                                $p3 = (empty($row['foto_proj3']) || $row['foto_proj3'] == 'images/default-proj.png') ? $padrao : $row['foto_proj3'];
                                $p4 = (empty($row['foto_proj4']) || $row['foto_proj4'] == 'images/default-proj.png') ? $padrao : $row['foto_proj4'];
                            ?>
                                <div class="profile-card">
                                    <img src="images/coracaoeramos.png" class="decor-coracaoeramos top-right-on-card">

                                    <div class="profile-image">
                                        <img src="<?php echo $foto_perfil; ?>" alt="Foto de perfil" style="object-fit: cover;">
                                    </div>
                                    <div class="profile-text">
                                        <h3><?php echo htmlspecialchars($row['titulo_anuncio']); ?></h3>
                                        <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
                                        
                                        <p class="profile-contact">
                                            Contato: <?php echo htmlspecialchars($row['telefone']); ?><br>
                                            Instagram: <?php echo htmlspecialchars($row['instagram']); ?><br>
                                            E-mail: <?php echo htmlspecialchars($row['email']); ?>
                                        </p>

                                        <div class="profile-projects">
                                            <img src="<?php echo $p1; ?>" alt="Projeto 1">
                                            <img src="<?php echo $p2; ?>" alt="Projeto 2">
                                            <img src="<?php echo $p3; ?>" alt="Projeto 3">
                                            <img src="<?php echo $p4; ?>" alt="Projeto 4">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <button class="carousel-btn prev">&larr;</button>
                    <button class="carousel-btn next">&rarr;</button>
                    <img src="images/ramo.png" alt="Ramo decorativo" class="decor-ramo bottom-right">
                </div>

            <?php else: ?>
                <div style="text-align: center; width: 100%; margin-top: 50px;">
                    <p style="font-size: 1.5rem; color: #5C8070; font-weight: bold; font-family: 'Lucida Sans', sans-serif;">
                        Nenhum costureiro cadastrado ainda.
                    </p>
                </div>
            <?php endif; ?>

        </div>
        
        <img src="images/revesteupcycling.png" alt="Logo" class="decor-revesteupcycling">
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.querySelector('.carousel-track');
            // Se não houver track (sem cadastros), encerra o script para não dar erro no console
            if (!track) return;

            const items = Array.from(track.children);
            const nextButton = document.querySelector('.carousel-btn.next');
            const prevButton = document.querySelector('.carousel-btn.prev');
            let currentIndex = 0;
            
            const updateSlide = () => {
                const totalItems = track.children.length; 
                if (totalItems === 0) return;
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
            };

            nextButton.addEventListener('click', () => {
                const totalItems = track.children.length;
                currentIndex++;
                if (currentIndex >= totalItems) { currentIndex = 0; }
                updateSlide();
            });

            prevButton.addEventListener('click', () => {
                const totalItems = track.children.length;
                currentIndex--;
                if (currentIndex < 0) { currentIndex = totalItems - 1; }
                updateSlide();
            });
        });
    </script>
</body>
</html>