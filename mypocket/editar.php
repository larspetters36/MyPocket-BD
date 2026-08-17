<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php'; 
session_start();

$carteira = new Carteira($pdo);

$id = (int) ($_GET['id'] ?? 0);
$transacao = $carteira->pegaTransacaoId($id);

if ($transacao === null) {
    $_SESSION['erro'] = "Transação não encontrada.";
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Transação — My Pocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    Editar Transação
                </div>

                <div class="card-body">

                    <form method="POST" action="processa_edicao.php">

                        <input type="hidden" name="id" value="<?= $transacao['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo da Transação</label>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tipo"
                                    id="receita"
                                    value="entrada"
                                    <?= $transacao['tipo'] === 'Entrada' ? 'checked' : '' ?>>

                                <label class="form-check-label" for="receita">Receita</label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="tipo"
                                    id="despesa"
                                    value="saida"
                                    <?= $transacao['tipo'] === 'Saída' ? 'checked' : '' ?>>

                                <label class="form-check-label" for="despesa">Despesa</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Valor</label>
                            <input
                                type="text"
                                name="valor"
                                class="form-control"
                                value="<?= number_format((float) $transacao['valor'], 2, ',', '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <input
                                type="text"
                                name="descricao"
                                class="form-control"
                                value="<?= htmlspecialchars($transacao['descricao']) ?>">
                        </div>

                        <button class="btn btn-primary w-100">Salvar Alterações</button>
                        <a href="index.php" class="btn btn-link w-100">Cancelar</a>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>