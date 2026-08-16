<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php'; 

session_start();

$carteira = new Carteira($pdo);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Pocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-5">
<?php if (isset($_SESSION['erro'])): ?>

    <div class="alert alert-danger">
        <?= $_SESSION['erro'] ?>
    </div>

    <?php unset($_SESSION['erro']); ?>

<?php endif; ?>
    //parte do saldo, n esquecer
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            <h6 class="text-muted mb-2">Saldo Atual</h6>
            <h1 class="fw-bold text-success">
                R$ <?= number_format($carteira->getSaldo(), 2, ',', '.') ?>
            </h1>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    Nova Transação
                </div>

                <div class="card-body">

                    <form method="POST" action="processa.php">

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Tipo da Transação
                            </label>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tipo"
                                    id="receita"
                                    value="entrada"
                                    checked>

                                <label class="form-check-label" for="receita">
                                    Receita
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tipo"
                                    id="despesa"
                                    value="saida">

                                <label class="form-check-label" for="despesa">
                                    Despesa
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Valor</label>
                            <input
                                type="text"
                                name="valor"
                                class="form-control"
                                placeholder="R$ 0,00">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <input
                                type="text"
                                name="descricao"
                                class="form-control"
                                placeholder="Ex: Mercado">
                        </div>

                        <button class="btn btn-primary w-100">
                            Salvar Transação
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <!-- Histórico -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Histórico de Transações</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Descrição</th>
                                    <th>Data</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($carteira->getTransacoes() as $transacao): ?>

                                <?php
                                    $entrada = $transacao['tipo'] === 'Entrada';
                                    $datanov = (new DateTime($transacao['criado_em']))->format('d-m-Y H:i');
                                ?>

                                <tr>
                                    <td>
                                        <span class="badge <?= $entrada ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $transacao['tipo'] ?>
                                        </span>
                                    </td>

                                    <td class="<?= $entrada ? 'text-success' : 'text-danger' ?>">
                                        <strong>
                                            R$
                                            <?= number_format((float) $transacao['valor'], 2, ',', '.') ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($transacao['descricao']) ?>
                                    </td>

                                    <td>
                                        <?= $datanov ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
