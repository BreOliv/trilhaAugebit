








<?php 

//Inicia uma nova sessão ou retoma uma sessão existente.
@session_start();

//Inclusão do Arquivo de Conexão.
require_once("conexao.php");

//Obtenção dos Dados do Formulário.
$email = $_POST['email'];
$senha = (int)$_POST['senha'];

//Verificação das Credenciais.
$query = $pdo->prepare("SELECT * FROM cadastro_admin WHERE email = :email AND senha = :senha");
$query->bindValue(":email", $email);
$query->bindValue(":senha", $senha);
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

// Verifica se encontrou um usuário administrador
if(count($resultado) > 0) {
    // Define variáveis de sessão
    $_SESSION['id_usuario'] = $resultado[0]['id'];
    $_SESSION['nome_usuario'] = $resultado[0]['nome_admin'];
    $_SESSION['email_usuario'] = $resultado[0]['email'];
        
    echo '<script>window.location="index.php"</script>';

} else {
    // Se não encontrou como admin, tenta verificar se é um usuário normal
    $query = $pdo->prepare("SELECT * FROM usuario_config WHERE email = :email AND senha = :senha");
    $query->bindValue(":email", $email);
    $query->bindValue(":senha", $senha); // Corrigido: senha sem criptografia
    $query->execute();
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

    if(count($resultado) > 0) {
        // Define variáveis de sessão
        $_SESSION['id_usuario'] = $resultado[0]['id'];
        $_SESSION['nome_usuario'] = $resultado[0]['nome'];
        $_SESSION['email_usuario'] = $resultado[0]['email'];
        
        echo '<script>window.location="painel"</script>';
    } else {
        // Se não encontrou em nenhuma tabela, exibe mensagem de erro
        echo '<script>alert("Dados Incorretos! Verifique seu email e senha.")</script>';
        echo '<script>window.location="index.php"</script>';
    }
}
?>