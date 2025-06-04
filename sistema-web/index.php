<?php
session_start();

$nome = $_SESSION['nome_usuario'] ?? 'Visitante';

// Simulando dados do usuário
$usuario = [
    'nome' => 'Nome Usu.',
    'progresso' => 75,
    'cursos' => [
        [
            'nome' => 'Desenho Técnico Mecânico',
            'descricao' => 'Crie e interprete desenhos mecânicos com precisão profissional',
            'cor' => 'bg-gradient-to-br from-blue-500 to-purple-600'
        ],
        [
            'nome' => 'Prototipagem e Impressão 3D',
            'descricao' => 'Transforme ideias em protótipos físicos usando impressão 3D',
            'cor' => 'bg-gradient-to-br from-purple-500 to-pink-500'
        ]
    ],
    'chat_duvidas' => [
        ['curso' => 'Nome do curso', 'funcionario' => 'Nome do funcionário', 'hora' => '21:55'],
        ['curso' => 'Nome do curso', 'funcionario' => 'Nome do funcionário', 'hora' => '21:55'],
        ['curso' => 'Nome do curso', 'funcionario' => 'Nome do funcionário', 'hora' => '21:55'],
        ['curso' => 'Nome do curso', 'funcionario' => 'Nome do funcionário', 'hora' => '21:55']
    ]
];

// Função de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Função para gerar calendário do mês atual
function gerarCalendario() {
    $hoje = date('j');
    $mes = date('n');
    $ano = date('Y');
    $primeiroDiaMes = mktime(0, 0, 0, $mes, 1, $ano);
    $diasNoMes = date('t', $primeiroDiaMes);
    $primeiroDiaSemana = date('w', $primeiroDiaMes);
    
    $calendario = [];
    $dia = 1;
    
    // Dias do mês anterior
    if ($primeiroDiaSemana > 0) {
        $mesAnterior = $mes - 1;
        $anoAnterior = $ano;
        if ($mesAnterior == 0) {
            $mesAnterior = 12;
            $anoAnterior--;
        }
        $diasMesAnterior = date('t', mktime(0, 0, 0, $mesAnterior, 1, $anoAnterior));
        for ($i = $primeiroDiaSemana - 1; $i >= 0; $i--) {
            $calendario[] = ['dia' => $diasMesAnterior - $i, 'atual' => false, 'hoje' => false];
        }
    }
    
    // Dias do mês atual
    for ($dia = 1; $dia <= $diasNoMes; $dia++) {
        $calendario[] = [
            'dia' => $dia, 
            'atual' => true, 
            'hoje' => $dia == $hoje,
            'destaque' => in_array($dia, [8, 25]) // Dias em destaque
        ];
    }
    
    // Completar com dias do próximo mês se necessário
    while (count($calendario) % 7 != 0) {
        $calendario[] = ['dia' => ++$dia - $diasNoMes, 'atual' => false, 'hoje' => false];
    }
    
    return $calendario;
}

$calendario = gerarCalendario();
$mesAtual = date('M');
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

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            height: calc(100vh - 200px);
            max-width: 100%;
            overflow: hidden;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .right-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Progress Card */
        .progress-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .progress-ring {
            width: 120px;
            height: 120px;
            transform: rotate(-90deg);
        }

        .progress-ring circle {
            fill: none;
            stroke-width: 8;
        }

        .progress-bg {
            stroke: #e5e7eb;
        }

        .progress-fill {
            stroke: #4c6ef5;
            stroke-linecap: round;
            stroke-dasharray: 314;
            stroke-dashoffset: 78.5; /* 75% progress */
            transition: stroke-dashoffset 1s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
        }

        .progress-info h3 {
            color: #1f2937;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .progress-legend {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6b7280;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            background: #4c6ef5;
            border-radius: 50%;
        }

        /* Courses Section */
        .courses-section {
            flex: 1;
            display: grid;
            grid-template-columns: 200px 1fr 1fr;
            gap: 20px;
            min-height: 300px;
            overflow: hidden;
        }

        .add-course-card {
            background: rgba(255, 255, 255, 0.1);
            border: 2px dashed black;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 300px;

        }

        .add-course-card:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #9999FF;
        }

        .add-course-card i {
            font-size: 40px;
            color: black;
        }
        .add-course-card i:hover {
            font-size: 40px;
            color: #9999FF;
        }

        .course-card {
            background: linear-gradient(135deg, #4c6ef5, #9775fa);
            border-radius: 20px;
            padding: 25px;
            color: white;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 300px;

        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .course-menu {
            position: absolute;
            top: 20px;
            right: 20px;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        .course-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .course-card h3 {
            font-size: 18px;
            margin-bottom: 8px;
            font-weight: 600;
            line-height: 1.3;
        }

        .course-card p {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .course-card.purple {
            background: linear-gradient(135deg, #9775fa, #f06292);
            height: 300px;

        }

        /* Calendar */
        .calendar-card {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            padding: 15px;
            color: white;
            max-height: 280px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-header h3 {
            font-size: 18px;
            font-weight: 500;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            font-size: 12px;
            justify-items: center;m
        }

        .calendar-day-header {
            text-align: center;
            font-size: 11px;
            color: #888;
            padding: 6px 0;
            font-weight: 500;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            min-height: 28px;
        }

        .calendar-day:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .calendar-day.other-month {
            color: #555;
        }

        .calendar-day.today {
            background: #4c6ef5;
            color: white;
            font-weight: 600;
        }

        .calendar-day.highlight {
            background: rgba(76, 110, 245, 0.3);
            color: white;
            border: 2px solid #4c6ef5;
        }

        /* Chat Card */
        .chat-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 15px;
            flex: 1;
            max-height: 260px;
            display: flex;
            flex-direction: column;
        }

        .chat-card::-webkit-scrollbar {
            width: 8px;
        }

        .chat-card::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 20px;
        }

        .chat-card::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-top: 15px;
        }

        .chat-header h3 {
            color: #1f2937;
            font-size: 18px;
        }

        .chat-toggle {
            width: 50px;
            height: 25px;
            background: #4c6ef5;
            border-radius: 25px;
            position: relative;
            cursor: pointer;
        }

        .chat-toggle::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 21px;
            height: 21px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #e0e7ff;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .chat-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #4c6ef5, #9775fa);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .chat-info {
            flex: 1;
        }

        .chat-info h4 {
            color: #1f2937;
            font-size: 14px;
            margin: 0;
        }

        .chat-info p {
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }

        .chat-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chat-time {
            color: #1f2937;
            font-size: 13px;
            font-weight: 500;
        }

        .chat-status {
            width: 20px;
            height: 20px;
            border: 2px solid #1f2937;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #1f2937;
        }

        .chat-button {
            background: #4c6ef5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .chat-button:hover {
            background: #3b5bdb;
        }


        @media (max-width: 1400px) {
            .dashboard-grid {
                grid-template-columns: 1.8fr 1fr;
                gap: 20px;
                justify-content: center;
                align-items: center;
            }
            
            .courses-section {
                grid-template-columns: 180px 1fr 1fr;
                gap: 15px;
            }
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .courses-section {
                grid-template-columns: 200px 1fr 1fr;
                min-height: auto;
            }
        }

        @media (max-width: 900px) {
            .courses-section {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .add-course-card {
                height: 120px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin: 10px;
                padding: 20px;
                border-radius: 20px;
            }
            
            .progress-card {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                    height: 250px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
        
    </style>

        <title>Gráfico de Rosca Simulado</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .body2 {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      background: white;
      border-radius: 20px;
      padding: 30px;
      display: flex;
      align-items: center;
      gap: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .chart-container {
      position: relative;
      width: 150px;
      height: 150px;
    }
    .chart-container .percent {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-weight: bold;
      font-size: 24px;
    }
    .legend {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 16px;
    }
    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: #7B61FF;
    }
  </style>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img class="logo2"  src="img/logo2.png" alt=""></img>
        </div>
        
        <div class="nav-item active">
            <i class="fas fa-th-large"></i>
        </div>
        
        <div class="nav-item">
            <a href="pages/cursos.php" title="Cursos">
            <i class="fas fa-folder"></i>
            </a>
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
            <h1>Trilha Augebit</h1>
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

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Section -->
            <div class="left-section">
                <!-- Progress Card -->
                 <div class="body 2">
       <div class="card">
    <div class="chart-container">
      <canvas id="progressoChart"></canvas>
      <div class="percent">75%</div>
    </div>
    <div class="legend">
      <div class="dot"></div>
      <div>
        <strong>Progresso</strong> dos cursos<br>em geral
      </div>
    </div>
  </div>
  </div>

                <!-- <div class="progress-card">
                    <div class="progress-circle">
                        <svg class="progress-ring">
                            <circle class="progress-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-fill" cx="60" cy="60" r="50"></circle>
                        </svg>
                        <div class="progress-text"><?php echo $usuario['progresso']; ?>%</div>
                    </div>
                    <div class="progress-info">
                        <div class="progress-legend">
                            <div class="legend-dot"></div>
                            <span>Progresso dos cursos em geral</span>
                        </div>
                    </div>
                </div> -->

                <!-- Courses Section -->
                <div class="courses-section">
                    <div class="add-course-card">
                        <i class="fas fa-plus"></i>
                    </div>
                    
                    <?php foreach ($usuario['cursos'] as $index => $curso): ?>
                    <div class="course-card <?php echo $index == 1 ? 'purple' : ''; ?>">
                        <div class="course-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div class="course-icon">
                            <i class="fas fa-<?php echo $index == 0 ? 'drafting-compass' : 'cube'; ?>"></i>
                        </div>
                        <h3><?php echo $curso['nome']; ?></h3>
                        <p><?php echo $curso['descricao']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Section -->
            <div class="right-section">
                <!-- Calendar -->
                <div class="calendar-card">
                    <div class="calendar-header">
                        <h3>Calendário</h3>
                        <span><?php echo $mesAtual; ?> <i class="fas fa-chevron-down"></i></span>
                    </div>
                    
                    <div class="calendar-grid">
                        <div class="calendar-day-header">D</div>
                        <div class="calendar-day-header">S</div>
                        <div class="calendar-day-header">T</div>
                        <div class="calendar-day-header">Q</div>
                        <div class="calendar-day-header">Q</div>
                        <div class="calendar-day-header">S</div>
                        <div class="calendar-day-header">S</div>
                        
                        <?php foreach ($calendario as $dia): ?>
                        <div class="calendar-day <?php 
                            echo !$dia['atual'] ? 'other-month' : '';
                            echo $dia['hoje'] ? ' today' : '';
                            echo isset($dia['destaque']) && $dia['destaque'] ? ' highlight' : '';
                        ?>">
                            <?php echo $dia['dia']; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Chat de Dúvidas -->
                <div class="chat-card">
        <div class="chat-header">
            <h3>Chat de dúvidas</h3>
            <div class="chat-toggle"></div>
        </div>

        <?php foreach (array_slice($usuario['chat_duvidas'], 0, 2) as $index => $chat): ?>
            
        <div class="chat-item">
            <div class="chat-avatar">
                <?php echo strtoupper(substr($chat['funcionario'], 0, 1)); ?>
            </div>
            <div class="chat-info">
                <h4><?php echo $chat['curso']; ?></h4>
                <p><?php echo $chat['funcionario']; ?></p>
            </div>
            <div class="chat-right">
                <span class="chat-time"><?php echo $chat['hora']; ?></span>
                <div class="chat-status">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
        
        <?php endforeach; ?>

    </div>

            </div>
        </div>
    </div>

    <script>
        // Animação do progresso circular
        document.addEventListener('DOMContentLoaded', function() {
            const progressFill = document.querySelector('.progress-fill');
            const progress = <?php echo $usuario['progresso']; ?>;
            const circumference = 2 * Math.PI * 50;
            const offset = circumference - (progress / 100) * circumference;
            
            setTimeout(() => {
                progressFill.style.strokeDashoffset = offset;
            }, 500);
        });

        // Toggle do chat
        document.querySelector('.chat-toggle').addEventListener('click', function() {
            this.classList.toggle('active');
        });

        // Hover effects para os cards
        document.querySelectorAll('.course-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px) scale(0.97)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
    <script>
    const progresso = 75;
    const restante = 100 - progresso;

    new Chart(document.getElementById("progressoChart"), {
      type: "doughnut",
      data: {
        labels: ["Concluído", "Restante"],
        datasets: [{
          data: [progresso, restante],
          backgroundColor: ["#7B61FF", "#D6CEFF"],
          borderWidth: 0,
        }]
      },
      options: {
        cutout: "80%",
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false }
        }
      }
    });
  </script>

</body>
</html>