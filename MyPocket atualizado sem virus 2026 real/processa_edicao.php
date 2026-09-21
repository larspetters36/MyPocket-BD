<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php'; 

session_start();

$carteira = new Carteira($pdo);

$id = (int) ($_POST['id'] ?? 0);
$tipo = $_POST['tipo'] ?? '';
$descricao = $_POST['descricao'] ?? '';

$valorRec = $_POST['valor'] ?? '';
$valorDev = str_replace('.', '', $valorRec);
$valorDev = str_replace(',', '.', $valorDev);
$valor = (float) preg_replace('/[^0-9.\-]/', '', $valorDev);

try {
    if (empty($id) || empty($tipo) || empty($valor) || empty($descricao)) {
        throw new Exception("Preencha todos os campos.");
    } else {
        if ($valor <= 0) {
            throw new Exception("Informe um valor válido.");
        }

        $tipoF = $tipo === 'entrada' ? 'Entrada' : 'Saída';
        $carteira->editarTransacao($id, $tipoF, $valor, $descricao);
    }
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
}

header('Location: index.php');
exit();