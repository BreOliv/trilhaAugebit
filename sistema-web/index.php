<?php
// Configurações iniciais
session_start();
$nome_usuario = "Nome Usu.";
$data_atual = date("Y-m-d");
$mes_atual = date("m");
$ano_atual = date("Y");

// Dados para estatísticas (simulados)
$frequencia_cursos = 75;
$aulas_concluidas_1 = 75;
$aulas_concluidas_2 = 75;

// Função para gerar o calendário
function gerarCalendario($mes, $ano) {
    $primeiro_dia = mktime(0, 0, 0, $mes, 1, $ano);
    $num_dias = date("t", $primeiro_dia);
    $dia_semana_inicio = date("w", $primeiro_dia);
    
    // Ajuste para iniciar a semana no domingo
    if($dia_semana_inicio == 0) $dia_semana_inicio = 7;
    
    $dias_destaque = [8, 25]; // Dias com destaque (ex: dias com aulas)
    $dia_atual = date("j");
    
    $calendario = [];
    $semana = array_fill(0, 7, "");
    
    // Dias do mês anterior
    $dias_mes_anterior = date("t", mktime(0, 0, 0, $mes-1, 1, $ano));
    for($i = $dia_semana_inicio - 1; $i >= 0; $i--) {
        $semana[$i] = "<div class='dia outro-mes'>" . ($dias_mes_anterior - ($dia_semana_inicio - 1 - $i)) . "</div>";
    }
    
    // Dias do mês atual
    $dia_da_semana = $dia_semana_inicio;
    for($dia = 1; $dia <= $num_dias; $dia++) {
        $classe = 'dia';
        if(in_array($dia, $dias_destaque)) {
            $classe .= ' destaque';
        }
        if($dia == $dia_atual && $mes == date("m") && $ano == date("Y")) {
            $classe .= ' hoje';
        }
        if($dia == 28) {
            $classe .= ' selecionado';
        }
        
        $semana[$dia_da_semana] = "<div class='$classe'>$dia</div>";
        $dia_da_semana++;
        
        if($dia_da_semana > 6 || $dia == $num_dias) {
            // Preencher dias restantes da semana com o próximo mês
            if($dia_da_semana <= 6 && $dia == $num_dias) {
                for($i = $dia_da_semana; $i <= 6; $i++) {
                    $semana[$i] = "<div class='dia outro-mes'>" . ($i - $dia_da_semana + 1) . "</div>";
                }
            }
            
            $calendario[] = $semana;
            $semana = array_fill(0, 7, "");
            $dia_da_semana = 0;
        }
    }
    
    return $calendario;
}

$calendario = gerarCalendario(date("m"), date("Y"));

// Lista de cursos
$cursos = [
    [
        "titulo" => "Desenho Técnico Mecânico",
        "descricao" => "Crie e interprete desenhos mecânicos com precisão profissional."
    ],
    [
        "titulo" => "Prototipagem e Impressão 3D",
        "descricao" => "Transforme ideias em protótipos físicos usando impressão 3D."
    ]
];

// Lista de dúvidas/mensagens
$duvidas = [
    ["curso" => "Nome do curso", "funcao" => "Nome da Funcionalidade", "horario" => "21:55"],
    ["curso" => "Nome do curso", "funcao" => "Nome da Funcionalidade", "horario" => "21:55"],
    ["curso" => "Nome do curso", "funcao" => "Nome da Funcionalidade", "horario" => "21:55"],
    ["curso" => "Nome do curso", "funcao" => "Nome da Funcionalidade", "horario" => "21:55"]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trilha Augebit - Dashboard</title>
    <style>
        :root {
            --cor-primaria: #6c5ce7;
            --cor-secundaria: #a29bfe;
            --cor-fundo: #f5f6fa;
            --cor-texto: #2d3436;
            --cor-borda: #dfe6e9;
            --cor-destaque: #6c5ce7;
            --cor-branco: #ffffff;
            --borda-radius: 12px;
            --sombra: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
            display: flex;
        }
        
        .sidebar {
            width: 80px;
            height: 100vh;
            background-color: var(--cor-branco);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            position: fixed;
            left: 0;
            top: 0;
        }
        
        .sidebar .logo {
            width: 40px;
            height: 40px;
            background-color: var(--cor-primaria);
            border-radius: 10px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .sidebar .menu-item {
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            cursor: pointer;
            color: var(--cor-texto);
            opacity: 0.7;
            transition: all 0.3s;
        }
        
        .sidebar .menu-item:hover {
            background-color: var(--cor-secundaria);
            color: white;
            opacity: 1;
        }
        
        .main-content {
            flex: 1;
            margin-left: 80px;
            padding: 20px;
            width: calc(100% - 80px);
        }
        
        .dashboard {
            background-color: var(--cor-branco);
            border-radius: var(--borda-radius);
            padding: 20px;
            box-shadow: var(--sombra);
            display: grid;
            grid-template-columns: 6fr 4fr;
            grid-gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            grid-column: 1 / span 2;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .user-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .action-button {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--cor-fundo);
            border: none;
            cursor: pointer;
            color: var(--cor-texto);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            background-color: var(--cor-fundo);
            padding: 8px 16px;
            border-radius: 20px;
            gap: 8px;
            cursor: pointer;
        }
        
        .statistics-section {
            background-color: var(--cor-fundo);
            border-radius: var(--borda-radius);
            padding: 20px;
            grid-column: 1;
        }
        
        .stat-card {
            background-color: var(--cor-branco);
            border-radius: var(--borda-radius);
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: var(--sombra);
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #575fcf;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #e6e9f0;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background-color: var(--cor-primaria);
            border-radius: 5px;
        }
        
        .calendar-section {
            background-color: #222;
            color: var(--cor-branco);
            border-radius: var(--borda-radius);
            padding: 20px;
            grid-column: 2;
            grid-row: 1;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-days {
            display: flex;
            justify-content: space-around;
            margin-bottom: 10px;
        }
        
        .calendar-days span {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 12px;
        }
        
        .dia {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
        }
        
        .dia.outro-mes {
            opacity: 0.3;
        }
        
        .dia.destaque {
            background-color: var(--cor-primaria);
            color: white;
        }
        
        .dia.hoje {
            border: 2px solid var(--cor-primaria);
        }
        
        .dia.selecionado {
            background-color: #333;
            border: 2px solid var(--cor-primaria);
            color: white;
        }
        
        .courses-section {
            grid-column: 1 / span 2;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .course-card {
            background-color: var(--cor-secundaria);
            border-radius: var(--borda-radius);
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 200px;
            box-shadow: var(--sombra);
            transition: transform 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
        }
        
        .course-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .course-card p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .empty-card {
            background-color: var(--cor-fundo);
            border: 2px dashed var(--cor-borda);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--cor-borda);
        }
        
        .chat-section {
            background-color: var(--cor-fundo);
            border-radius: var(--borda-radius);
            padding: 20px;
            grid-column: 2;
            grid-row: 2;
        }
        
        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .toggle-container {
            width: 44px;
            height: 24px;
            background-color: var(--cor-primaria);
            border-radius: 12px;
            position: relative;
            cursor: pointer;
        }
        
        .toggle-switch {
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            right: 2px;
            top: 2px;
            transition: transform 0.3s;
        }
        
        .message {
            background-color: var(--cor-branco);
            border-radius: var(--borda-radius);
            padding: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .message-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .message-avatar {
            width: 30px;
            height: 30px;
            background-color: var(--cor-secundaria);
            border-radius: 50%;
        }
        
        .message-content {
            font-size: 14px;
        }
        
        .message-content .course-name {
            font-weight: 600;
        }
        
        .message-content .function-name {
            color: #555;
            font-size: 12px;
        }
        
        .message-time {
            font-size: 12px;
            color: #888;
        }
        
        .message-reply {
            color: var(--cor-primaria);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .header, .statistics-section, .calendar-section, .courses-section, .chat-section {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
        </div>
        <div class="menu-item">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
            </svg>
        </div>
        <div class="menu-item">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path>
            </svg>
        </div>
        <div class="menu-item">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7V7h10v2z"></path>
            </svg>
        </div>
        <div class="menu-item">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
            </svg>
        </div>
    </div>
    
    <div class="main-content">
        <div class="dashboard">
            <div class="header">
                <h1>Trilha Augebit</h1>
                <div class="user-actions">
                    <button class="action-button">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                        </svg>
                    </button>
                    <button class="action-button">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"></path>
                        </svg>
                    </button>
                    <div class="user-info">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                        <span><?php echo $nome_usuario; ?></span>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="statistics-section">
                <h2>Estatísticas de inscrição</h2>
                
                <div class="stat-card">
                    <h3>Frequência cursos</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $frequencia_cursos; ?>%"></div>
                    </div>
                    <div style="text-align: right; margin-top: 5px;"><?php echo $frequencia_cursos; ?>%</div>
                </div>
                
                <div class="stat-card">
                    <h3>Aulas concluídas</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $aulas_concluidas_1; ?>%"></div>
                    </div>
                    <div style="text-align: right; margin-top: 5px;"><?php echo $aulas_concluidas_1; ?>%</div>
                </div>
                
                <div class="stat-card">
                    <h3>Aulas concluídas</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $aulas_concluidas_2; ?>%"></div>
                    </div>
                    <div style="text-align: right; margin-top: 5px;"><?php echo $aulas_concluidas_2; ?>%</div>
                </div>
            </div>
            
            <div class="calendar-section">
                <div class="calendar-header">
                    <h2>Calendário</h2>
                    <div>
                        <span>Abr</span>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="calendar-days">
                    <span>D</span>
                    <span>S</span>
                    <span>T</span>
                    <span>Q</span>
                    <span>Q</span>
                    <span>S</span>
                    <span>S</span>
                </div>
                
                <div class="calendar-grid">
                    <?php foreach($calendario as $semana): ?>
                        <?php foreach($semana as $dia): ?>
                            <?php echo $dia; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="courses-section">
                <div class="empty-card">
                    <svg width="30" height="30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path>
                    </svg>
                </div>
                
                <?php foreach($cursos as $curso): ?>
                    <div class="course-card">
                        <div>
                            <h3><?php echo $curso['titulo']; ?></h3>
                            <p><?php echo $curso['descricao']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="chat-section">
                <div class="chat-header">
                    <h2>Chat de dúvidas</h2>
                    <div class="toggle-container">
                        <div class="toggle-switch"></div>
                    </div>
                </div>
                
                <?php foreach($duvidas as $duvida): ?>
                    <div class="message">
                        <div class="message-info">
                            <div class="message-avatar"></div>
                            <div class="message-content">
                                <div class="course-name"><?php echo $duvida['curso']; ?></div>
                                <div class="function-name"><?php echo $duvida['funcao']; ?></div>
                            </div>
                        </div>
                        <div class="message-actions">
                            <span class="message-time"><?php echo $duvida['horario']; ?></span>
                            <span class="message-reply">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18z"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
        // JavaScript básico para interatividade
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para o chat de dúvidas
            const toggleContainer = document.querySelector('.toggle-container');
            const toggleSwitch = document.querySelector('.toggle-switch');
            
            toggleContainer.addEventListener('click', function() {
                if (toggleSwitch.style.transform === 'translateX(-20px)') {
                    toggleSwitch.style.transform = 'translateX(0)';
                    toggleContainer.style.backgroundColor = '#6c5ce7';
                } else {
                    toggleSwitch.style.transform = 'translateX(-20px)';
                    toggleContainer.style.backgroundColor = '#95a5a6';
                }
            });
            
            // Interatividade do calendário
            const dias = document.querySelectorAll('.dia');
            dias.forEach(dia => {
                dia.addEventListener('click', function() {
                    dias.forEach(d => d.classList.remove('selecionado'));
                    dia.classList.add('selecionado');
                });
            });
        });
    </script>
</body>
</html>