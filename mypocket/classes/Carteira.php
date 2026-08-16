<?php
declare(strict_types=1);

require_once 'Despesa.php';
require_once 'Receita.php';

class Carteira {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function adicionarDespesa(Despesa $despesa): void {
        if ($despesa->getValor() > $this->getSaldo()) {
            throw new Exception("Saldo insuficiente.");
        }

        $this->inserirTransacao($despesa);
    }

    public function adicionarReceita(Receita $receita): void {
        $this->inserirTransacao($receita);
    }

    private function inserirTransacao(Transacao $transacao): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO transacoes (tipo, valor, descricao) VALUES (:tipo, :valor, :descricao)"
        );

        $stmt->execute([
            'tipo' => $transacao->getTipo(),
            'valor' => $transacao->getValor(),
            'descricao' => $transacao->getDescricao(),
        ]);
    }

    public function getSaldo(): float {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(SUM(CASE WHEN tipo = 'Entrada' THEN valor ELSE -valor END), 0) AS saldo
             FROM transacoes"
        );

        return (float) $stmt->fetch()['saldo'];
    }

    
    public function getTransacoes(): array {
        $stmt = $this->pdo->query(
            "SELECT tipo, valor, descricao, criado_em FROM transacoes ORDER BY criado_em DESC, id DESC"
        );

        return $stmt->fetchAll();
    }
}
