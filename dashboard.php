<?php
session_start();
include 'config/database.php';

// 1. VERIFICAÇÃO DE SEGURANÇA
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$mensagem = "";

// --- FUNÇÃO AUXILIAR PARA APAGAR ARQUIVO FÍSICO ---
function deletarArquivoFisico($caminhoArquivo) {
    // Só apaga se o arquivo existir E se estiver dentro da pasta uploads (segurança para não apagar assets do site)
    if (!empty($caminhoArquivo) && file_exists($caminhoArquivo) && strpos($caminhoArquivo, 'images/uploads/') !== false) {
        unlink($caminhoArquivo); // Comando do PHP que deleta o arquivo
    }
}

// 2. PROCESSAR FORMULÁRIOS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'] ?? '';

    // --- SALVAR DADOS E UPLOAD (COM SUBSTITUIÇÃO DE ARQUIVO) ---
    if ($acao == 'salvar') {
        $titulo = $conn->real_escape_string($_POST['titulo']);
        $descricao = $conn->real_escape_string($_POST['descricao']);
        $telefone = $conn->real_escape_string($_POST['telefone']);
        $instagram = $conn->real_escape_string($_POST['instagram']);
        
        $conn->query("UPDATE usuarios SET titulo_anuncio='$titulo', descricao='$descricao', telefone='$telefone', instagram='$instagram' WHERE id=$id");

        // Busca dados atuais para saber se precisa apagar foto velha
        $sql_busca = "SELECT * FROM usuarios WHERE id=$id";
        $res_busca = $conn->query($sql_busca);
        $user_atual = $res_busca->fetch_assoc();

        // Função de upload inteligente
        function uploadFoto($inputName, $userId, $conn, $colunaBanco, $fotoAtual) {
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
                // 1. Apaga a foto anterior se existir
                deletarArquivoFisico($fotoAtual);

                // 2. Faz o upload da nova
                $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
                $novoNome = "user_" . $userId . "_" . $inputName . "_" . time() . "." . $ext;
                $destino = "images/uploads/" . $novoNome;
                
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $destino)) {
                    $conn->query("UPDATE usuarios SET $colunaBanco = '$destino' WHERE id=$userId");
                }
            }
        }

        uploadFoto('foto_perfil', $id, $conn, 'foto_perfil', $user_atual['foto_perfil']);
        uploadFoto('foto_proj1', $id, $conn, 'foto_proj1', $user_atual['foto_proj1']);
        uploadFoto('foto_proj2', $id, $conn, 'foto_proj2', $user_atual['foto_proj2']);
        uploadFoto('foto_proj3', $id, $conn, 'foto_proj3', $user_atual['foto_proj3']);
        uploadFoto('foto_proj4', $id, $conn, 'foto_proj4', $user_atual['foto_proj4']);

        $mensagem = "Perfil atualizado com sucesso!";
    }

    // --- EXCLUIR FOTO DE PERFIL (BANCO + ARQUIVO) ---
    elseif ($acao == 'excluir_perfil_foto') {
        // Busca o caminho atual
        $res = $conn->query("SELECT foto_perfil FROM usuarios WHERE id=$id");
        $row = $res->fetch_assoc();
        
        // Apaga o arquivo físico
        deletarArquivoFisico($row['foto_perfil']);

        // Limpa no banco
        $conn->query("UPDATE usuarios SET foto_perfil = NULL WHERE id=$id");
        $mensagem = "Foto de perfil removida.";
    }

    // --- EXCLUIR FOTOS SELECIONADAS (BANCO + ARQUIVO) ---
    elseif ($acao == 'excluir_fotos_selecionadas') {
        if (isset($_POST['imgs_delete'])) {
            // Busca dados atuais para ter os caminhos
            $res = $conn->query("SELECT * FROM usuarios WHERE id=$id");
            $row = $res->fetch_assoc();

            foreach($_POST['imgs_delete'] as $coluna) {
                if(in_array($coluna, ['foto_proj1', 'foto_proj2', 'foto_proj3', 'foto_proj4'])) {
                    // Apaga físico
                    deletarArquivoFisico($row[$coluna]);
                    // Apaga do banco
                    $conn->query("UPDATE usuarios SET $coluna = NULL WHERE id=$id");
                }
            }
            $mensagem = "Fotos selecionadas foram removidas.";
        }
    }

    // --- EXCLUIR CONTA COMPLETA (TUDO) ---
    elseif ($acao == 'excluir_conta') {
        // 1. Busca todas as fotos do usuário antes de deletar a conta
        $res = $conn->query("SELECT foto_perfil, foto_proj1, foto_proj2, foto_proj3, foto_proj4 FROM usuarios WHERE id=$id");
        $row = $res->fetch_assoc();

        // 2. Apaga todos os arquivos físicos
        deletarArquivoFisico($row['foto_perfil']);
        deletarArquivoFisico($row['foto_proj1']);
        deletarArquivoFisico($row['foto_proj2']);
        deletarArquivoFisico($row['foto_proj3']);
        deletarArquivoFisico($row['foto_proj4']);

        // 3. Deleta o usuário do banco
        $conn->query("DELETE FROM usuarios WHERE id=$id");
        
        // 4. Logout e Tchau
        session_destroy();
        echo "<script>alert('Sua conta e todos os seus dados foram excluídos.'); window.location.href='index.php';</script>";
        exit();
    }
}

// 3. BUSCAR DADOS (Recarrega dados atualizados para exibir na tela)
$sql = "SELECT * FROM usuarios WHERE id = $id";
$res = $conn->query($sql);
$user = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - Editar Perfil</title>
    <link rel="stylesheet" href="styles/globals.css">
    <style>
        body { background-color: #F5E7D4; }
        .dashboard-container { 
            max-width: 850px; 
            margin: 2rem auto; 
            background: #fff; 
            padding: 2.5rem; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        
        h1 { color: #5C8070; margin-bottom: 5px; }
        .subtitle { color: #333; font-weight: bold; display: block; margin-bottom: 10px; }
        .instructions { color: #666; margin-bottom: 25px; display: block; font-size: 0.95rem; }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; margin-bottom: 5px; }
        
        input[type="text"], textarea { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;
        }

        .btn-salvar { 
            background-color: #5C8070; color: white; border: none; padding: 15px; 
            font-size: 1.1rem; border-radius: 6px; cursor: pointer; margin-top: 25px; width: 100%; 
            transition: background 0.3s;
        }
        .btn-salvar:hover { background-color: #4a665a; }

        .btn-excluir {
            background-color: #B9735B; color: white; border: none; padding: 8px 15px;
            border-radius: 4px; cursor: pointer; font-size: 0.9rem; margin-top: 10px;
        }
        .btn-excluir:disabled { background-color: #ccc; cursor: not-allowed; }
        .btn-excluir:hover:not(:disabled) { background-color: #965f4b; }

        .btn-delete-account {
            background-color: #B9735B; color: white; border: none; padding: 15px;
            font-size: 1rem; border-radius: 6px; cursor: pointer; margin-top: 15px; width: 100%;
        }
        .btn-delete-account:hover { background-color: #965f4b; }

        .preview-box { 
            width: 100px; height: 100px; object-fit: cover; border-radius: 5px; margin: 10px auto; 
            border: 1px solid #ddd; display: flex; align-items: center; justify-content: center;
            background: #f0f0f0; color: #ccc; font-weight: bold;
        }
        img.preview-box { background: none; }

        .grid-fotos { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; }
        .foto-item { 
            flex: 1; min-width: 160px; 
            border: 1px dashed #ccc; padding: 15px; 
            border-radius: 8px; text-align: center;
            display: flex; flex-direction: column; align-items: center; justify-content: space-between;
        }
        
        input[type="file"] { margin-top: 10px; font-size: 0.9rem; width: 100%; }
        
        .select-box { margin-bottom: 10px; transform: scale(1.2); }

        .msg-sucesso { 
            background-color: #d4edda; color: #155724; padding: 15px; 
            border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;
            text-align: center; font-weight: bold;
        }
        .top-links { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .top-links a { text-decoration: none; color: #5C8070; font-weight: bold; }
        .top-links a.sair { color: #dc3545; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <div class="top-links">
            <a href="index.php" class="nav-link">← Voltar para o Site</a>
            <a href="logout.php" class="sair nav-link" onclick="return confirm('Tem certeza que deseja sair do sistema?');">Sair (Logout)</a>
        </div>

        <h1>Olá, <?php echo htmlspecialchars($user['nome']); ?>!</h1>
        <span class="subtitle">Você está logado como: <strong><?php echo ucfirst($user['tipo']); ?></strong></span>
        <p class="instructions">Edite abaixo como seu cartão aparecerá no site.</p>

        <?php if($mensagem): ?>
            <div class="msg-sucesso" id="msgFlash"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="formPerfil">
            <input type="hidden" name="acao" value="salvar">
            
            <label>Nome do Negócio/Costureiro(a):</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($user['titulo_anuncio']); ?>" required>

            <label>Descrição (Conte sua história):</label>
            <textarea name="descricao" rows="5"><?php echo htmlspecialchars($user['descricao']); ?></textarea>

            <label>Telefone / WhatsApp:</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($user['telefone']); ?>">

            <label>Instagram (@seu_insta):</label>
            <input type="text" name="instagram" value="<?php echo htmlspecialchars($user['instagram']); ?>">

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">
            
            <h3 style="color: #5C8070;">Gerenciar Fotos</h3>

            <label>Foto de Perfil (Destaque):</label>
            <div class="foto-item" style="width: 100%;">
                <div id="preview-perfil-container">
                    <?php 
                    // Verifica se existe E se o arquivo físico está lá
                    if($user['foto_perfil'] && file_exists($user['foto_perfil']) && $user['foto_perfil'] != 'images/default-user.png'): 
                    ?>
                        <img src="<?php echo $user['foto_perfil']; ?>" class="preview-box">
                        <button type="button" class="btn-excluir" onclick="excluirPerfilFoto()">Excluir Foto de Perfil</button>
                    <?php else: ?>
                        <div class="preview-box">Vazio</div>
                    <?php endif; ?>
                </div>
                <input type="file" name="foto_perfil" accept="image/*" onchange="previewImage(this, 'preview-perfil-container')">
            </div>

            <label>Fotos dos Projetos (Galeria):</label>
            <div style="text-align: right; margin-bottom: 5px;">
                <button type="button" id="btnExcluirSelecionadas" class="btn-excluir" disabled onclick="confirmarExclusaoFotos()">
                    Excluir Selecionadas
                </button>
            </div>

            <div class="grid-fotos">
                <?php 
                $projetos = ['foto_proj1' => 'Projeto 1', 'foto_proj2' => 'Projeto 2', 'foto_proj3' => 'Projeto 3', 'foto_proj4' => 'Projeto 4'];
                foreach($projetos as $col => $label): 
                ?>
                <div class="foto-item">
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <small><strong><?php echo $label; ?></strong></small>
                        <?php if($user[$col] && file_exists($user[$col]) && $user[$col] != 'images/default-proj.png'): ?>
                            <input type="checkbox" name="imgs_delete[]" value="<?php echo $col; ?>" class="select-box" onchange="verificarSelecao()">
                        <?php endif; ?>
                    </div>
                    
                    <div id="preview-<?php echo $col; ?>-container">
                        <?php if($user[$col] && file_exists($user[$col]) && $user[$col] != 'images/default-proj.png'): ?>
                            <img src="<?php echo $user[$col]; ?>" class="preview-box">
                        <?php else: ?>
                            <div class="preview-box">Vazio</div>
                        <?php endif; ?>
                    </div>
                    
                    <input type="file" name="<?php echo $col; ?>" accept="image/*" onchange="previewImage(this, 'preview-<?php echo $col; ?>-container')">
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn-salvar">Salvar Alterações</button>
        </form>

        <form method="POST" onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja EXCLUIR PERMANENTEMENTE seu perfil? Essa ação apagará todos os seus dados e fotos e não pode ser desfeita.');">
            <input type="hidden" name="acao" value="excluir_conta">
            <button type="submit" class="btn-delete-account">Excluir Perfil</button>
        </form>

    </div>

    <form id="formDelPerfil" method="POST" style="display:none;"><input type="hidden" name="acao" value="excluir_perfil_foto"></form>
    <form id="formDelFotos" method="POST" style="display:none;"><input type="hidden" name="acao" value="excluir_fotos_selecionadas"><div id="containerInputsDelete"></div></form>

    <script>
        const msg = document.getElementById('msgFlash');
        if (msg) setTimeout(() => { msg.style.display = "none"; }, 5000);

        let hasUnsavedChanges = false;
        const formPerfil = document.getElementById('formPerfil');

        formPerfil.addEventListener('change', () => hasUnsavedChanges = true);
        formPerfil.addEventListener('input', () => hasUnsavedChanges = true);

        document.querySelectorAll('a.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                if (hasUnsavedChanges) {
                    if (!confirm("Alterações não salvas, deseja sair sem salvá-las?")) {
                        e.preventDefault();
                    }
                }
            });
        });
        
        formPerfil.addEventListener('submit', () => hasUnsavedChanges = false);

        function previewImage(input, containerId) {
            const container = document.getElementById(containerId);
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.innerHTML = `<img src="${e.target.result}" class="preview-box">`;
                }
                reader.readAsDataURL(file);
                hasUnsavedChanges = true; // Marca como alterado ao selecionar foto
            }
        }

        function excluirPerfilFoto() {
            if(confirm("Deseja realmente excluir sua foto de perfil?")) {
                document.getElementById('formDelPerfil').submit();
            }
        }

        function verificarSelecao() {
            const checkboxes = document.querySelectorAll('input[name="imgs_delete[]"]:checked');
            document.getElementById('btnExcluirSelecionadas').disabled = checkboxes.length === 0;
        }

        function confirmarExclusaoFotos() {
            const checkboxes = document.querySelectorAll('input[name="imgs_delete[]"]:checked');
            if (checkboxes.length > 0) {
                if(confirm(`Deseja realmente excluir ${checkboxes.length} foto(s) selecionada(s)?`)) {
                    const container = document.getElementById('containerInputsDelete');
                    container.innerHTML = '';
                    checkboxes.forEach(chk => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'imgs_delete[]'; input.value = chk.value;
                        container.appendChild(input);
                    });
                    document.getElementById('formDelFotos').submit();
                }
            }
        }
    </script>

</body>
</html>