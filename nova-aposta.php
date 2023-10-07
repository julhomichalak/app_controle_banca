<?
require_once 'database.php';
$apostasDB = new database();

$competicoes = $apostasDB->getCompeticoes();
$infosBanca = $apostasDB->getInfosBanca()[0];
if (isset($_POST['nova_aposta'])) {

    $competicao = $_POST['competicao'];
    $mandante = $_POST['mandante'];
    $visitante = $_POST['visitante'];
    $casa = $_POST['casa'];
    date_default_timezone_set('America/Sao_Paulo');
    $data_jogo = $_POST['data_jogo'];
    $hora_jogo = $_POST['hora_jogo'];
    date_default_timezone_set('America/Sao_Paulo');
    $dataJogo = floatval(strtotime($data_jogo));
    $data_aposta = floatval(time());

    $odd = $_POST['odd'];
    $unidade = floatval($_POST['unidade']);
    $valor = $infosBanca['valor_unidade_mes'] * $unidade;
    $descricao = $_POST['descricao'];

    $valor_unidade = $infosBanca['valor_unidade_mes'];

    $status = 4;

    $sql = "INSERT INTO apostas 
    (valor, unidade, odd, data_jogo, data_aposta, competicao, mandante, visitante, casa, descricao, status, hora_jogo, valor_unidade) 
    VALUES 
    ('$valor', '$unidade', '$odd', to_timestamp('$dataJogo'), current_timestamp, '$competicao', '$mandante', '$visitante', '$casa', '$descricao', '$status', '$hora_jogo', '$valor_unidade')";




    try {
        $apostasDB->executarConsulta($sql);
        $mensagem = "Aposta feita com sucesso!";
        echo '<script>alert("' . $mensagem . '");</script>';
        echo '<script>window.location.href = "index";</script>';
    } catch (PDOException $e) {
        echo "Erro ao inserir aposta: " . $e->getMessage();
    }
}



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="https://i.postimg.cc/yNv6jxDy/Gest-o-de-banca.png">
    <title>Nova Aposta</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center mb-3 mt-5">
                    Nova Aposta
                </h3>
                <a href="index" class="btn btn-success btn-lg mb-3">
                    Voltar
                </a>
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="competicao" class="form-label me-2">Competição</label>
                                <select name="competicao" required>
                                    <?php foreach ($competicoes as $competicao) : ?>
                                        <option value="<?php echo $competicao['nome']; ?>">
                                            <?php echo $competicao['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>


                            </div>
                            <div class="mb-3">
                                <label for="mandante" class="form-label">Mandante</label>
                                <input name="mandante" type=" text" class="form-control" id="mandante" required>
                            </div>
                            <div class="mb-3">
                                <label for="visitante" class="form-label">Visitante</label>
                                <input name="visitante" type="text" class="form-control" id="visitante" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_jogo" class="form-label">Data do jogo</label>
                                <input name="data_jogo" type="date" class="form-control" id="data_jogo" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_jogo" class="form-label">Hora do jogo</label>
                                <input name="hora_jogo" type="time" class="form-control" id="hora_jogo" required>
                            </div>
                            <div class="mb-3">
                                <label for="casa" class="form-label">Casa</label>
                                <input type="text" class="form-control" id="casa" name="casa" required>
                            </div>

                            <div class="mb-3">
                                <label for="odd" class="form-label">ODD</label>
                                <input name="odd" type="text" class="form-control" id="odd" required>
                            </div>
                            <div class="mb-3">
                                <label for="unidade" class="form-label">Unidade</label>
                                <input name="unidade" type="text" class="form-control" id="unidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea name="descricao" class="form-control" placeholder="Descrição da aposta" id="descricao" style="height: 100px"></textarea>
                            </div>
                            <button name="nova_aposta" type="submit" class="btn btn-success">Criar Aposta</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>