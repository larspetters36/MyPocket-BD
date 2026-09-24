CREATE DATABASE IF NOT EXISTS mypocket;
USE mypocket;

CREATE TABLE IF NOT EXISTS recorrencias (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    tipo                ENUM('entrada', 'saida') NOT NULL,
    valor               DECIMAL(10,2) NOT NULL,
    descricao           VARCHAR(255) NOT NULL,
    tipo_recorrencia    ENUM('diaria', 'semanal', 'mensal', 'anual') NOT NULL,
    inicio              DATE NOT NULL,
    duracao             DECIMAL(10,2) NOT NULL COMMENT 'duração em meses',
    proxima_execucao    DATE NOT NULL,
    ativa               TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS transacoes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    tipo            ENUM('Entrada', 'Saída') NOT NULL,
    valor           DECIMAL(10,2) NOT NULL,
    descricao       VARCHAR(255) NOT NULL,
    recorrencia_id  INT DEFAULT NULL,
    criado_em       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recorrencia_id) REFERENCES recorrencias(id)
);
