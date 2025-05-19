<?php 

//Inicia uma nova sessão ou retoma uma sessão existente.
@session_start();

//Inclusão do Arquivo de Conexão.
require_once("conexao.php");

//Obtenção dos Dados do Formulário.
$email = $_POST['email'];
$senha = $_POST['senha'];
$senha_criptografada = md5($senha);

//Verificação das Credenciais.
$query = $pdo->prepare("SELECT * FROM login_admin WHERE email = :email AND senha = :senha");
$query->bindValue(":email", $email);
$query->bindValue(":senha", $senha_criptografada);
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

// Verifica se encontrou um usuário administrador
if(count($resultado) > 0) {
    // Busca dados adicionais do administrador
    $query_admin = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = :email");
    $query_admin->bindValue(":email", $email);
    $query_admin->execute();
    $dados_admin = $query_admin->fetchAll(PDO::FETCH_ASSOC);

    if(count($dados_admin) > 0) {
        // Define variáveis de sessão
        $_SESSION['id_usuario'] = $dados_admin[0]['id'];
        $_SESSION['nome_usuario'] = $dados_admin[0]['usuario'];
        $_SESSION['email_usuario'] = $dados_admin[0]['email'];
        $_SESSION['nivel_usuario'] = 'admin'; // Define o nível como administrador
        $_SESSION['foto_usuario'] = $dados_admin[0]['foto_perfil'] ?? 'sem-foto.jpg';
        
        echo '<script>window.location="painel"</script>';
    } else {
        echo '<script>alert("Erro ao recuperar dados do usuário!")</script>';
        echo '<script>window.location="index.php"</script>';
    }
} else {
    // Se não encontrou como admin, tenta verificar se é um usuário normal
    $query = $pdo->prepare("SELECT * FROM usuario_config WHERE email = :email AND senha = :senha");
    $query->bindValue(":email", $email);
    $query->bindValue(":senha", $senha_criptografada);
    $query->execute();
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($resultado) > 0) {
        // Define variáveis de sessão
        $_SESSION['id_usuario'] = $resultado[0]['id'];
        $_SESSION['nome_usuario'] = $resultado[0]['nome'];
        $_SESSION['email_usuario'] = $resultado[0]['email'];
        $_SESSION['nivel_usuario'] = 'config'; // Define o nível como configurador
        $_SESSION['foto_usuario'] = $resultado[0]['foto_perfil'] ?? 'sem-foto.jpg';
        
        echo '<script>window.location="painel"</script>';
    } else {
        // Se não encontrou em nenhuma tabela, exibe mensagem de erro
        echo '<script>alert("Dados Incorretos! Verifique seu email e senha.")</script>';
        echo '<script>window.location="index.php"</script>';
    }
}
?>