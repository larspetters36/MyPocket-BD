<?php
declare(strict_types=1);

class Recorrencia {
    private string $tipo;           
    private float $valor;
    private string $descricao;
    private string $tipoRecorrencia; 
    private string $inicio;           
    private float $duracao;           

    public function __construct(
        string $tipo,
        float $valor,
        string $descricao,
        string $tipoRecorrencia,
        string $inicio,
        float $duracao
    ) {
        if ($valor <= 0) {
            throw new Exception("Informe um valor válido para a recorrência.");
        }
        if (!in_array($tipoRecorrencia, ['diaria', 'semanal', 'mensal', 'anual'], true)) {
            throw new Exception("Tipo de recorrência inválido.");
        }
        if ($duracao <= 0) {
            throw new Exception("Informe uma duração válida.");
        }

        $this->tipo = $tipo;
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->tipoRecorrencia = $tipoRecorrencia;
        $this->inicio = $inicio;
        $this->duracao = $duracao;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    public function getTipoRecorrencia(): string {
        return $this->tipoRecorrencia;
    }

    public function getInicio(): string {
        return $this->inicio;
    }

    public function getDuracao(): float {
        return $this->duracao;
    }
}
