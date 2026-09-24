<?php
declare(strict_types=1);

require_once 'Despesa.php';
require_once 'Receita.php';
require_once 'Recorrencia.php';

class Carteira {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function adicionarDespesa(Despesa $despesa, ?int $recorrenciaId = null, ?string $data = null): void {
        if ($despesa->getValor() > $this->getSaldo()) {
            throw new Exception("Saldo insuficiente.");
        }

        $this->inserirTransacao($despesa, $recorrenciaId, $data);
    }

    public function adicionarReceita(Receita $receita, ?int $recorrenciaId = null, ?string $data = null): void {
        $this->inserirTransacao($receita, $recorrenciaId, $data);
    }

    private function inserirTransacao(Transacao $transacao, ?int $recorrenciaId = null, ?string $data = null): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO transacoes (tipo, valor, descricao, recorrencia_id, criado_em)
             VALUES (:tipo, :valor, :descricao, :recorrencia_id, :criado_em)"
        );

        $stmt->execute([
            'tipo' => $transacao->getTipo(),
            'valor' => $transacao->getValor(),
            'descricao' => $transacao->getDescricao(),
            'recorrencia_id' => $recorrenciaId,
            'criado_em' => $data ?? date('Y-m-d H:i:s'),
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
            "SELECT id, tipo, valor, descricao, criado_em FROM transacoes ORDER BY criado_em DESC, id DESC"
        );

        return $stmt->fetchAll();
    }

    public function pegaTransacaoId(int $id): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT id, tipo, valor, descricao, criado_em FROM transacoes WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        $transacao = $stmt->fetch();

        return $transacao ?: null;
    }

    public function editarTransacao(int $id, string $tipo, float $valor, string $descricao): void {
        $stmt = $this->pdo->prepare(
            "UPDATE transacoes SET tipo = :tipo, valor = :valor, descricao = :descricao WHERE id = :id"
        );

        $stmt->execute([
            'tipo' => $tipo,
            'valor' => $valor,
            'descricao' => $descricao,
            'id' => $id,
        ]);
    }

    public function removerTransacao(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM transacoes WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }



    public function adicionarRecorrencia(Recorrencia $recorrencia): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO recorrencias
                (tipo, valor, descricao, tipo_recorrencia, inicio, duracao, proxima_execucao, ativa)
             VALUES
                (:tipo, :valor, :descricao, :tipo_recorrencia, :inicio, :duracao, :proxima_execucao, 1)"
        );

        $stmt->execute([
            'tipo' => $recorrencia->getTipo(),
            'valor' => $recorrencia->getValor(),
            'descricao' => $recorrencia->getDescricao(),
            'tipo_recorrencia' => $recorrencia->getTipoRecorrencia(),
            'inicio' => $recorrencia->getInicio(),
            'duracao' => $recorrencia->getDuracao(),
            'proxima_execucao' => $recorrencia->getInicio(),
        ]);
    }

    public function getRecorrenciasAtivas(): array {
        $stmt = $this->pdo->query(
            "SELECT id, tipo, valor, descricao, tipo_recorrencia, proxima_execucao
             FROM recorrencias WHERE ativa = 1 ORDER BY proxima_execucao"
        );

        return $stmt->fetchAll();
    }


    public function processarRecorrencias(): void {
        $hoje = new DateTime('today');

        $stmt = $this->pdo->query("SELECT * FROM recorrencias WHERE ativa = 1");

        foreach ($stmt->fetchAll() as $r) {
            $proxima = new DateTime($r['proxima_execucao']);
            $limite = (new DateTime($r['inicio']))->modify('+' . (int) round((float) $r['duracao']) . ' months');

            if ($proxima > $hoje) {
                continue; 
            }

            while ($proxima <= $hoje && $proxima <= $limite) {
                try {
                    if ($r['tipo'] === 'entrada') {
                        $this->adicionarReceita(
                            new Receita((float) $r['valor'], $r['descricao']),
                            (int) $r['id'],
                            $proxima->format('Y-m-d H:i:s')
                        );
                    } else {
                        $this->adicionarDespesa(
                            new Despesa((float) $r['valor'], $r['descricao']),
                            (int) $r['id'],
                            $proxima->format('Y-m-d H:i:s')
                        );
                    }
                } catch (Exception $e) {
                }

                $proxima = $this->proximaData($proxima, $r['tipo_recorrencia']);
            }

            $update = $this->pdo->prepare(
                "UPDATE recorrencias SET proxima_execucao = :proxima, ativa = :ativa WHERE id = :id"
            );
            $update->execute([
                'proxima' => $proxima->format('Y-m-d'),
                'ativa' => $proxima <= $limite ? 1 : 0,
                'id' => $r['id'],
            ]);
        }
    }

    private function proximaData(DateTime $data, string $tipo): DateTime {
        $nova = clone $data;

        switch ($tipo) {
            case 'diaria':
                $nova->modify('+1 day');
                break;
            case 'semanal':
                $nova->modify('+1 week');
                break;
            case 'mensal':
                $nova->modify('+1 month');
                break;
            case 'anual':
                $nova->modify('+1 year');
                break;
        }

        return $nova;
    }

   
    public function saldoAte(DateTime $data): float {
        $hoje = new DateTime('today');
        $projecao = $this->getSaldo();

        $stmt = $this->pdo->query("SELECT * FROM recorrencias WHERE ativa = 1");

        foreach ($stmt->fetchAll() as $r) {
            $proxima = new DateTime($r['proxima_execucao']);
            $limite = (new DateTime($r['inicio']))->modify('+' . (int) round((float) $r['duracao']) . ' months');

            while ($proxima <= $data && $proxima <= $limite) {
                if ($proxima >= $hoje) {
                    $projecao += $r['tipo'] === 'entrada' ? (float) $r['valor'] : -(float) $r['valor'];
                }
                $proxima = $this->proximaData($proxima, $r['tipo_recorrencia']);
            }
        }

        return $projecao;
    }

    public function fimMesSaldo(): float {
        $fimMes = new DateTime((new DateTime('today'))->format('Y-m-t'));
        return $this->saldoAte($fimMes);
    }

 
    private function saldoRealAte(DateTime $data): float {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(CASE WHEN tipo = 'Entrada' THEN valor ELSE -valor END), 0) AS saldo
             FROM transacoes WHERE criado_em <= :data"
        );
        $stmt->execute(['data' => $data->format('Y-m-d 23:59:59')]);

        return (float) $stmt->fetch()['saldo'];
    }

    public function projetarMes(int $ano): array {
        $hoje = new DateTime('today');
        $projecoes = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $fimMes = new DateTime(sprintf('%04d-%02d-01', $ano, $mes));
            $fimMes->modify('last day of this month');

            $projecoes[$mes] = $fimMes < $hoje
                ? $this->saldoRealAte($fimMes)
                : $this->saldoAte($fimMes);
        }

        return $projecoes;
    }
    
}