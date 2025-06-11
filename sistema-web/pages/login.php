<?php
session_start();
require_once __DIR__ . '/../conexao.php'; 

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica se o email existe na tabela login_admin
    $stmt = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificação direta (sem hash)
    if ($user && $senha === $user['senha']) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['nome_usuario'] = $user['nome_admin'];
        $_SESSION['admin_id'] = $user['id'];
        header("Location: ../index.php"); // Redireciona após login
        exit;
    } else {
        $loginError = "Usuário ou senha incorretos";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Augebit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6c5ce7;
            --light-primary: #8c7ae6;
            --dark-color: #2d3436;
            --light-color: #f5f6fa;
            --gray-color: #b2bec3;
            --bg-color: #ecf0f1;
        }
     

        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: ';
        }
        
        body {
            background-color: var(--bg-color);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            height: 80vh;
            display: flex;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            overflow: hidden;
        }
        
        .logo {
            position: absolute;
            top: 30px;
            left: 30px;
        }
        
        .logo img {
            width: 50px;
            height: auto;
        }
        
        .login-section {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: var(--bg-color);
        }
        
        .login-title {
            font-size: 32px;
            font-family: 'Poppins';
            color: var(--dark-color);
            margin-bottom: 5px;
            margin-left: 88px;
        }
        
        .login-subtitle {
            font-size: 16px;
            color: #636e72;
            margin-bottom: 25px;
            line-height: 1.5;
            margin-left: 15px;
            text-align: center;
            font-family: 'Poppins';
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            background-color: white;
            font-size: 16px;
            color: var(--dark-color);
            outline: none;
        }
        
        .form-control::placeholder {
            color: var(--gray-color);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-color);
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 6px;
            margin-top: 4px;
        }
        
        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 12px;
            font-family: 'Poppins';
        }
        
        .btn-primary {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            background-color: var(--light-primary);
        }
        
        .social-login {
            margin-top: 40px;
            text-align: center;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--gray-color);
            margin-bottom: 20px;
            font-family: 'Poppins';
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--gray-color);
        }
        
        .separator::before {
            margin-right: 10px;
        }
        
        .separator::after {
            margin-left: 10px;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #2d3436;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 20px;
            transition: transform 0.3s;
        }
        
        .social-icon:hover {
            transform: scale(1.1);
        }
        
        .signup-link {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #636e72;
            font-family: 'Poppins';
        }
        
        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .image-section {
            width: 50%;
            background-color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .image-container {
            max-width: 100%;
            margin-bottom: 30px;
        }
        
        .image-container img {
            width: 100%;
            max-width: 350px;
            height: auto;
        }
        
        .image-text {
            font-family: 'Poppins', light;
            font-size: 13px;
            font-weight: regular;
            text-align: center;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .error-message {
            color: #e74c3c;
            margin-top: 5px;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }
            
            .login-section,
            .image-section {
                width: 100%;
                padding: 30px;
            }
            
            .image-section {
                display: none;
            }
        }
    </style>
</head>
<body>
    <a href="#" class="logo">
        <img src="../img/Logo2.png" alt="Logo Augebit">
    </a>


    <div class="container">
        <div class="login-section">
            <h1 class="login-title">Bem-Vindo de volta!</h1>
            <p class="login-subtitle">Entre para gerenciar cursos e acompanhar o desenvolvimento da sua equipe.</p>
            
            <?php if ($loginError): ?>
                <div class="error-message"><?php echo $loginError; ?></div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
            <input type="email" name="usuario" class="form-control" placeholder="Email" required>                </div>
                <div class="form-group">
                    <div class="password-wrapper">
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
                        <span toggle="#senha" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div> 
                <div class="forgot-password">
                    <a href="#">Esqueci a senha</a>
                </div>
                <button type="submit" class="btn-primary">Entrar</button>
            </form>
            
            <div class="social-login">
                <div class="separator">ou continue com</div>
                <div class="social-icons">
                    <div class="social-icon">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-icon">
                        <i class="fab fa-apple"></i>
                    </div>
                </div>
            </div>
            
            <div class="signup-link">
                Não possui uma conta? <a href="./cadastro.php">Cadastre-se</a>
            </div>
        </div>
        
        <div class="image-section">
            <div class="image-container">
                <img src="../img/login.png" alt="Ilustração">
            </div>
            <div class="image-text">
                <h2>Acesse seu painel para gerenciar cursos e impulsionar o crescimento da equipe.</h2>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.querySelector('.toggle-password');
  const icons = togglePassword.querySelectorAll('i');
  
  // Remove todos os ícones extras (se houver mais de 1)
  icons.forEach((icon, idx) => {
    if (idx > 0) icon.remove();
  });

  const passwordInput = document.querySelector('#senha');
  // Script de toggle reescrevendo o innerHTML
  togglePassword.addEventListener('click', function() {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    this.innerHTML = isHidden
      ? '<i class="fas fa-eye-slash"></i>'
      : '<i class="fas fa-eye"></i>';
  });
});

    </script>
</body>
</html>