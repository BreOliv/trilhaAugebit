<?php
require_once("conexao.php");

session_start(); // ✅ Coloque no início, ANTES de qualquer saída

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = trim($_POST['nome_admin']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $genero = trim($_POST['genero']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($sobrenome) || empty($email) || empty($genero) || empty($senha)) {
        echo '<script>alert("Preencha todos os campos."); window.location="cadastro.php";</script>';
        exit();
    }

    // Verifica se email já existe
    $query = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = :email");
    $query->bindValue(":email", $email);
    $query->execute();

    if ($query->rowCount() > 0) {
        echo '<script>alert("Email já cadastrado."); window.location="cadastro.php";</script>';
        exit();
    }

    try {
        $pdo->beginTransaction();

        $query = $pdo->prepare("INSERT INTO cadastro_admin (nome_admin, sobrenome, email, genero, senha) 
                                VALUES (:nome_admin, :sobrenome, :email, :genero, :senha)");
        $query->bindValue(":nome_admin", $nome);
        $query->bindValue(":sobrenome", $sobrenome);
        $query->bindValue(":email", $email);
        $query->bindValue(":genero", $genero);
        $query->bindValue(":senha", $senha);
        $query->execute();

        $query = $pdo->prepare("INSERT INTO login_admin (email, senha) 
                                VALUES (:email, :senha)");
        $query->bindValue(":email", $email);
        $query->bindValue(":senha", $senha);
        $query->execute();

        $pdo->commit();

        // ✅ Salva nome na sessão
        $_SESSION['nome_usuario'] = $nome;

        // ✅ Redireciona para index de forma segura
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo '<script>alert("Erro no cadastro: ' . $e->getMessage() . '"); window.location="cadastro.php";</script>';
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
