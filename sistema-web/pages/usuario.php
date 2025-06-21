<?php
session_start();
require_once '../conexao.php';

// Nome exibido no topo
$nome = $_SESSION['nome_usuario'] ?? 'Visitante';
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    die('Administrador não identificado.');
}

// Mensagem de sucesso
$mensagem = $_SESSION['mensagem_sucesso'] ?? '';
$tipo_mensagem = $mensagem ? 'success' : '';
unset($_SESSION['mensagem_sucesso']);

// Buscar dados atuais
$stmt = $pdo->prepare("SELECT * FROM cadastro_admin WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die('Administrador não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_mudancas'])) {
    $nome_admin = trim($_POST['nome_admin']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $genero = $_POST['genero'] ?? '';
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    try {
        if (empty($nome_admin) || empty($sobrenome) || empty($email)) {
            throw new Exception("Nome, sobrenome e email são obrigatórios.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        // Verifica se o e-mail já existe em outro admin
        $stmt = $pdo->prepare("SELECT id FROM cadastro_admin WHERE email = ? AND id != ?");
        $stmt->execute([$email, $admin_id]);
        if ($stmt->fetch()) {
            throw new Exception("Este e-mail já está sendo usado por outro administrador.");
        }

        // SQL base
        $sql = "UPDATE cadastro_admin SET nome_admin = ?, sobrenome = ?, email = ?, genero = ?";
        $dados = [$nome_admin, $sobrenome, $email, $genero];

        // Atualizar senha se solicitado
        if (!empty($nova_senha)) {
            if ($nova_senha !== $confirmar_senha) {
                throw new Exception("A confirmação da nova senha não confere.");
            }

            if ($senha_atual !== $admin['senha']) {
                throw new Exception("Senha atual incorreta.");
            }

            $sql .= ", senha = ?";
            $dados[] = $nova_senha;
        }

        $sql .= " WHERE id = ?";
        $dados[] = $admin_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($dados);

        $_SESSION['nome_usuario'] = $nome_admin;
        $nome = $nome_admin;

        $_SESSION['mensagem_sucesso'] = "Informações atualizadas com sucesso!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } catch (Exception $e) {
        $mensagem = $e->getMessage();
        $tipo_mensagem = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trilha Augebit - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: black;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 80px;
            background: black;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 30px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .logo2{
         width: 45px;
        height: 45px;   
        }

        .nav-item {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .logout-btn {
            margin-top: auto;
            color: #ff6b6b;
        }

        .logout-btn:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            background: #E3E3E3;
            backdrop-filter: blur(20px);
            border-radius: 30px 30px 30px 30px;
            margin: 20px 20px 20px 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: black;
            font-size: 32px;
            font-weight: 600;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-menu .icon {
            width: 45px;
            height: 45px;
            background: #9999FF;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu .icon:hover {
            background: #4848D8;
        }

        .user-info {
            background: #9999FF;
            padding: 10px 20px;
            border-radius: 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

         .content {
            flex: 1;
            display: flex;
            padding: 40px;
            gap: 40px;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            width: 320px;
            height: 800px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
            position: relative;
        }

        .profile-avatar::after {
            content: '✓';
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: #10b981;
            border: 3px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .profile-name {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .profile-role {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .profile-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-primary:hover {
            background: #5b5bd6;
            transform: translateY(-2px);
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .form-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            flex: 1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-input {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 200px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-cancel {
            padding: 12px 24px;
            border: 2px solid #d1d5db;
            background: white;
            color: #6b7280;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-save {
            padding: 12px 24px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }

        .btn-save:hover {
            background: #5b5bd6;
            transform: translateY(-2px);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .password-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .collapse-btn {
            background: none;
            border: none;
            color: #6366f1;
            font-weight: 500;
            cursor: pointer;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .password-fields {
            display: none;
        }

        .password-fields.show {
            display: block;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .content {
                flex-direction: column;
                padding: 20px;
                gap: 20px;
            }
            
            .profile-card {
                width: 100%;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }

        </style>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img class="logo2"  src="../img/logo2.png" alt=""></img>
        </div>
        
        <div class="nav-item active">
         <a href="../index.php" title="Cursos">
            <i class="fas fa-th-large"></i>
        </a>
        </div>
        
        <div class="nav-item">
            <a href="cursos.php" title="Cursos">
            <i class="fas fa-folder"></i>
            </a>
        </div>
        
        <div class="nav-item">
             <a href="grafico.php" title="Cursos">
            <i class="fas fa-chart-bar"></i>
            </a>
        </div>
        
        <div class="nav-item">
                     <a href="chat.php" title="Cursos">
            <i class="fas fa-file-alt"></i>
        </a>
        </div>
        
        <div class="nav-item">
         <a href="usuario.php" title="Cursos">
            <i class="fas fa-user"></i>
            </a>
        </div>
        
        <a href="login.php" class="nav-item logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Perfil Augebit</h1>
            <div class="user-menu">
                <div class="icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="user-info">
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($nome);?> </span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

                <div class="content">
                <!-- Profile Card -->
                <div class="profile-card">
                    <div class="profile-avatar">
                        <?= strtoupper(substr($admin['nome_admin'], 0, 1)) ?>
                    </div>
                    <div class="profile-name"><?= htmlspecialchars($admin['nome_admin'] . ' ' . $admin['sobrenome']) ?></div>
                    <div class="profile-role">Administrador(a)</div>
                    
                    <div class="profile-actions">
                        <button class="profile-btn btn-primary">
                           <i class="fa-solid fa-circle-info"></i> Informações Pessoal
                        </button>
                        <button class="profile-btn btn-secondary">
                            <i class="fa-solid fa-lock"></i> Login e Senha
                        </button>
                        <a href= "login.php" class="profile-btn btn-secondary">
                            <i class="fa-solid fa-right-from-bracket"></i> Sair
                        </a>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="form-section">
                    <h2 class="section-title">Informações Pessoal</h2>
                    
                    <?php if ($mensagem): ?>
                <div id="mensagem-flash" class="alert alert-<?= $tipo_mensagem === 'success' ? 'success' : 'error' ?>" style="transition: opacity 0.5s;">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>


                    <form method="POST" action="">
                        <!-- Gênero -->
                        <div class="form-group">
                            <label class="form-label">Gênero</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="genero" value="feminino" class="radio-input" 
                                           <?= $admin['genero'] === 'feminino' ? 'checked' : '' ?>>
                                    <label>Feminino</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="genero" value="masculino" class="radio-input"
                                           <?= $admin['genero'] === 'masculino' ? 'checked' : '' ?>>
                                    <label>Masculino</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="genero" value="outro" class="radio-input"
                                           <?= $admin['genero'] === 'outro' ? 'checked' : '' ?>>
                                    <label>Outro</label>
                                </div>
                            </div>
                        </div>

                        <!-- Nome e Sobrenome -->
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome_admin" class="form-input" 
                                           value="<?= htmlspecialchars($admin['nome_admin']) ?>" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Sobrenome</label>
                                    <input type="text" name="sobrenome" class="form-input" 
                                           value="<?= htmlspecialchars($admin['sobrenome']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" 
                                   value="<?= htmlspecialchars($admin['email']) ?>" required>
                        </div>

                        <!-- Seção de Senha -->
                        <div class="password-section">
                            <button type="button" class="collapse-btn" onclick="togglePasswordFields()">
                                <i class="fa-solid fa-lock"></i> Alterar Senha (Opcional)
                            </button>
                            <div class="password-fields" id="passwordFields">
                                <div class="form-group">
                                    <label class="form-label">Senha Atual </label>
                                    <input type="password" name="senha_atual" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nova Senha</label>
                                    <input type="password" name="nova_senha" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirme a Nova Senha</label>
                                    <input type="password" name="confirmar_senha" class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="window.location.reload()">
                                Cancelar Mudanças
                            </button>
                            <button type="submit" name="salvar_mudancas" class="btn-save">
                                Salvar Mudanças
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <script>
        function togglePasswordFields() {
            const fields = document.getElementById('passwordFields');
            const btn = document.querySelector('.collapse-btn');

            if (fields.classList.contains('show')) {
                fields.classList.remove('show');
                btn.innerHTML = '<i class="fa-solid fa-lock"></i> Alterar Senha (Opcional)';
            } else {
                fields.classList.add('show');
                btn.innerHTML = '<i class="fa-solid fa-lock"></i> Ocultar Campos de Senha';
            }
        }

        // Validação frontend
        document.querySelector('form').addEventListener('submit', function(e) {
            const novaSenha = document.querySelector('input[name="nova_senha"]').value;
            const confirmarSenha = document.querySelector('input[name="confirmar_senha"]').value;
            const senhaAtual = document.querySelector('input[name="senha_atual"]').value;

            if (novaSenha) {
                if (!senhaAtual) {
                    alert('Por favor, preencha a senha atual para alterar a senha.');
                    e.preventDefault();
                    return false;
                }

                if (novaSenha.length < 6) {
                    alert('A nova senha deve ter pelo menos 6 caracteres!');
                    e.preventDefault();
                    return false;
                }

                if (novaSenha !== confirmarSenha) {
                    alert('As senhas não conferem!');
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Mensagem flash some depois de 3 segundos
        document.addEventListener('DOMContentLoaded', function () {
            const mensagem = document.getElementById('mensagem-flash');
            if (mensagem) {
                setTimeout(() => {
                    mensagem.style.opacity = '0';
                    setTimeout(() => mensagem.remove(), 500);
                }, 3000);
            }
        });
    </script>

</body>