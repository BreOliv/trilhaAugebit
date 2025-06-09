<?php
session_start();


$nome = $_SESSION['nome_usuario'] ?? 'Visitante';

// Função de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Progresso de conclusão dos cursos
$totalAlunos = 100;
$concluidos = 72;
$progresso = ($concluidos / $totalAlunos) * 100;

// Cursos aplicados e quantidade de alunos
$cursos = ['NR-10', 'NR-35', 'Espaço Confinado', 'Soldagem', 'Eletricista'];
$quantidades = [25, 20, 18, 22, 15];

// Desempenho por mês (simulado)
$meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
$concluidosPorMes = [5, 8, 12, 15, 20, 12];

// Simulação dos dados
$totalAulas = 50;
$aulasConcluidas = 40;
$progressoAulas = ($aulasConcluidas / $totalAulas) * 100;

$totalAlunos = 100;
$alunosComFrequencia = 85;
$frequencia = ($alunosComFrequencia / $totalAlunos) * 100;


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trilha Augebit - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        /* a {
            text-decoration: none;
            color: white;
        } */

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
        /* Configuração dos gráficos */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 2fr);
            grid-gap: 20px;
            max-width: 100%;
        }
        canvas {
            width: 400px !important;
            height: 400px !important;
        }

        .grafico-box {
            background-color: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            }

            .grafico-custom {
            width: 800px !important;
            display: flex;
            flex-direction: column;
            }

            .grafico-custom h2 {
            text-align: center;
            font-size: 24px;
            color: #000;
            font-family: Poppins, sans-serif;
            }

            .barra-container {
            width: 100%;
            height: 25px;
            background-color: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .barra-preenchida {
            height: 100%;
            background: linear-gradient(90deg, #4848D8, #6E6EFF);
            transition: width 0.5s ease;
            }               
             .resumo-container {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    margin-bottom: 5px;
                    justify-items: center;
                    }

                    .card-resumo {
                    flex: 1;
                    background: white;
                    padding: 20px;
                    border-radius: 15px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    text-align: center;
                    }

                    .card-resumo h3 {
                    font-size: 18px;
                    color: #555;
                    margin-bottom: 10px;
                    }

                    .card-resumo p {
                    font-size: 32px;
                    font-weight: bold;
                    color: #333;
                    }
            .container-processo{
                display: flex;
                flex-direction: column;
            }
             .grafico-box1 {
            background-color: white;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 5px 0;
            }
        
        #grafico1{
            width: 90% !important;
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
        
        <div class="nav-item">
            <a href="cursos.php" title="Cursos">
            <i class="fas fa-folder"></i>
            </a>
        </div>
        
        <div class="nav-item active">
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
            <h1>Gráficos Augebit</h1>
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
        <!-- Contéudo: Gráficos -->

         <div class="grid-container">
        <div class="grafico-box grafico-custom"><h2 class="titulo-grafico">Alunos por Curso</h2>
        <canvas id="grafico1"></canvas></div>
        <div class="grafico-box"><canvas id="grafico2"></canvas></div>

        <!-- barra de porcentagem -->
        <div class="container-processo">
 <div class="grafico-box1">
    <h3 style="margin-bottom: 10px; text-align: center; font-size: 15px;">Progresso Geral</h3>
    <div class="barra-container">
      <div class="barra-preenchida" style="width: <?php echo $progresso; ?>%;"></div>
    </div>
    <p style="text-align: center; font-weight: bold; padding: 15px;"><?php echo round($progresso); ?>% </p>
  </div>
   <div class="grafico-box1">
    <h3 style="margin-bottom: 10px; text-align: center; font-size: 15px;">Aulas Concluídas</h3>
    <div class="barra-container">
      <div class="barra-preenchida" style="width: <?php echo $progressoAulas; ?>%;"></div>
    </div>
    <p style="text-align: center; font-weight: bold; padding: 15px;"><?php echo round($progressoAulas); ?>% </p>
  </div>
        <div class="grafico-box1">
    <h3 style="margin-bottom: 10px; text-align: center; font-size: 15px; ">Frequência cursos</h3>
    <div class="barra-container">
      <div class="barra-preenchida" style="width: <?php echo $frequencia; ?>%;"></div>
    </div>
    <p style="text-align: center; font-weight: bold; padding: 15px;"><?php echo round($frequencia); ?>% </p>
  </div>

     </div> 
        <!-- Quantidade de Alunos -->
<div class="resumo-container">
    <div class="card-resumo">
        <h3>Total de Alunos</h3>
        <p><?php echo $totalAlunos; ?></p>
    </div>

    <div class="card-resumo">
        <h3>Total de Cursos</h3>
        <p><?php echo count($cursos); ?></p>
    </div>
    </div>
    </div>
    </div>
 
  <script>
    const progresso = <?php echo $progresso; ?>;

// Plugin para exibir o texto no centro do gráfico
const centerTextPlugin = {
  id: 'centerText',
  beforeDraw(chart) {
    const { width, height, ctx } = chart;
    ctx.restore();

    const fontSize = (height / 100).toFixed(2);
    ctx.font = `${fontSize}em Segoe UI`;
    ctx.textBaseline = 'middle';
    ctx.textAlign = 'center';
    ctx.fillStyle = '#333'; // cor do texto

    const text = `${Math.round(progresso)}%`;
    const x = width / 2;
    const y = height / 2;

    ctx.fillText(text, x, y);
    ctx.save();
  }
};

new Chart(document.getElementById('grafico2'), {
  type: 'doughnut',
  data: {
    labels: ['Concluído', 'Em andamento'],
    datasets: [{
      data: [progresso, 100 - progresso],
      backgroundColor: ['#9999FF', '#4848D8']
    }]
  },
  options: {
    cutout: '70%', // define o tamanho do buraco no meio
    plugins: {
      legend: {
        display: true,
        position: 'bottom'
      },
      title: {
        display: true,
        text: 'Progresso dos Cursos',
        color: '#333',
        font: {
          size: 18
        }
      }
    }
  },
  plugins: [centerTextPlugin] // ativa o plugin personalizado
});

const cursos = <?php echo json_encode($cursos); ?>;
const quantidades = <?php echo json_encode($quantidades); ?>;

new Chart(document.getElementById('grafico1'), {
  type: 'bar',
  data: {
    labels: cursos,
    datasets: [{
      label: 'Alunos por curso',
      data: quantidades,
      backgroundColor: [
        '#6E6EFF', 
        '#0D0D0F', 
        '#9999FF', 
        '#8C99A3', 
        '#4848D8'
      ],
      borderRadius: 4,
      barThickness: 80
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false
      }
    },
     scales: {
      x: {
        grid: {
          display: false
        },
        ticks: {
          color: 'black',      // cor das legendas
          font: {
            size: 14,
            family: 'Poppins, sans-serif',
            weight: 'bold'
          },
          padding: 10
        }
      },
      y: {
        beginAtZero: true,
        grid: {
          display: false
        },
        ticks: {
          color: '#black',      // cor dos números do eixo Y
          font: {
            size: 14,
            family: 'Poppins, sans-serif',
            weight: 'bold'
          },
          padding: 10
        }
      }
    }
  }
});


    const meses = <?php echo json_encode($meses); ?>;
  const concluidosPorMes = <?php echo json_encode($concluidosPorMes); ?>;

  new Chart(document.getElementById('grafico3'), {
    type: 'line',
    data: {
      labels: meses,
      datasets: [{
        label: 'Concluídos por mês',
        data: concluidosPorMes,
        borderColor: '#3b82f6', // azul
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        fill: true,
        tension: 0.3, // curva suave
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          labels: {
            color: '#333',
            font: { size: 14 }
          }
        },
        title: {
          display: true,
          text: 'Desempenho Mensal - Cursos Concluídos',
          color: '#111',
          font: { size: 20, weight: 'bold' }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 2,
            color: '#555',
            font: { size: 12 }
          }
        },
        x: {
          ticks: {
            color: '#555',
            font: { size: 12 }
          }
        }
      }
    }
  });

  </script>

    </div>
    </body>