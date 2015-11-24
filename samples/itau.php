<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';

use Boletos\Bancos\Itau;
use Boletos\Cedente;
use Boletos\Sacado;

// DADOS DO BOLETO PARA O SEU CLIENTE
$boleto = new Itau();
$boleto->setPrazoPagamento(5);
$boleto->setTaxaEmissao(2.95);
$boleto->setNossoNumero('12345678');
$boleto->setNumeroDocumento('0123');
$boleto->setDataVencimento(new \DateTime());
$boleto->setDataEmissao(new \DateTime());
$boleto->setDataProcessamento(new \DateTime());
$boleto->setValorBoleto(2950 + $boleto->getTaxaEmissao());

// DADOS DO SEU CLIENTE
$sacado = new Sacado();
$sacado->setNome('Nome do seu Cliente');
$sacado->setEndereco('Endereço do seu Cliente');
$sacado->setEnderecoComplemento('Cidade - Estado -  CEP: 00000-000');
$boleto->setSacado($sacado);

// INFORMACOES PARA O CLIENTE
$boleto->setObservacao1('Pagamento de Compra na Loja Nonononono');
$boleto->setObservacao2('Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ '.number_format($boleto->getTaxaEmissao(), 2, ',', ''));
$boleto->setObservacao3('OOBoletoPhp - http://www.OOBoletoPhp.com.br');
$boleto->setInstrucao1('- Sr. Caixa, cobrar multa de 2% após o vencimento');
$boleto->setInstrucao2('- Receber até 10 dias após o vencimento');
$boleto->setInstrucao3('- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br');
$boleto->setInstrucao4('&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br');

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$boleto->setQuantidade('');
$boleto->setValorUnitario('');
$boleto->setAceite('');
$boleto->setEspecie('R$');
$boleto->setEspecieDoc('');

// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
$cedente = new Cedente();
// DADOS DA SUA CONTA
$cedente->setAgencia('1565');
$cedente->setConta('13877');
$boleto->setCarteira('175');
// SEUS DADOS
$cedente->setCpfCnpj('111.111.111-11');
$cedente->setEndereco('Coloque o endereço da sua empresa aqui');
$cedente->setCidadeUf('Cidade / Estado');
$cedente->setNome('Coloque a Razão Social da sua empresa aqui');
$boleto->setCedente($cedente);


$boleto->html();
