<?php
session_start();
include '../conexao.php';

$nome = $_SESSION['nome_usuario'] ?? 'Visitante';

// Função de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Definir usuário da sessão (em um sistema real, isso viria do login)
if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = 'Fernanda Dias'; // Usuário padrão
}

$database = new Database();
$db = $database->getConnection();

// Processar envio de mensagem
if ($_POST['action'] ?? '' === 'send_message') {
    $usuario = $_SESSION['usuario'];
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    if (!empty($mensagem)) {
        $query = "INSERT INTO mensagens (usuario, mensagem, data_envio) VALUES (:usuario, :mensagem, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->execute();
    }
    
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}

// Buscar mensagens
if ($_GET['action'] ?? '' === 'get_messages') {
    $query = "SELECT * FROM mensagens ORDER BY data_envio DESC LIMIT 50";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode(array_reverse($mensagens));
    exit;
}

// Buscar conversas (usuários únicos)
if ($_GET['action'] ?? '' === 'get_conversations') {
    $query = "SELECT DISTINCT usuario, MAX(data_envio) as ultima_mensagem 
              FROM mensagens 
              GROUP BY usuario 
              ORDER BY ultima_mensagem DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $conversas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($conversas);
    exit;
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
            <h1>Chat Augebit</h1>
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



</body>