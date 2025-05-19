<?php 

//Configuração do Banco de Dados.
$usuario = 'root';
$senha = '';
$banco = 'trilha_augebit';
$servidor = 'localhost';

date_default_timezone_set('America/Sao_Paulo');

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {
	echo 'Erro ao conectar com o Banco de Dados!';
	echo '<br>';
	echo $e;
}

//Variáveis de Configuração do Sistema.
$nome_sistema = 'Trilha Augebit ';
$email_sistema = 'admin@trilhaaugebit.com';
$senha_sistema = '123';
$nome_admin = '';

// Verificar se as tabelas existem, caso contrário, criá-las
$query = $pdo->query("SHOW TABLES LIKE 'cadastro_admin'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE cadastro_admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_admin VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        sobrenome VARCHAR(100),
        genero VARCHAR(100),
        senha INT(11)
    )");
}

$query = $pdo->query("SHOW TABLES LIKE 'login_admin'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE login_admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha INT(11) NOT NULL
    )");
}

$query = $pdo->query("SHOW TABLES LIKE 'usuario_config'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE usuario_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_aluno VARCHAR(100) NOT NULL,
        foto_perfil VARCHAR(255),
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(100) NOT NULL
    )");
}
    
    // Inserir um usuário padrão para configuração inicial
    $senha_padrao = 123;
    $pdo->query("INSERT INTO cadastro_admin SET nome_admin = '$nome_admin', email = '$email_sistema', 
    senha = '$senha_padrao'");

$query = $pdo->query("SHOW TABLES LIKE 'cadastro_app'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE cadastro_app (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_aluno VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(100) NOT NULL,
        foto_perfil VARCHAR(255)
    )");
}

$query = $pdo->query("SHOW TABLES LIKE 'cursos_app'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE cursos_app (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_curso VARCHAR(100) NOT NULL,
        descricao TEXT,
        modalidade ENUM('EAD', 'Presencial') NOT NULL,
        unidade_local VARCHAR(100),
        carga_horaria VARCHAR(50)
    )");
}

$query = $pdo->query("SHOW TABLES LIKE 'inscricoes_app'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE inscricoes_app (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_curso INT NOT NULL,
        status ENUM('andamento', 'concluído', 'pendente') DEFAULT 'pendente',
        data_inscricao DATE DEFAULT CURRENT_DATE,
        FOREIGN KEY (id_usuario) REFERENCES cadastro_app(id),
        FOREIGN KEY (id_curso) REFERENCES cursos_app(id)
    )");
}

$query = $pdo->query("SHOW TABLES LIKE 'filtro_app'");
if($query->rowCount() == 0){
    $pdo->query("
    CREATE TABLE filtro_app (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        filtro_curso ENUM('EAD', 'Presencial'),
        FOREIGN KEY (id_usuario) REFERENCES cadastro_app(id)
    )");
}

// / Carregar configurações do usuário se existirem
$query = $pdo->query("SELECT * FROM cadastro_admin LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
    $nome_admin = $res[0]['nome_admin'];
    $email_sistema = $res[0]['email'];
}
