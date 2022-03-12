<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private array $lances;
    private string $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance): void
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance->getUsuario())) return;

        $totalLancesUsuario = $this->quantidadeLancesDoUsuario($lance->getUsuario());
        if ($totalLancesUsuario >= 5) return;

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    private function ehDoUltimoUsuario(Usuario $usuario): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $ultimoLance->getUsuario() === $usuario;
    }

    private function quantidadeLancesDoUsuario(Usuario $usuario): int
    {
        $totalLancesUsuario = array_reduce(
            $this->lances,
            function (int $somatoria, Lance $lance) use ($usuario) {
                if ($lance->getUsuario() == $usuario) {
                    return $somatoria + 1;
                }

                return $somatoria;
            },
            0
        );

        return $totalLancesUsuario;
    }
}
