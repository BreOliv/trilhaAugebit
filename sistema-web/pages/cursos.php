<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluir Font Awesome para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Cursos Augebit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background:  #1a1a2e ;
            min-height: 100vh;
            color: #333;
            overflow: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 80px;
            background: black;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
            padding: 20px 0;
            gap: 30px;
            z-index: 100;
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

        .logo2 {
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
            text-decoration: none;
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

        .main-content {
            flex: 1;
            padding: 30px;
            backdrop-filter: blur(20px);
            border-radius: 30px 30px 30px 30px;
            margin: 20px 20px 20px 0;
            background-color: #f5f5f5;
        }

        .conteudo-container {
            /*  */
            flex-grow: 4;
            padding: 20px;
            height: 100vh;
            overflow: auto;
            /* box-sizing: border-box; */
            display: flex;
            justify-content: center;
            align-items: center;
            /* background-color: #000; deixa o fundo preto nas margens */
            }

        .header {
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-radius: 20px 20px 0 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-icon, .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-icon:hover, .notification-icon:hover {
            background: #e5e7eb;
            transform: scale(1.05);
        }

        .user-menu {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

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

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .courses-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<div>
     <!-- Main Content -->
    <div class="main-content">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img class="logo2" src="../img/logo2.png" alt="Logo" />
        </div>
        
        <div class="nav-item ">
            <i class="fas fa-th-large"> </i>
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

          <div class="conteudo-container">
    <div class="main-content">
        <div class="header">
            
                <h1>Cursos Augebit</h1>
            
            
            <div class="header-actions">
                <div class="search-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#6b7280">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>
                <div class="notification-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#6b7280">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                </div>
                <div class="user-menu">Nome Usuário ▼</div>
            </div>
        </div>

        <div class="content">
            <h2 class="section-title">Cursos mais Acessados</h2>
            
            <div class="courses-grid">
                <div class="course-card blue">
                    <div class="course-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
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
                            <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>
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
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
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
                            <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="course-title">Prototipagem e Impressão 3D</div>
                        <div class="course-description">Aprenda sobre impressão 3D profissional</div>
                    </div>
                </div>

                <div class="course-card add">+</div>
            </div>

            <div class="recent-section">
                <h3 class="recent-title">Criados recentemente</h3>
                <button class="create-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    CRIAR 
                </button>
            </div>

            <div class="recent-list">
                <?php
                $recentCourses = [
                    "Design de Equipamentos Industriais",
                    "Design de Equipamentos Industriais", 
                    "Design de Equipamentos Industriais",
                    "Design de Equipamentos Industriais"
                ];

                foreach($recentCourses as $course):
                ?>
                <div class="recent-item">
                    <div class="recent-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <div class="recent-info">
                        <div class="recent-name"><?php echo $course; ?></div>
                        <div class="recent-time">Criado agora</div>
                    </div>
                    <div class="recent-tag">Design</div>
                    <button class="more-btn">⋮</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>
    </div>
    </div>

    <script>
        // Adicionar interatividade aos cards
        document.querySelectorAll('.course-card:not(.add)').forEach(card => {
            card.addEventListener('click', function() {
                console.log('Curso selecionado:', this.querySelector('.course-title').textContent);
            });
        });

        // Adicionar funcionalidade ao botão de criar
        document.querySelector('.create-btn').addEventListener('click', function() {
            alert('Funcionalidade de criar curso em desenvolvimento!');
        });

        // Adicionar funcionalidade aos botões de mais opções
        document.querySelectorAll('.more-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                alert('Menu de opções');
            });
        });

        // Funcionalidade do card de adicionar
        document.querySelector('.course-card.add').addEventListener('click', function() {
            alert('Adicionar novo curso');
        });
    </script>
</body>
</html>