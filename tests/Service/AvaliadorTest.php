<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    /** @var Avaliador */
    private Avaliador $leiloeiro;

    protected function setUp():void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorBuscaMaiorValor(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);
        $maiorvalor = $this->leiloeiro->getMaiorValor();

        //Assert - Then
        self::assertEquals(2500, $maiorvalor);
    }
    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorBuscaMenorValor(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);
        $menorvalor = $this->leiloeiro->getManorValor();

        //Assert - Then
        self::assertEquals(1700, $menorvalor);
    }
    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorBusca3MaioresValores(Leilao $leilao)
    {
            $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();
        self::assertCount(3,$maiores);
        self::assertEquals(2500, $maiores[0]->getValor());
        self::assertEquals(2000, $maiores[1]->getValor());
        self::assertEquals(1700, $maiores[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    /* ---------- Dados ----------- */

    public function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Fiat 147OKM');

        $maria  = new Usuario('Maria');
        $joao   = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            "Ordem Crescente" =>[$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Fiat 147OKM');

        $maria  = new Usuario('Maria');
        $joao   = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            "Ordem Descrecente" =>[$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria(): array
    {
        $leilao = new Leilao('Fiat 147OKM');

        $maria  = new Usuario('Maria');
        $joao   = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            "Aleatorio" => [$leilao]
        ];
    }

}