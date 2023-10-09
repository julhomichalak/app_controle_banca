<?
require_once 'database.php';
$apostasDB = new database();

$infosBanca = $apostasDB->getInfosBancaAtiva();


if (isset($_POST['info_banca'])) {
    $id = $infosBanca['id'];
    $unidade_banca = $_POST['unidade'];
    $valor_inicial_mes = $_POST['valor_inicial_mes'];
    $valor_unidade_mes = $valor_inicial_mes / $unidade_banca;

    $sql = "UPDATE infos_banca SET
        qnt_unidade = '$unidade_banca',
        valor_unidade_mes = '$valor_unidade_mes',
        valor_inicial_mes = '$valor_inicial_mes'
        WHERE id = '$id'";

    $apostasDB->executarConsulta($sql);
    $mensagem = "Banca atualizada com sucesso!";
    echo '<script>alert("' . $mensagem . '");</script>';
    echo '<script>window.location.href = "index";</script>';
}

if (isset($_POST['finalizar_banca'])) {
    $id = $infosBanca['id'];
    $proximaBancaId = $infosBanca['proxima_banca'];

    if ($proximaBancaId) {
        $sql_desativar_banca_atual = "UPDATE infos_banca SET ativo = false WHERE id = '$id'";
        $apostasDB->executarConsulta($sql_desativar_banca_atual);
        $sql_ativar_proxima_banca = "UPDATE infos_banca SET ativo = true WHERE id = '$proximaBancaId'";
        $apostasDB->executarConsulta($sql_ativar_proxima_banca);

        $mensagem = "Banca finalizada com sucesso!";
        echo '<script>alert("' . $mensagem . '");</script>';
        echo '<script>window.location.href = "index";</script>';
    } else {
        $mensagem = "Não foi possível finalizar a banca, pois não há uma próxima banca definida.";
        echo '<script>alert("' . $mensagem . '");</script>';
        echo '<script>window.location.href = "index";</script>';
    }
}


if (isset($_POST['nova_banca'])) {
    $unidade_banca = $_POST['unidade'];
    $valor_inicial_mes = $_POST['valor_inicial_mes'];
    $valor_unidade_mes = $valor_inicial_mes / $unidade_banca;
    $mes_banca = $_POST['mes_banca'];
    $ano_banca = $_POST['ano_banca'];
    $id = md5($valor_unidade_mes * $unidade_banca * $ano_banca) . $ano_banca;

    $sql = "INSERT INTO infos_banca (id, qnt_unidade, valor_unidade_mes, valor_inicial_mes, mes_banca, ano_banca, ativo) VALUES ('$id', '$unidade_banca', '$valor_unidade_mes', '$valor_inicial_mes', '$mes_banca', '$ano_banca', false)";
    $apostasDB->executarConsulta($sql);
    $id_banca_atual = $infosBanca['id'];
    $sql_update_banca_atual = "UPDATE infos_banca SET proxima_banca = '$id' WHERE id = '$id_banca_atual'";
    $apostasDB->executarConsulta($sql_update_banca_atual);

    $mensagem = "Banca criada com sucesso!";
    echo '<script>alert("' . $mensagem . '");</script>';
    echo '<script>window.location.href = "index";</script>';
}


$diaAtual = date('d');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="https://i.postimg.cc/yNv6jxDy/Gest-o-de-banca.png">
    <title>Informações da Banca</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center mb-3 mt-5">
                    Informações da Banca
                </h3>
                <div class="row ms-1">
                    <a href="index" class="col-1 btn btn-success btn-lg mb-3 me-5">
                        Voltar
                    </a>
                    <a href="#" class="col-1 btn btn-success btn-lg mb-3" id="btnNovaBanca" data-bs-toggle="modal" data-bs-target="#modalNovaBanca">
                        Nova Banca
                    </a>

                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" name="info_banca">
                            <div class="mb-3 text-center">
                                <h4>Banca atual</h4>
                            </div>
                            <div class="mb-3">
                                <label for="unidade_banca" class="form-label">Unidade da banca</label><span class="badge rounded-pill text-bg-success ms-1">50U = 2%</span><span class="badge rounded-pill text-bg-success ms-1">33.33U = 3%</span><span class="badge rounded-pill text-bg-success ms-1">25U = 4%</span>
                                <input value="<?= $infosBanca['qnt_unidade']; ?>" type="text" class="form-control" id="unidade_banca" name="unidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="valor_inicial_mes" class="form-label">Valor Inicial do Mês</label>
                                <input value="<?= $infosBanca['valor_inicial_mes']; ?>" type="text" class="form-control" id="valor_inicial_mes" name="valor_inicial_mes" required>
                            </div>
                            <div class="mb-3">
                                <label for="mes_banca" class="form-label">Mês da banca</label>
                                <input value="<?= $infosBanca['mes_banca']; ?>" type="text" class="form-control" id="mes_banca" name="mes_banca" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="ano_banca" class="form-label">Valor Inicial do Mês</label>
                                <input value="<?= $infosBanca['ano_banca']; ?>" type="text" class="form-control" id="ano_banca" name="ano_banca" disabled>
                            </div>
                            <button name="info_banca" type="submit" class="btn btn-success">Atualizar Banca</button>
                            <button name="finalizar_banca" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalFinalizarBanca">Finalizar Banca</button>
                        </form>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Finalizar Banca-->
    <div class="modal fade" id="modalFinalizarBanca" tabindex="-1" aria-labelledby="modalFinalizarBanca" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalFinalizarBanca">Finalizar Banca</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <? if ($diaAtual == '30' || $diaAtual == '31' || $diaAtual == '01') {
                    ?>
                        <span class="badge text-bg-danger mb-1 text-center d-block p-2 fs-6">Crie uma banca nova antes de finalizar a banca atual!</span>
                        <!--Caso não seja dia primeiro liberar form -->
                        <div class="modal-body">
                            Você confirma que irá finalizar a banca o id <b><?= $infosBanca['id'] ?></b>?<br>
                            <hr>
                            <b>Mês: <?= $infosBanca['mes_banca']; ?></b><b> Mês: <?= $infosBanca['ano_banca']; ?></b><br>
                            <hr>
                            <span class="badge text-bg-danger">Fechou o mês com um <br><strong>lucro de R$ </strong> <?= $apostasDB->getLucroBancaReais() ?><br></span>
                        </div>
                        <div class="modal-footer">
                            <form method="POST">
                                <button name="finalizar_banca" type=" submit" class="btn btn-danger">Confirmar finalização de banca</button>
                            </form>
                        </div>
                    <?  } else {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Atenção!</h4>
                            <p>Hoje não é dia 30, 31 ou 1 portanto não é possível finalizar a banca atual.</p>
                            <hr>
                            <p class="mb-0">Aguarde até o dia 30, 31 ou 1 para criar a banca atual.</p>
                        </div>
                    <?
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <? if ($diaAtual == '30' || $diaAtual == '31' || $diaAtual == '01') {
                    ?>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <? } else {
                    ?>
                    <? }
                    ?>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Nova Banca -->
    <div class="modal fade" id="modalNovaBanca" tabindex="-1" aria-labelledby="modalNovaBanca" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalNovaBanca">Criar Nova Banca</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <? if ($diaAtual == '7' || $diaAtual == '31' || $diaAtual == '01') {
                    ?>
                        <!--Caso não seja dia primeiro liberar form -->
                        <form method="POST" name="nova_banca">
                            <div class="mb-3 text-center">
                                <h4>Réplica Banca atual</h4>
                            </div>
                            <div class="mb-3">
                                <label for="unidade_banca" class="form-label">Unidade da banca</label><span class="badge rounded-pill text-bg-success ms-1">50U = 2%</span><span class="badge rounded-pill text-bg-success ms-1">33.33U = 3%</span><span class="badge rounded-pill text-bg-success ms-1">25U = 4%</span>
                                <input value="<?= $infosBanca['qnt_unidade']; ?>" type="text" class="form-control" id="unidade_banca" name="unidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="valor_inicial_mes" class="form-label">Valor ATUAL da banca</label> <span class="badge rounded-pill text-bg-primary">Começou o mês com <?= $infosBanca['valor_inicial_mes']; ?></span>
                                <input value="<?= $apostasDB->getBancaAtual(); ?>" type="text" class="form-control" id="valor_inicial_mes" name="valor_inicial_mes" required>
                            </div>
                            <div class="mb-3">
                                <label for="mes_banca" class="form-label">Mês da banca</label><span class="badge rounded-pill text-bg-danger ms-1">ATENÇÃO</span>
                                <input value="<?= $infosBanca['mes_banca']; ?>" type="text" class="form-control" id="mes_banca" name="mes_banca" required>
                            </div>
                            <div class="mb-3">
                                <label for="ano_banca" class="form-label">Ano da banca</label><span class="badge rounded-pill text-bg-danger ms-1">ATENÇÃO</span>
                                <input value="<?= $infosBanca['ano_banca']; ?>" type="text" class="form-control" id="ano_banca" name="ano_banca" required>
                            </div>
                            <button name="nova_banca" type="submit" class="btn btn-success">Criar Banca</button>
                        </form>
                    <?  } else {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Atenção!</h4>
                            <p>Hoje não é dia dia 30, 31 ou 1 portanto não é possível criar uma nova banca.</p>
                            <hr>
                            <p class="mb-0">Aguarde até o dia dia 30, 31 ou 1 para criar uma nova banca.</p>
                        </div>
                    <?  }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>