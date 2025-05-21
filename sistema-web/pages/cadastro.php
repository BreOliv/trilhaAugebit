<?php
// Arquivo: cadastro.php
// Este arquivo contém o formulário de cadastro com HTML/CSS e processamento PHP

// Verificar se foi especificado algum erro na URL (para redirecionar de volta com mensagem)
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';
$mensagem_erro = '';



switch ($erro) {
    case 'email_existente':
        $mensagem_erro = 'Email já cadastrado no sistema!';
        break;
    case 'campos_obrigatorios':
        $mensagem_erro = 'Todos os campos são obrigatórios!';
        break;
    case 'erro_cadastro':
        $mensagem_erro = 'Erro ao cadastrar! Tente novamente.';
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
    <style>
        /* Reset e estilos gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }
        
        body {
            background-color: #f0f0f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        /* Container principal */
        .container {
            display: flex;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 90%;
            max-width: 1000px;
            overflow: hidden;
        }
        
        /* Seção da esquerda */
        .left-section {
            background-color: #fff;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .left-section .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .illustration {
            background-color: #f5f5ff;
            border-radius: 50%;
            width: 340px;
            height: 340px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .illustration img {
            max-width: 100%;
            height: auto;
        }
        
        .left-text {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            text-align: center;
        }
        
        /* Seção da direita */
        .right-section {
            background-color: #f0f0f4;
            padding: 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .title {
            font-size: 28px;
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            text-align: center;
        }
        
        /* Formulário */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .name-row {
            display: flex;
            gap: 15px;
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #6c63ff;
        }
        
        .gender-group {
            margin: 10px 0;
        }
        
        .gender-title {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .gender-options {
            display: flex;
            gap: 20px;
        }
        
        .gender-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .radio-input {
            width: 18px;
            height: 18px;
            accent-color: #6c63ff;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c63ff;
        }
        
        .terms {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }
        
        .terms input {
            width: 16px;
            height: 16px;
            accent-color: #6c63ff;
        }
        
        .terms a {
            color: #6c63ff;
            text-decoration: none;
        }
        
        .submit-btn {
            background-color: #6c63ff;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            background-color: #5a52d5;
        }
        
        .login-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        
        .login-link a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Logotipo no canto superior direito */
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            z-index: 10;
        }
        
        body {
            position: relative;
        }
        
        .global-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            z-index: 100;
        }
        
        /* Mensagem de erro */
        .alert-error {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            color: #b71c1c;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .left-section, .right-section {
                width: 100%;
                padding: 30px;
            }
            
            .left-section {
                display: none;
            }
            
            .name-row {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Logo global no canto superior direito (fora do container para garantir visibilidade) -->
    <div class="global-logo">
        <img src="../img/logo2.png" alt="Logo do Sistema" style="width: 100%; height: auto;">
    </div>

    <div class="container">
        <!-- Seção da esquerda com ilustração -->
        <div class="left-section">
            <div class="content">
                <div class="illustration">
                    <img src="../img/singup.png" alt="Ilustração de cadastro">
                </div>
                <div class="left-text">
                    <p>Preencha seus dados para criar seu acesso e impulsionar o desenvolvimento da equipe.</p>
                </div>
            </div>
        </div>
        
        <!-- Seção da direita com formulário -->
        <div class="right-section">
            <h1 class="title">Cadastro de Administrador</h1>
            <p class="subtitle">Registre-se para gerenciar cursos e acompanhar o desenvolvimento da sua equipe.</p>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alert-error">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="../cadastrar.php">
                
                <div class="name-row">
                    <div class="form-group">
                        <input type="text" name="nome_admin" class="form-control" placeholder="Nome" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="sobrenome" class="form-control" placeholder="Sobrenome" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                
                <div class="gender-group">
                    <div class="gender-title">Gênero</div>
                    <div class="gender-options">
                        <div class="gender-option">
                            <input type="radio" id="feminino" name="genero" value="feminino" class="radio-input" checked>
                            <label for="feminino">Feminino</label>
                        </div>
                        
                        <div class="gender-option">
                            <input type="radio" id="masculino" name="genero" value="masculino" class="radio-input">
                            <label for="masculino">Masculino</label>
                        </div>
                        
                        <div class="gender-option">
                            <input type="radio" id="outro" name="genero" value="outro" class="radio-input">
                            <label for="outro">Outro</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group password-container">
                    <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                </div>
                
                <div class="terms">
                    <input type="checkbox" id="termos" name="termos" required>
                    <label for="termos">Li e aceito os termos da <a href="#">Política de Privacidade</a></label>
                </div>
                
                <button type="submit" class="submit-btn">Cadastrar</button>
                
                <div class="login-link">
                    <p>Já possui uma conta? <a href="./login.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('senha');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>
</html>