<?php
// Simulação dos dados que futuramente podem vir do banco de dados

$totalAlunos = 100;
$concluidos = 72;
$emAndamento = $totalAlunos - $concluidos;
$progresso = ($concluidos / $totalAlunos) * 100;

$cursos = ['NR-10', 'NR-35', 'Espaço Confinado', 'Soldagem', 'Eletricista'];
$quantidades = [25, 20, 18, 22, 15];

$meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
$concluidosPorMes = [5, 8, 12, 15, 20, 12];
?>
