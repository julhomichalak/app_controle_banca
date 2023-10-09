<?php
require_once 'database.php';
$apostasDB = new database();
$competicoes = $apostasDB->getCompeticoes();
$apostas = $apostasDB->getApostasByStatus(4);
$infosBanca = $apostasDB->getInfosBanca()[0];
$apostasFinalizadas = $apostasDB->getApostasFinalizadas();
$valorApostas = $apostas['valor'];
$somaValores = 0;
foreach ($valorApostas as $valor) {
	$somaValores += $valor;
}

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

if (isset($_POST['deuGreen'])) {
	$apostasDB->deuGreen($_POST['id_aposta']);
}

if (isset($_POST['deuRed'])) {
	$apostasDB->deuRed($_POST['id_aposta']);
}

if (isset($_POST['devolveuAposta'])) {
	$apostasDB->devolveuAposta($_POST['id_aposta']);
}




//Editar a aposta
if (isset($_POST['edit_aposta'])) {
	$id_aposta = $_POST['id_aposta'];

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

	$status = 4;
	$sql = "UPDATE apostas SET
    valor = '$valor',
    unidade = '$unidade',
    odd = '$odd',
    data_jogo = to_timestamp('$dataJogo'),
    data_aposta = current_timestamp,
    competicao = '$competicao',
    mandante = '$mandante',
    visitante = '$visitante',
    casa = '$casa',
    descricao = '$descricao',
    status = '$status',
    hora_jogo = '$hora_jogo'
WHERE id = " . (int)$id_aposta;


	try {
		$apostasDB->executarConsulta($sql);
		$mensagem = "Aposta atualizada com sucesso!";
		echo '<script>alert("' . $mensagem . '");</script>';
		echo '<script>window.location.href = "index";</script>';
	} catch (PDOException $e) {
		echo "Erro ao atualizar aposta: " . $e->getMessage();
	}
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


	<title>Gestão de banca</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col md-12 mt-3">
				<div class="row">
					<div class="col container">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<th>Banca Inicial</th>
									<th>Banca Atual</th>
									<th>Banca Atual Sem Apostas Abertas</th>
									<th>Porcentagem Apostas</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>R$ <?= $apostasDB->getValorInicialBanca() ?></td>
									<td>R$ <?= $apostasDB->getBancaAtual(); ?></td>
									<td>R$ <?= $apostasDB->getBancaAtualSemApostasAbertas(); ?></td>
									<td class="text-success"><?= $apostasDB->calcularPorcentagemApostasPorStatus(1) ?> | <?= $apostasDB->contarRegistrosPorStatus(1)[1] ?> </td>

								</tr>
								<tr>
									<td colspan="1"><strong>Lucro % </strong><br> <?= $apostasDB->getLucroBanca() . '%' ?> </td>
									<td colspan="1"><strong>Lucro R$ </strong><br> <?= $apostasDB->getLucroBancaReais() ?> </td>
									<td colspan="1"><strong>Lucro Sem Apostas Abertas R$ </strong><br> <?= $apostasDB->getLucroBancaReaisSemApostasAtuais() ?> </td>
									<td class="text-danger">
										<?= $apostasDB->calcularPorcentagemApostasPorStatus(2) ?> | <?= $apostasDB->contarRegistrosPorStatus(2)[2] ?> <br>
										<span class="text-primary"><?= $apostasDB->calcularPorcentagemApostasPorStatus(3) ?> | <?= $apostasDB->contarRegistrosPorStatus(3)[3] ?></span>
									</td>
								</tr>




							</tbody>
						</table>
					</div>
					<h3 class="col text-center mt-5">
						Gestão de banca
					</h3>
					<div class="col container mb-3">
						<h6>Links Rápidos para Apostas</h6>
						<div class="row">
							<div class="col-md-6">
								<div class="list-group">
									<a href="https://br.novibet.com/apostas-esportivas" class="list-group-item list-group-item-action" target="_blank">Novibet</a>
									<a href="https://www.bet365.com/#/IP/B1" class="list-group-item list-group-item-action" target="_blank">Bet365</a>
									<a href="https://br.betano.com" class="list-group-item list-group-item-action" target="_blank">Betano</a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="list-group">
									<a href="https://www.betfast.io/br" class="list-group-item list-group-item-action" target="_blank">Betfast</a>
									<a href="https://www.playpix.com/pb/sports/pre-match/event-view/Soccer" class="list-group-item list-group-item-action" target="_blank">PlayPix</a>
									<a href="https://sportsbet.io/pt/sports" class="list-group-item list-group-item-action" target="_blank">SportBet.io</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="container mb-3">
					<div class="row justify-content-center text-center">
						<div class="col p-0">
							<a href="infos-banca" class="btn btn-primary btn-lg">Controlar informações da banca</a>
						</div>
						<div class="col p-0">
							<a href="apostas-finalizadas" class="btn btn-secondary btn-lg">Ver apostas passadas (<?= count($apostasFinalizadas) ?>)</a>
						</div>
						<div class="col p-0">
							<a href="nova-aposta" class="btn btn-success btn-lg">Criar nova aposta</a>
						</div>
						<div class="col p-0">
							<a href="evolucao-banca" class="btn btn-info btn-lg">Evolução da banca</a>
						</div>
					</div>
				</div>
				<h4 class="mb-3">
					Apostas atuais (<?= count($apostas) ?>)
				</h4>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Criada em</th>
							<th>Competição</th>
							<th>Mandante</th>
							<th>Visitante</th>
							<th>Valor Aposta</th>
							<th>Unidade</th>
							<th>ODD</th>
							<th>Retorno</th>
							<th>Lucro</th>
							<th>Valor Unidade</th>
							<th>Casa</th>
							<th>Hora do jogo</th>
							<th>Descrição</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($apostas as $aposta) { ?>
							<tr>
								<td style="min-width: 95px;"><?= formatarData($aposta['data_aposta']); ?></td>
								<td><?= $aposta['competicao']; ?></td>
								<td><?= $aposta['mandante']; ?></td>
								<td><?= $aposta['visitante']; ?></td>
								<td style="min-width: 115px;"><?= 'R$ ' . $aposta['valor']; ?></td>
								<td><?= $aposta['unidade']; ?></td>
								<td><?= $aposta['odd']; ?></td>
								<td><?= 'R$ ' . number_format($aposta['valor'] * $aposta['odd'], 2, '.', ''); ?></td>
								<td style="min-width: 100px;"><?= number_format(($aposta['valor'] * $aposta['odd']) - ($aposta['valor_unidade'] * $aposta['unidade']), 2, '.', ''); ?></td>
								<td><?= 'R$ ' . $aposta['valor_unidade']; ?></td>
								<td><?= $aposta['casa']; ?></td>
								<td><?= formatarDataMinuto($aposta['data_jogo'] . $aposta['hora_jogo']); ?></td>
								<td style="max-width: 200px;"><?= $aposta['descricao']; ?></td>
								<td>
									<div style="gap: 10px;" class="d-flex">
										<i style="cursor: pointer;" class="material-icons text-success" data-bs-toggle="modal" data-bs-target="#modalGreen<?= $aposta['id'] ?>">check_box</i>
										<i style="cursor: pointer;" class="material-icons text-danger" data-bs-toggle="modal" data-bs-target="#modalRed<?= $aposta['id'] ?>">cancel</i>
										<i style="cursor: pointer;" class="material-icons text-primary" data-bs-toggle="modal" data-bs-target="#modalDevolveu<?= $aposta['id'] ?>">sync</i>
										<i style="cursor: pointer;" class="material-icons text-primary" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $aposta['id'] ?>">create</i>
									</div>
								</td>
							</tr>


							<!-- Modal green -->
							<div class="modal fade" id="modalGreen<?= $aposta['id'] ?>" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5">Deu green</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											Você confirma que deu green na aposta com o id <b><?= $aposta['id'] ?></b>?<br>
											<hr>
											<b><?= $aposta['mandante'] ?></b> X <b><?= $aposta['visitante'] ?></b><br>
											<hr>
											<?= $aposta['descricao'] ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
											<form method="POST">
												<input name="id_aposta" type="hidden" class="form-control" id="id_aposta" required value="<?= $aposta['id']; ?>">
												<button name="deuGreen" type="submit" class="btn btn-success">Confirmar que deu green ✅</button>
											</form>
										</div>
									</div>
								</div>
							</div>




							<!-- Modal red -->
							<div class="modal fade" id="modalRed<?= $aposta['id'] ?>" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5">Deu red</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											Você confirma que deu red na aposta com o id <b><?= $aposta['id'] ?></b>?<br>
											<hr>
											<b><?= $aposta['mandante'] ?></b> X <b><?= $aposta['visitante'] ?></b><br>
											<hr>
											<?= $aposta['descricao'] ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
											<form method="POST">
												<input name="id_aposta" type="hidden" class="form-control" id="id_aposta" required value="<?= $aposta['id']; ?>">
												<button name="deuRed" type=" submit" class="btn btn-danger">Confirmar que deu red</button>
											</form>
										</div>
									</div>
								</div>
							</div>

							<!-- Modal devolveu -->
							<div class="modal fade" id="modalDevolveu<?= $aposta['id'] ?>" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5">Devolveu aposta</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											Você confirma que devolveu a aposta com o id <b><?= $aposta['id'] ?></b>?<br>
											<hr>
											<b><?= $aposta['mandante'] ?></b> X <b><?= $aposta['visitante'] ?></b><br>
											<hr>
											<?= $aposta['descricao'] ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
											<form method="POST">
												<input name="id_aposta" type="hidden" class="form-control" id="id_aposta" required value="<?= $aposta['id']; ?>">
												<button name="devolveuAposta" type="submit" class="btn btn-primary">Confirmar que devolveu 🔄</button>
											</form>
										</div>
									</div>
								</div>
							</div>




							<!-- Modal edit -->
							<div class="modal fade" id="modalEdit<?= $aposta['id'] ?>" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5">Editar Aposta</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<form method="POST">
												<input name="id_aposta" type="hidden" class="form-control" id="id_aposta" required value="<?= $aposta['id']; ?>">
												<div class="mb-3">
													<label for="competicao" class="form-label me-2">Competição</label>
													<select name="competicao" required>
														<?php foreach ($competicoes as $competicao) : ?>
															<?php $selected = ($aposta['competicao'] == $competicao['nome']) ? 'selected' : ''; ?>
															<option value="<?= $competicao['nome']; ?>" <?= $selected; ?>>
																<?= $competicao['nome']; ?>
															</option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="mb-3">
													<label for="mandante" class="form-label">Mandante</label>
													<input name="mandante" type="text" class="form-control" id="mandante" required value="<?= $aposta['mandante']; ?>">
												</div>
												<div class="mb-3">
													<label for="visitante" class="form-label">Visitante</label>
													<input name="visitante" type="text" class="form-control" id="visitante" required value="<?= $aposta['visitante']; ?>">
												</div>
												<div class="mb-3">
													<label for="data_jogo" class="form-label">Data do jogo</label>
													<input name="data_jogo" type="date" class="form-control" id="data_jogo" required value="<?= $aposta['data_jogo']; ?>">
												</div>
												<div class="mb-3">
													<label for="hora_jogo" class="form-label">Hora do jogo</label>
													<input name="hora_jogo" type="time" class="form-control" id="hora_jogo" required value="<?= $aposta['hora_jogo']; ?>">
												</div>
												<div class="mb-3">
													<label for="casa" class="form-label">Casa</label>
													<input name="casa" type="text" class="form-control" id="casa" required value="<?= $aposta['casa']; ?>">
												</div>
												<div class="mb-3">
													<label for="odd" class="form-label">ODD</label>
													<input name="odd" type="text" class="form-control" id="odd" required value="<?= $aposta['odd']; ?>">
												</div>
												<div class="mb-3">
													<label for="unidade" class="form-label">Unidade</label>
													<input name="unidade" type="number" class="form-control" id="unidade" required value="<?= $aposta['unidade']; ?>">
												</div>
												<div class="mb-3">
													<label for="descricao" class="form-label">Descrição</label>
													<textarea name="descricao" class="form-control" placeholder="Descrição da aposta" id="descricao" style="height: 100px"><?= $aposta['descricao']; ?></textarea>
												</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
											<button name="edit_aposta" type="submit" class="btn btn-primary">Editar Aposta</button>
										</div>
										</form>
									</div>
								</div>
							</div>


						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>