<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberMaisDeCincoLancesDoMesmoUsuario()
    {
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
        $leilao->recebeLance(new Lance($joao, 1500));
        $leilao->recebeLance(new Lance($ana, 2500));
        $leilao->recebeLance(new Lance($joao, 2500));
        $leilao->recebeLance(new Lance($ana, 3500));
        $leilao->recebeLance(new Lance($joao, 3500));
        $leilao->recebeLance(new Lance($ana, 4500));
        $leilao->recebeLance(new Lance($joao, 4500));

        // Caso da falha
        $leilao->recebeLance(new Lance($ana, 5500));

        self::assertCount(10, $leilao->getLances());
        self::assertEquals(4500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        self::assertCount(1, $leilao->getLances());
        self::assertEquals(1000, $leilao->getLances()[0]->getValor());
    }

    /**
     * @dataProvider entregaLeiloes
     */
    public function testLeilaoDeveReceberLances(int $quantLances, Leilao $leilao, array $valoresLances)
    {
        self::assertCount($quantLances, $leilao->getLances());
        foreach ($valoresLances as $indice => $valorLance) {
            self::assertEquals($valorLance, $leilao->getLances()[$indice]->getValor());
        }
    }

    public function leilaoComUmLance()
    {
        $joao = new Usuario('João');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance($joao, 1000));

        return $leilao;
    }

    public function leilaoComDoisLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));

        return $leilao;
    }

    public function entregaLeiloes()
    {
        return [
            'leilao-com-um-lance' => [1, $this->leilaoComUmLance(), [1000]],
            'leilao-com-dois-lances' => [2, $this->leilaoComDoisLances(), [1000, 2000]],
        ];
    }
}