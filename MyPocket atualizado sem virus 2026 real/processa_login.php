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
}