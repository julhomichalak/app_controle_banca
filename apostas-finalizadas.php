<?php
require_once 'database.php';
$apostasDB = new database();

$apostas = $apostasDB->getApostasFinalizadas();
$apostasGanhas = $apostasDB->getApostasByStatus(1);
$apostasPerdidas = $apostasDB->getApostasByStatus(2);
$apostasDevolvidas = $apostasDB->getApostasByStatus(3);

function formatarData($data)
{
    $dataObjeto = new DateTime($data);
    return $dataObjeto->format('d/m/y');
}

function formatarDataMinuto($data)
{
    $dataObjeto = new DateTime($data);
    return $dataObjeto->format('d/m/y H:i');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.postimg.cc/yNv6jxDy/Gest-o-de-banca.png">
    <title>Apostas Finalizadas</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col md-12 mt-3">
                <div class="row">

                    <h3 class="col text-center mt-5">
                        Apostas Finalizadas
                    </h3>
                </div>
                <div class="row">
                    <a href="index" class="col-md-1 btn btn-success btn-lg mb-3 ms-3 me-3">
                        Voltar
                    </a>
                    <h4 class="col mb-3">
                        Apostas Finalizadas (<?= count($apostas) ?>)
                    </h4>
                    <h4 class="col mb-3 text-success">
                        Apostas Vencidas (<?= count($apostasGanhas) ?>)
                    </h4>
                    <h4 class="col mb-3 text-danger">
                        Apostas Perdidas (<?= count($apostasPerdidas) ?>)
                    </h4>
                    <h4 class="col mb-3 text-primary">
                        Apostas Devolvidas (<?= count($apostasDevolvidas) ?>)
                    </h4>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Criada em</th>
                            <th>Competição</th>
                            <th>Mandante</th>
                            <th>Visitante</th>
                            <th>Unidade</th>
                            <th>ODD</th>
                            <th>Retorno</th>
                            <th>Lucro</th>
                            <th>Valor Unidade</th>
                            <th>Casa</th>
                            <th>Hora do jogo</th>
                            <th>Descrição</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apostas as $aposta) { ?>
                            <tr <?php
                                switch ($aposta['status']) {
                                    case 1:
                                        echo 'class="table-success"'; // Verde claro
                                        break;
                                    case 2:
                                        echo 'class="table-danger"'; // Vermelho claro
                                        break;
                                    case 3:
                                        echo 'class="table-primary"'; // Azul claro
                                        break;
                                    default:
                                        break;
                                }
                                ?>>
                                <td><?= formatarData($aposta['data_aposta']); ?></td>
                                <td><?= $aposta['competicao']; ?></td>
                                <td><?= $aposta['mandante']; ?></td>
                                <td><?= $aposta['visitante']; ?></td>
                                <td><?= $aposta['unidade']; ?></td>
                                <td><?= $aposta['odd']; ?></td>
                                <td><?= 'R$ ' . number_format($aposta['valor'] * $aposta['odd'], 2, '.', ''); ?></td>
                                <td style="min-width: 100px;">
                                    <?= 'R$ ' . $aposta['lucro']; ?>
                                </td>
                                <td><?= 'R$ ' . $aposta['valor_unidade']; ?></td>
                                <td><?= $aposta['casa']; ?></td>
                                <td><?= formatarDataMinuto($aposta['data_jogo'] . $aposta['hora_jogo']); ?></td>
                                <td><?= $aposta['descricao']; ?></td>
                                <td><?= $aposta['status_nome']; ?></td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>