<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php';

session_start();

$carteira = new Carteira($pdo);

$tipo = $_POST['tipo'] ?? ''; //?? para verificar se nao é nulo **nao esquwcer**
$descricao = $_POST['descricao'] ?? '';


$valorRec = $_POST['valor'] ?? '';
$valorDev = str_replace('.', '', $valorRec);
$valorDev = str_replace(',', '.', $valorDev);
$valor = (float) preg_replace('/[^0-9.\-]/', '', $valorDev); //esse preg_replace serve para remover caractereds que nao sejam numeros

try {
    if ($valor <= 0) {
        throw new Exception("Informe um valor válido.");
    }
    if (empty($tipo) || empty($descricao)) {
        throw new Exception("Preencha todos os campos");
    } else {
        if ($tipo === 'entrada') {
            $receita = new Receita($valor, $descricao);
            $carteira->adicionarReceita($receita);
        } elseif ($tipo === 'saida') {
            $despesa = new Despesa($valor, $descricao);
            $carteira->adicionarDespesa($despesa);
        }
    }
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
}

header('Location: index.php');
exit();
