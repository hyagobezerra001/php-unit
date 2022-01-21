<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances seguidos');
        $leilao = new Leilao('Variante 0KM');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

    }
    /**
     * @dataProvider geraLance
     */
    public function testaLeitao(int $quantidade, Leilao $leilao, array $valores)
    {
        self::assertCount($quantidade,$leilao->getLances());
        foreach ($valores as $index => $valor){
            self::assertEquals($valor,$leilao->getLances()[$index]->getValor());
        }
    }

    public function geraLance()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao = new Leilao('Fiat 0KM');
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 3000));

        $leilaoum = new Leilao('Vocho 0KM');
        $leilaoum->recebeLance(new Lance($joao, 1000));
        return [
            "2 Lances" => [2, $leilao, [2000,3000]],
            "1 Lance" => [1, $leilaoum, [1000]]
        ];
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não propor mais de 5 lances por leilão');
        $leilao = new Leilao('Brasília Amarela');
        $joao   = new Usuario('João');
        $maria  = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));
        $leilao->recebeLance(new Lance($joao, 6000));

    }
}