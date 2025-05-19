<?php

//Inclusão do Arquivo de Conexão.
require_once("conexao.php");

// Verifica se o formulário foi enviado
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Determina o tipo de cadastro (admin ou usuário normal)
    $tipo_cadastro = isset($_POST['tipo_cadastro']) ? $_POST['tipo_cadastro'] : 'usuario';
    
    // Obtém os dados do formulário
    $nome = addslashes($_POST['nome_admin']);
    $sobrenome = addslashes($_POST['sobrenome']); // Novo campo de sobrenome
    $email = addslashes($_POST['email']);
    $genero = addslashes($_POST['genero']); // Novo campo de gênero
    $senha = $_POST['senha']; // Será convertida para INT
    
    // Converter senha para INT
    $senha_int = (int)$senha;
    
    // Verificar se o email já existe no sistema
    $query = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = :email");
    $query->bindValue(":email", $email);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo '<script>alert("Email já cadastrado no sistema!")</script>';
        echo '<script>window.location="cadastro.php"</script>';
        exit();
    }
    
    try {
        // Inicia uma transação para garantir que todas as operações sejam realizadas
        $pdo->beginTransaction();
        
        if($tipo_cadastro == 'admin') {
            // Cadastro de administrador
            
            // Insere na tabela cadastro_admin
            $query = $pdo->prepare("INSERT INTO cadastro_admin (nome_admin, sobrenome, email, genero, senha) VALUES (:nome_admin, :sobrenome, :email, :genero, :senha)");
            $query->bindValue(":nome_admin", $nome);
            $query->bindValue(":sobrenome", $sobrenome);
            $query->bindValue(":email", $email);
            $query->bindValue(":genero", $genero);
            $query->bindValue(":senha", $senha_int);
            $query->execute();
            
            // Insere na tabela login_admin
            $query = $pdo->prepare("INSERT INTO login_admin (email, senha) VALUES (:email, :senha)");
            $query->bindValue(":email", $email);
            $query->bindValue(":senha", $senha_int);
            $query->execute();
            
            $mensagem = "Administrador cadastrado com sucesso!";
        } 
        // Finaliza a transação
        $pdo->commit();
        
        echo '<script>alert("' . $mensagem . '")</script>';
        echo '<script>window.location="index.php"</script>';
        
    } catch(Exception $e) {
        // Desfaz as operações em caso de erro
        $pdo->rollBack();
        
        echo '<script>alert("Erro ao cadastrar usuário: ' . $e->getMessage() . '")</script>';
        echo '<script>window.location="pages/cadastro.php"</script>';
    }
    
} else {
    // Se alguém acessar este arquivo diretamente, redireciona para a página de cadastro
    header("Location: /pages/cadastro.php");
    exit();
}

?>