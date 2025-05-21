<?php
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = trim($_POST['nome_admin']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $genero = trim($_POST['genero']);
    $senha = $_POST['senha']; // sem hash

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

        // Inserir na tabela de administradores
        $query = $pdo->prepare("INSERT INTO cadastro_admin (nome_admin, sobrenome, email, genero, senha) 
                                VALUES (:nome_admin, :sobrenome, :email, :genero, :senha)");
        $query->bindValue(":nome_admin", $nome);
        $query->bindValue(":sobrenome", $sobrenome);
        $query->bindValue(":email", $email);
        $query->bindValue(":genero", $genero);
        $query->bindValue(":senha", $senha); // senha como texto puro
        $query->execute();

        // Inserir no login dos administradores
        $query = $pdo->prepare("INSERT INTO login_admin (email, senha) 
                                VALUES (:email, :senha)");
        $query->bindValue(":email", $email);
        $query->bindValue(":senha", $senha); // senha como texto puro
        $query->execute();

        $pdo->commit();

        echo '<script>alert("Cadastro realizado com sucesso!"); window.location="index.php";</script>';
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo '<script>alert("Erro no cadastro: ' . $e->getMessage() . '"); window.location="cadastro.php";</script>';
        exit();
    }

} else {
    // Caso acessem diretamente via GET
    header("Location: index.php");
    exit();
}
