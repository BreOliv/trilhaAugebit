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

// Processar o formulário de criação do curso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_curso = $_POST['courseName'] ?? '';
    $subtitulo = $_POST['subtitle'] ?? '';
    $tempo = $_POST['duration'] ?? '';
    $modalidade = $_POST['modality'] ?? '';
    $local = $_POST['location'] ?? '';
    $data_limite = $_POST['deadline'] ?? null;
    $descricao = $_POST['description'] ?? '';

    // Variável para armazenar o nome da imagem
    $nome_imagem = null;

    // Verificar se o arquivo de imagem foi enviado e não houve erro
    if (isset($_FILES['img_curso']) && $_FILES['img_curso']['error'] === UPLOAD_ERR_OK) {
        $arquivoTmp = $_FILES['img_curso']['tmp_name'];
        $nomeOriginal = $_FILES['img_curso']['name'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        // Validar extensão se quiser (ex: jpg, png)
        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($extensao, $extPermitidas)) {
            // Criar nome único para evitar sobrescrever arquivos
            $nome_imagem = uniqid('curso_') . '.' . $extensao;
            $destino = '../uploads/' . $nome_imagem;

        }
    }

    try {
        // Inserir no banco, incluindo o nome da imagem (que pode ser NULL)
        $stmt = $pdo->prepare("INSERT INTO cursos_web (nome_curso, subtitulo, tempo, modalidade, local, data_limite, descricao, img_curso, criado_em) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nome_curso, $subtitulo, $tempo, $modalidade, $local, $data_limite, $descricao, $nome_imagem]);

        echo "<script>alert('Curso criado com sucesso!'); window.location.href='cursos.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Erro ao salvar o curso: " . $e->getMessage();
    }
}

// Buscar os cursos mais recentes
try {
    $stmt = $pdo->query("SELECT * FROM cursos_web ORDER BY criado_em DESC LIMIT 5");
    $recentCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar cursos: " . $e->getMessage();
    $recentCourses = [];
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

        /* Cursos mais Acessados */
        .content {
            padding: 30px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 25px;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }

        .course-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .course-card.blue {
            background: linear-gradient(135deg, #4c63d2, #5a73e8);
            color: white;
        }

        .course-card.purple {
            background: linear-gradient(135deg, #7c3aed, #8b5cf6);
            color: white;
        }

        .course-card.light-blue {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .course-card.gray {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
        }

        .course-card.add {
            border: 2px dashed #d1d5db;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 48px;
        }

        .course-card.add:hover {
            border-color: #4c63d2;
            color: #4c63d2;
        }
        
        /*Cursos mais Acessados*/

         .course-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .course-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .course-description {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.4;
        }

        .recent-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .recent-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .create-btn {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .recent-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            height: auto;
            width: auto;
            padding: 30px;
        }
        .recent-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .recent-item:hover {
            background: #f9fafb;
        }

        .recent-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }

        .recent-info {
            flex: 1;
        }

        .recent-name {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .recent-time {
            font-size: 14px;
            color: #6b7280;
        }

        .recent-tag {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-right: 15px;
        }

        .more-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f3f4f6;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .more-btn:hover {
            background: #e5e7eb;
        }

        /* Modal */
        .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: flex-start;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                padding-top: 80px;
                z-index: 1000;
            }

            .modal-overlay.modal-visible {
                opacity: 1;
                visibility: visible;
            }

            .modal-container {
                background: white;
                border-radius: 16px;
                width: 90%;
                max-width: 1000px;
                padding: 24px;
                position: relative;
                transform: translateY(30px);
                transition: transform 0.3s ease;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                 max-height: 80vh; /* Modal no máximo 80% da altura da tela */
                overflow-y: auto;
                  scrollbar-width: none; /* Firefox */
                 -ms-overflow-style: none; /* IE antigo */
            }
            .modal-container::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }

            .modal-overlay.modal-visible .modal-container {
                transform: translateY(0);
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
            }

            .modal-title {
                font-size: 20px;
                font-weight: 600;
                color: #333;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .edit-icon {
                color: #667eea;
                cursor: pointer;
            }

            .close-btn {
                background: none;
                border: none;
                font-size: 24px;
                color: #999;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: color 0.2s ease;
            }

            .close-btn:hover {
                color: #666;
            }

            .modal-content {
                display: grid;
                gap: 20px;
            }

            .upload-area {
                grid-column: 1 / -1; /* Faz ela pegar todas as colunas do grid */
                margin: 0 auto;      /* Centraliza horizontalmente */
                width: 900px;   /* Largura máxima do quadrado */
                height: 200px;       /* Altura fixa do quadrado */
                border: 2px dashed #667eea;
                border-radius: 12px;
                padding: 40px 20px;
                text-align: center;
                background: #f8f9ff;
                margin-bottom: 20px;
                overflow: hidden;
            }

            .upload-icon {
                margin-bottom: 12px;
                color: #667eea;
            }

            .upload-btn {
                background: #667eea;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 500;
                transition: background 0.2s ease;
            }

            .upload-btn:hover {
                background: #5a67d8;
            }

            .image-preview {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 150px;
                }

        .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
        }

            .image-actions{
                padding: 10px;
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .form-group.full-width {
                grid-column: 1 / -1;
            }

            .form-label {
                font-size: 14px;
                font-weight: 600;
                color: #333;
            }

            .required {
                color: #e53e3e;
            }

            .form-input, .form-select, .form-textarea {
                padding: 12px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                font-size: 14px;
                transition: border-color 0.2s ease;
            }

            .form-input:focus, .form-select:focus, .form-textarea:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            .form-textarea {
                min-height: 80px;
                resize: vertical;
            }

            .form-actions {
                display: flex;
                gap: 12px;
                justify-content: flex-end;
                margin-top: 24px;
            }

            .btn {
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                border: none;
                font-size: 14px;
            }

            .btn-secondary {
                background: #f7fafc;
                color: #4a5568;
                border: 1px solid #e2e8f0;
            }

            .btn-secondary:hover {
                background: #edf2f7;
            }

            .btn-primary {
                background: #667eea;
                color: white;
            }

            .btn-primary:hover {
                background: #5a67d8;
            }

            .demo-trigger {
                position: absolute;
                top: 20px;
                left: 20px;
                background: #667eea;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
            }

            @media (max-width: 640px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
                
                .modal-container {
                    margin: 20px;
                    padding: 20px;
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
        
        <div class="nav-item">
            <a href="../index.php" title="Cursos">
            <i class="fas fa-th-large"></i>
    </a>
        </div>
        
        <div class="nav-item active">
            <i class="fas fa-folder"></i>
        </div>
        
        <div class="nav-item">
            <i class="fas fa-chart-bar"></i>
        </div>
        
        <div class="nav-item">
            <i class="fas fa-file-alt"></i>
        </div>
        
        <div class="nav-item">
            <i class="fas fa-user"></i>
        </div>
        
        <a href="pages/login.php" class="nav-item logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Cursos Augebit</h1>
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

        <!-- Cursos mais Acessados -->
         <div class="content">
            <h2 class="section-title">Cursos mais Acessados</h2>
            
            <div class="courses-grid">
                <div class="course-card blue">
                    <div class="course-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M469.3 19.3l23.4 23.4c25 25 25 65.5 0 90.5l-56.4 56.4L322.3 75.7l56.4-56.4c25-25 65.5-25 90.5 0zM44.9 353.2L299.7 98.3 413.7 212.3 158.8 467.1c-6.7 6.7-15.1 11.6-24.2 14.2l-104 29.7c-8.4 2.4-17.4 .1-23.6-6.1s-8.5-15.2-6.1-23.6l29.7-104c2.6-9.2 7.5-17.5 14.2-24.2zM249.4 103.4L103.4 249.4 16 161.9c-18.7-18.7-18.7-49.1 0-67.9L94.1 16c18.7-18.7 49.1-18.7 67.9 0l19.8 19.8c-.3 .3-.7 .6-1 .9l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0l64-64c.3-.3 .6-.7 .9-1l45.1 45.1zM408.6 262.6l45.1 45.1c-.3 .3-.7 .6-1 .9l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0l64-64c.3-.3 .6-.7 .9-1L496 350.1c18.7 18.7 18.7 49.1 0 67.9L417.9 496c-18.7 18.7-49.1 18.7-67.9 0l-87.4-87.4L408.6 262.6z"/></svg>
                        </svg>
                    </div>
                    <div>
                        <div class="course-title">Desenho Técnico Mecânico</div>
                        <div class="course-description">Aprenda desenho técnico mecânico com precisão industrial</div>
                    </div>
                </div>

                <div class="course-card purple">
                    <div class="course-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                        </svg>
                    </div>
                    <div>
                        <div class="course-title">Prototipagem e Impressão 3D</div>
                        <div class="course-description">Domine técnicas avançadas de prototipagem</div>
                    </div>
                </div>

                <div class="course-card light-blue">
                    <div class="course-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M352 96c0 14.3-3.1 27.9-8.8 40.2L396 227.4c-23.7 25.3-54.2 44.1-88.5 53.6L256 192c0 0 0 0 0 0s0 0 0 0l-68 117.5c21.5 6.8 44.3 10.5 68.1 10.5c70.7 0 133.8-32.7 174.9-84c11.1-13.8 31.2-16 45-5s16 31.2 5 45C428.1 341.8 347 384 256 384c-35.4 0-69.4-6.4-100.7-18.1L98.7 463.7C94 471.8 87 478.4 78.6 482.6L23.2 510.3c-5 2.5-10.9 2.2-15.6-.7S0 501.5 0 496l0-55.4c0-8.4 2.2-16.7 6.5-24.1l60-103.7C53.7 301.6 41.8 289.3 31.2 276c-11.1-13.8-8.8-33.9 5-45s33.9-8.8 45 5c5.7 7.1 11.8 13.8 18.2 20.1l69.4-119.9c-5.6-12.2-8.8-25.8-8.8-40.2c0-53 43-96 96-96s96 43 96 96zm21 297.9c32.6-12.8 62.5-30.8 88.9-52.9l43.7 75.5c4.2 7.3 6.5 15.6 6.5 24.1l0 55.4c0 5.5-2.9 10.7-7.6 13.6s-10.6 3.2-15.6 .7l-55.4-27.7c-8.4-4.2-15.4-10.8-20.1-18.9L373 393.9zM256 128a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
                        </svg>
                    </div>
                    <div>
                        <div class="course-title">Desenho Técnico Mecânico</div>
                        <div class="course-description">Curso avançado de desenho técnico</div>
                    </div>
                </div>

                <div class="course-card gray">
                    <div class="course-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128z"/></svg>
                        </svg>
                    </div>
                    <div>
                        <div class="course-title">Prototipagem e Impressão 3D</div>
                        <div class="course-description">Aprenda sobre impressão 3D profissional</div>
                    </div>
                </div>

                <div class="course-card add">+</div>
            </div>

     <!-- Seção Criados Recentemente -->
            <div class="recent-section">
                <h3 class="recent-title">Criados recentemente</h3>
                <button class="create-btn"  id="openModalBtn" onclick="openModal()" >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                    </svg>
                    Criar 
                </button>
            </div>

            <div class="recent-list">
                <?php if (!empty($recentCourses)): ?>
                    <?php foreach ($recentCourses as $curso): ?>
                        <div class="recent-item">
                            <div class="recent-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                            </div>
                            <div class="recent-info">
                                <div class="recent-name"><?= htmlspecialchars($curso['nome_curso']) ?></div>
                                <div class="recent-time">
                                    <?php
                                    $dataCriacao = date('Y-m-d', strtotime($curso['criado_em']));
                                    $dataHoje = date('Y-m-d');
                                    echo ($dataCriacao == $dataHoje) ? 'Criado agora' : date('d/m/Y H:i', strtotime($curso['criado_em']));
                                    ?>
                                </div>
                            </div>
                            <div class="recent-tag">Design</div>
                            <button class="more-btn">⋮</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum curso criado recentemente.</p>
                <?php endif; ?>
            </div>
        </div>
        </div>

       <!-- MODAL -->
<div class="modal-overlay" id="courseModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                Criar Novo Curso
                <span class="edit-icon">✏️</span>
            </h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>

 <form class="modal-content" id="courseForm" method="POST" action="" enctype="multipart/form-data">
  <div class="upload-area" style="cursor:pointer;">
    <div class="upload-content" id="uploadContent">
      <div class="upload-icon" id="uploadIcon">
        <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
        </svg>
      </div>
      <button type="button" class="upload-btn" id="uploadBtn">Adicionar Arquivo</button>
      <input type="file" id="fileInput" name="img_curso" style="display:none" accept="image/*" />
    </div>

    <!-- Preview da imagem -->
    <div id="previewContainer" style="margin-top:10px; text-align:center; position: relative;">
      <div class="image-preview" id="imagePreview" style="display:none;">
        <img 
          id="previewImg" 
          src="" 
          alt="Preview" 
          style="max-width: 100%; max-height: 200px; border-radius: 8px; cursor: pointer;" 
          onclick="changeImage()"
          title="Clique para trocar a imagem"
        >
        <button 
          type="button" 
          class="action-btn delete-btn" 
          onclick="deleteImage()" 
          title="Deletar imagem"
          style="
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            font-weight: bold;
            line-height: 20px;
            text-align: center;
            padding: 0;
          "
        >&times;</button>
      </div>
    </div>
  </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="courseName">
                        Nome do Curso<span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="courseName" 
                        name="courseName" 
                        class="form-input"
                        placeholder="Ex: Design de materiais industriais"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="subtitle">Subtítulo</label>
                    <input 
                        type="text" 
                        id="subtitle" 
                        name="subtitle" 
                        class="form-input"
                        placeholder="Ex: Fundamentos de design"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="duration">
                        Tempo<span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="duration" 
                        name="duration" 
                        class="form-input"
                        placeholder="Ex: 40 horas"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="modality">
                        Modalidade<span class="required">*</span>
                    </label>
                    <select id="modality" name="modality" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Online">Online</option>
                        <option value="Híbrido">Híbrido</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Unidade Local</label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        class="form-input"
                        placeholder="Opcional"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="deadline">Data Limite</label>
                    <input 
                        type="date" 
                        id="deadline" 
                        name="deadline" 
                        class="form-input"
                    >
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label" for="description">
                    Descrição<span class="required">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-textarea"
                    placeholder="Descrição do curso"
                    required
                ></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    CANCELAR
                </button>
                <button type="submit" class="btn btn-primary">
                    CRIAR
                </button>
            </div>
        </form>
    </div>
</div>

       <script>
    // Interatividade nos cards
    document.querySelectorAll('.course-card:not(.add)').forEach(card => {
        card.addEventListener('click', function() {
            console.log('Curso selecionado:', this.querySelector('.course-title').textContent);
        });
    });

    // Botões de mais opções
    document.querySelectorAll('.more-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            alert('Menu de opções');
        });
    });

    // Card de adicionar novo curso
    document.querySelector('.course-card.add').addEventListener('click', function() {
        openModal();
    });

    // Modal
    const modal = document.getElementById('courseModal');
    const openModalBtn = document.getElementById('openModalBtn');

    function openModal() {
        modal.classList.add('modal-visible');
    }

    function closeModal() {
        modal.classList.remove('modal-visible');
    }

    // Fechar modal ao clicar no botão de fechar
    const closeBtn = modal.querySelector('.close-btn');
    closeBtn.addEventListener('click', closeModal);

    // Fechar modal clicando fora
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Upload de imagem
    const fileInput = document.getElementById('fileInput');
    const previewImg = document.getElementById('previewImg');
    const imagePreview = document.getElementById('imagePreview');
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadBtn = document.getElementById('uploadBtn');

    // Botão "Adicionar Arquivo" abre o seletor de arquivos
    uploadBtn.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'inline-block';  // Mostra preview + botão delete
                uploadIcon.style.display = 'none';            // Esconde o ícone
                uploadBtn.style.display = 'none';             // Esconde o botão
            };
            reader.readAsDataURL(file);
        }
    });

    function changeImage() {
        fileInput.click();
    }

    function deleteImage() {
        if (confirm('Tem certeza que deseja excluir a imagem?')) {
            previewImg.src = '';
            fileInput.value = '';
            imagePreview.style.display = 'none';
            uploadIcon.style.display = 'block';
            uploadBtn.style.display = 'inline-block';
        }
    }
</script>

    </body>