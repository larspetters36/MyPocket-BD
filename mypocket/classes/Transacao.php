<?php
declare(strict_types=1);

abstract class Transacao {
    private float $valor;
    private string $descricao;

    public function __construct(float $valor, string $descricao) {
        $this->valor = $valor;
        $this->descricao = $descricao;
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function getDescricao(): string {
        return $this->descricao;
    }

    abstract public function getTipo(): string;
}
