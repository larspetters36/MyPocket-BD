<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Recorrencia.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php';

session_start();

$carteira = new Carteira($pdo);

$tipo = $_POST['tipo'] ?? ''; //?? para verificar se nao é nulo **nao esquwcer**
$descricao = $_POST['descricao'] ?? '';
$ehRecorrente = ($_POST['eh_recorrente'] ?? 'nao') === 'sim';

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
    }

    if ($ehRecorrente) {
        $tipoRecorrencia = $_POST['tipo_recorrencia'] ?? '';
        $inicio = $_POST['inicio'] ?? '';
        $duracao = (float) ($_POST['duracao'] ?? 0);

        if (empty($tipoRecorrencia) || empty($inicio) || $duracao <= 0) {
            throw new Exception("Preencha os dados da recorrência corretamente.");
        }

        $recorrencia = new Recorrencia($tipo, $valor, $descricao, $tipoRecorrencia, $inicio, $duracao);
        $carteira->adicionarRecorrencia($recorrencia);
    } elseif ($tipo === 'entrada') {
        $receita = new Receita($valor, $descricao);
        $carteira->adicionarReceita($receita);
    } elseif ($tipo === 'saida') {
        $despesa = new Despesa($valor, $descricao);
        $carteira->adicionarDespesa($despesa);
    }
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
}

header('Location: index.php');
exit();
