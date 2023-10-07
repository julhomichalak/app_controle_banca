<?
require_once 'database.php';
$apostasDB = new database();

$infosBanca = $apostasDB->getInfosBanca()[0];
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
                <a href="index" class="btn btn-success btn-lg mb-3">
                    Voltar
                </a>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" name="info_banca">
                            <div class="mb-3">
                                <label for="unidade_banca" class="form-label">Unidade da banca</label>
                                <input value="<?= $infosBanca['qnt_unidade']; ?>" type="text" class="form-control" id="unidade_banca" name="unidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="valor_inicial_mes" class="form-label">Valor Inicial do Mês</label>
                                <input value="<?= $infosBanca['valor_inicial_mes']; ?>" type="text" class="form-control" id="valor_inicial_mes" name="valor_inicial_mes" required>
                            </div>
                            <button name="info_banca" type="submit" class="btn btn-success">Atualizar Banca</button>
                        </form>


                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>