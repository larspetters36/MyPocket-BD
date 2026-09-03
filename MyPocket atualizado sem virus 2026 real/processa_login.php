<?php

require_once 'config/conexao.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if(empty($nome) || empty($email) || empty($senha)) {
        throw new Exception("Preencha todos os campos.");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Insira um email valido.");
    }
    $senhaCripto = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
                       ':nome' => $nome,
                       ':email' => $email,
                       ':senha' => $senhaCripto
                    ]);
    } catch (\PDOException $e){
        
    }
}
