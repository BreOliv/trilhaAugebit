<?php

// Configuração do Banco de Dados
$usuario = 'root';
$senha = '';
$banco = 'trilha_augebit';
$servidor = 'localhost';

date_default_timezone_set('America/Sao_Paulo');

try {
    $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {
    echo 'Erro ao conectar com o Banco de Dados!<br>';
    echo $e->getMessage();
    exit();
}

// Variáveis de Configuração do Sistema
$nome_sistema = 'Trilha Augebit';
$email_sistema = 'admin@trilhaaugebit.com';
$senha_sistema = '123'; // senha em texto simples
$nome_admin = 'Administrador';

// Criar tabelas caso não existam
$pdo->query("
    CREATE TABLE IF NOT EXISTS cadastro_admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_admin VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        sobrenome VARCHAR(100),
        genero VARCHAR(100),
        senha VARCHAR(255) NOT NULL
    )
");

$pdo->query("
    CREATE TABLE IF NOT EXISTS login_admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )
");

// Verificar se o admin já está cadastrado
$stmt = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = ?");
$stmt->execute([$email_sistema]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não existir, insere o admin com a senha em texto simples
if (!$resultado) {
    $stmt = $pdo->prepare("INSERT INTO cadastro_admin (nome_admin, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome_admin, $email_sistema, $senha_sistema]);

    $stmt = $pdo->prepare("INSERT INTO login_admin (email, senha) VALUES (?, ?)");
    $stmt->execute([$email_sistema, $senha_sistema]);
}

// Carrega configurações do sistema a partir do primeiro admin cadastrado
$query = $pdo->query("SELECT * FROM cadastro_admin LIMIT 1");
$res = $query->fetch(PDO::FETCH_ASSOC);

if ($res) {
    $nome_admin = $res['nome_admin'];
    $email_sistema = $res['email'];
}

?>
