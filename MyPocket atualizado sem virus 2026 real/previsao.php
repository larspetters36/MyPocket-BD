<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Recorrencia.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php';

session_start();

$carteira = new Carteira($pdo);
$carteira->processarRecorrencias();

$ano = (int) ($_GET['ano'] ?? date('Y'));
$saldosPorMes = $carteira->projetarMes($ano);

$nomesMeses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
$mesAtual = (int) date('n');
$anoAtual = (int) date('Y');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsão Anual — My Pocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Previsão de saldo — <?= $ano ?></h4>
            <div>
                <a href="?ano=<?= $ano - 1 ?>" class="btn btn-sm btn-outline-secondary">&laquo; <?= $ano - 1 ?></a>
                <a href="?ano=<?= $ano + 1 ?>" class="btn btn-sm btn-outline-secondary"><?= $ano + 1 ?> &raquo;</a>
                <a href="index.php" class="btn btn-sm btn-outline-primary">Voltar</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <?php foreach ($nomesMeses as $indice => $nome): ?>
                                    <?php $ehMesAtual = ($indice + 1) === $mesAtual && $ano === $anoAtual; ?>
                                    <th class="<?= $ehMesAtual ? 'text-primary' : '' ?>"><?= $nome ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($saldosPorMes as $mes => $valor): ?>
                                    <?php $ehFuturo = ($mes > $mesAtual && $ano === $anoAtual) || $ano > $anoAtual; ?>
                                    <td class="<?= $valor >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <strong>R$ <?= number_format($valor, 2, ',', '.') ?></strong>
                                        <?php if ($ehFuturo): ?>
                                            <div><small class="text-muted">estimado</small></div>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>

</html>