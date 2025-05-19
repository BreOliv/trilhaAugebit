<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo $nome_sistema; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 18px;
        }
        .genero-opcoes {
            display: flex;
            gap: 15px;
        }
    </style>
</head>
<body>
    <?php require_once("../cadastrar.php"); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Cadastro - <?php echo $nome_sistema; ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="cadastrar.php" method="POST">
                            <!-- Tipo de cadastro (pode ficar oculto se você preferir) -->
                            <input type="hidden" name="tipo_cadastro" value="admin">
                            
                            <div class="form-group">
                                <label for="nome_admin">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome_admin" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="sobrenome">Sobrenome:</label>
                                <input type="text" class="form-control" id="sobrenome" name="sobrenome" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Gênero:</label>
                                <div class="genero-opcoes">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="feminino" value="feminino" required>
                                        <label class="form-check-label" for="feminino">
                                            Feminino
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="masculino" value="masculino">
                                        <label class="form-check-label" for="masculino">
                                            Masculino
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="outro" value="outro">
                                        <label class="form-check-label" for="outro">
                                            Outro
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Já possui uma conta? <a href="index.php">Faça login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>