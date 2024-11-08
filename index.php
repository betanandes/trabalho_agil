<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Pessoas</title>
    <link rel="shortcut icon" href="assets/img/icones-de-pessoas.png" type="image/x-icon">

    <!-- Fonte Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilos gerais */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('assets/img/monochromatic-background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        /* Container principal com fundo translúcido */
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            /* Fundo branco translúcido para o conteúdo */
            padding: 20px;
            margin-top: 50px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            background-color: #fff;
            border: none;
            color: #333;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Botões e headers personalizados */
        .card-header {
            background-color: #db0b8a;
            color: #fff;
        }

        .btn-primary {
            background-color: #702271;
            border-color: #702271;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-success {
            background-color: #702271;
            border-color: #702271;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-warning {
            background-color: #ffafcc;
            border-color: #ffafcc;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #d355af;
            border-color: #d355af;
        }

        .btn-success:hover {
            background-color: #d355af;
            border-color: #d355af;
        }

        .btn-warning:hover {
            background-color: #f48fb1;
            border-color: #f48fb1;
        }

        .alert {
            border-radius: 5px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-4">Gestão de Pessoas</h1>

        <?php
        // Inicializa as variáveis
        $idPessoa = 0;
        $nomeAtual = '';
        $idadeAtual = '';
        $resultadoBusca = '';

        // Ação para salvar, atualizar e excluir
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Salvando ou atualizando registro
            if (isset($_POST['salvar'])) {
                $idPessoa = $_POST['id'];
                $nome = $_POST['nome'];
                $idade = $_POST['idade'];

                if ($idPessoa == 0) {
                    // Inserir novo registro
                    $sql = "INSERT INTO pessoas (nome, idade) VALUES ('$nome', $idade)";
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='alert alert-success'>Novo registro salvo com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao salvar: " . $conn->error . "</div>";
                    }
                } else {
                    // Atualizar registro existente
                    $sqlUpdate = "UPDATE pessoas SET nome = '$nome', idade = $idade WHERE id = $idPessoa";
                    if ($conn->query($sqlUpdate) === TRUE) {
                        echo "<div class='alert alert-success'>Registro atualizado com sucesso!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Erro ao atualizar: " . $conn->error . "</div>";
                    }
                } 
            }

            // Carregar registro para edição
            if (isset($_POST['editar'])) {
                $idPessoa = $_POST['idPessoa'];
                $sqlBuscaPorID = "SELECT * FROM pessoas WHERE id = $idPessoa";
                $resultado = $conn->query($sqlBuscaPorID);

                if ($resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $idPessoa = $row['id'];
                    $nomeAtual = $row['nome'];
                    $idadeAtual = $row['idade'];
                }
            }

            if (isset($_POST['excluir'])) {
                $idPessoa = $_POST['idPessoa']; // Identifica o ID do item que queremos excluir
                $sqlDelete = "DELETE FROM pessoas WHERE id = $idPessoa";
                if ($conn->query($sqlDelete) === TRUE) {
                    echo "<div class='alert alert-success'>Registro excluído com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erro ao excluir: " . $conn->error . "</div>";
                }
            }


            // Localizar registro
            if (isset($_POST['buscar'])) {
                $nomeBusca = $_POST['nomeBusca'];

                // Prepara a SQL de busca parcial usando LIKE
                $sqlBusca = "SELECT * FROM pessoas WHERE nome LIKE '%$nomeBusca%'";
                $resultado = $conn->query($sqlBusca);

                if ($resultado->num_rows > 0) {
                    // Exibe os resultados encontrados e coloca botões de edição para cada um
                    // Exibe os resultados encontrados e coloca botões de edição e exclusão para cada um
                    $resultadoBusca = "<h2>Resultados da busca por '$nomeBusca':</h2><ul class='list-group'>";
                    while ($row = $resultado->fetch_assoc()) {
                        $resultadoBusca .= "<li class='list-group-item'>ID: " . $row["id"] . " | Nome: " . $row["nome"] . " | Idade: " . $row["idade"];
                        // Botão de Editar
                        $resultadoBusca .= "
    <form style='display:inline;' method='post' action='index.php'>
        <input type='hidden' name='idPessoa' value='" . $row["id"] . "'>
        <input type='submit' name='editar' value='Editar' class='btn btn-warning btn-sm ml-3'>
    </form>";

                        // Botão de Excluir
                        $resultadoBusca .= "
    <form style='display:inline;' method='post' action='index.php' onsubmit='return confirm(\"Tem certeza que deseja excluir esta pessoa?\");'>
        <input type='hidden' name='idPessoa' value='" . $row["id"] . "'>
        <input type='submit' name='excluir' value='Excluir' class='btn btn-danger btn-sm ml-2'>
    </form>";

                        $resultadoBusca .= "</li>";
                    }
                    $resultadoBusca .= "</ul>";

                } else {
                    $resultadoBusca = "<div class='alert alert-info'>Nenhuma pessoa encontrada com o nome que contenha '$nomeBusca'.</div>";
                }
            }
        }
        ?>

        <!-- Utiliza o grid do Bootstrap para colocar os formulários lado a lado -->
        <div class="row">
            <!-- Formulário de Salvar//Deletar Pessoa -->
            <div class="col-md-6 mb-3">
                <div class="card form-container">
                    <div class="card-header">
                        <?php echo ($idPessoa > 0) ? 'Editar Pessoa' : 'Adicionar Nova Pessoa'; ?>
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $idPessoa; ?>">

                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control"
                                    value="<?php echo $nomeAtual; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="idade">Idade:</label>
                                <input type="number" id="idade" name="idade" class="form-control"
                                    value="<?php echo $idadeAtual; ?>" required>
                            </div>

                            <button type="submit" name="salvar" class="btn btn-success btn-block">
                                <?php echo ($idPessoa > 0) ? 'Atualizar' : 'Salvar'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulário de Busca -->
            <div class="col-md-6 mb-3">
                <div class="card form-container">
                    <div class="card-header">
                        Buscar Pessoa
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="post">
                            <div class="form-group">
                                <label for="nomeBusca">Nome para buscar (parcial ou completo):</label>
                                <input type="text" id="nomeBusca" name="nomeBusca" class="form-control" required>
                            </div>
                            <button type="submit" name="buscar" class="btn btn-primary btn-block">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exibe o resultado da busca com opção de editar -->
        <?php if (!empty($resultadoBusca)): ?>
            <div class="mt-4">
                <?php echo $resultadoBusca; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- Bibliotecas JavaScript necessárias para o Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

</body>

</html>