<?php

require_once 'classes/Transacao.php';
require_once 'classes/Receita.php';
require_once 'classes/Despesa.php';
require_once 'classes/Carteira.php';
require_once 'config/conexao.php'; 

session_start();

$carteira = new Carteira($pdo);

$id = (int) ($_POST['id'] ?? 0);

try {
    if (empty($id)) {
        throw new Exception("Transação inválida.");
    }

    $carteira->removerTransacao($id);
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
}

header('Location: index.php');
exit();