<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';

use Boletos\Bancos\BanPara;
use Boletos\Cedente;
use Boletos\Sacado;

// DADOS DO BOLETO PARA O SEU CLIENTE
$boleto = new BanPara();
$boleto->setPrazoPagamento(8);
$boleto->setTaxaEmissao(1.50);
$boleto->setNossoNumero('1234567');
$boleto->setNumeroDocumento('01234567890');
$boleto->setDataVencimento(new \DateTime());
$boleto->setDataEmissao(new \DateTime());
$boleto->setDataProcessamento(new \DateTime());
$boleto->setValorBoleto(9.99);

// DADOS DO SEU CLIENTE
$sacado = new Sacado();
$sacado->setNome('Nome do seu Cliente');
$sacado->setEndereco('Endereço do seu Cliente');
$sacado->setEnderecoComplemento('Cidade - Estado -  CEP: 00000-000');
$boleto->setSacado($sacado);

// INFORMACOES PARA O CLIENTE
$boleto->setObservacao1('Pagamento de Compra na Loja Nonononono');
$boleto->setObservacao2('Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ' . number_format($boleto->getTaxaEmissao(), 2, ',', ''));
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
$cedente->setAgencia('0011');
$cedente->setConta('181675');
$boleto->setCarteira('9999');
$boleto->setContaDv('6');
// SEUS DADOS
$cedente->setCpfCnpj('Coloque aqui o CPF ou CNPJ');
$cedente->setEndereco('Coloque o endereço da sua empresa aqui');
$cedente->setCidadeUf('Cidade / Estado');
$cedente->setNome('Coloque a Razão Social da sua empresa aqui');
$boleto->setCedente($cedente);


$boleto->html();
