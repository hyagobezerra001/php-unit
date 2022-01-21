<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require_once 'vendor/autoload.php';

// Arrange - Given
$leilao = new Leilao('Fiat 147OKM');
$maria = new Usuario('Maria');
$joao = new Usuario('João');
$leilao->recebeLance(new Lance($joao, 3000));
$leilao->recebeLance(new Lance($maria, 2500));

// Act - When
$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);
$maiorvalor = $leiloeiro->getMaiorValor();

//Assert - Then
$valorEsperado = 2500;

if($valorEsperado == $maiorvalor){
    echo "TESTE OK" . PHP_EOL;
}else{
    echo "TESTE FALHOU" . PHP_EOL;
};

echo $maiorvalor;