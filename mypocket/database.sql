CREATE DATABASE IF NOT EXISTS mypocket;
USE mypocket;

CREATE TABLE IF NOT EXISTS transacoes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    tipo        ENUM('Entrada', 'Saída') NOT NULL,
    valor       DECIMAL(10,2) NOT NULL,
    descricao   VARCHAR(255) NOT NULL,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
