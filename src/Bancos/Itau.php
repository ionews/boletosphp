<?php
namespace Boletos\Bancos;

use Boletos\Boleto;

class Itau extends Boleto
{

    public function __construct()
    {
        parent::__construct();

        $this->setCodigoBanco('341')
            ->geraCodigoBanco()
            ->setNumeroMoeda(9);

        $this->logo_banco = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/logoitau.png', 'r'), filesize(self::$basepath . '/templates/imagens/logoitau.png')));
    }

    public function calculaDigitoVerificadorCodigoBarras()
    {
        return $this->geraModulo11($this->getCodigoBarras(), 9, 1);
    }

    public function geraCodigoBarras()
    {
        $this->verificaCodigoBarras();

        $cedente = $this->cedente;

        $codigo_barras  = $this->getCodigoBanco();
        $codigo_barras .= $this->getNumeroMoeda();
        $codigo_barras .= $this->geraFatorVencimento();
        $codigo_barras .= $this->formataValor($this->valor_boleto, 10, 0, "valor");
        $codigo_barras .= $this->carteira;
        $codigo_barras .= $this->nosso_numero;
        $codigo_barras .= $this->geraModulo10($cedente->getAgencia() . $cedente->getConta() . $this->carteira . $this->nosso_numero);
        $codigo_barras .= $cedente->getAgencia();
        $codigo_barras .= $cedente->getConta();
        $codigo_barras .= $this->geraModulo10($cedente->getAgencia() . $this->cedente->getConta());
        $codigo_barras .= '000';

        $this->codigo_barras    = $codigo_barras;

        $this->codigo_barras_dv = $this->calculaDigitoVerificadorCodigoBarras();
        $this->codigo_barras44  = substr($this->getCodigoBarras(), 0, 4) . $this->getCodigoBarrasDv() . substr($this->getCodigoBarras(), 4, 43);
        
        return $this;
    }

    public function getLinhaDigitavel()
    {
        $codigo = $this->getCodigoBarras44();

        // campo 1
        $banco    = substr($codigo, 0, 3);
        $moeda    = substr($codigo, 3, 1);
        $ccc      = substr($codigo, 19, 3);
        $ddnnum   = substr($codigo, 22, 2);
        $dv1      = $this->geraModulo10($banco.$moeda.$ccc.$ddnnum);
        // campo 2
        $resnnum  = substr($codigo, 24, 6);
        $dac1     = substr($codigo, 30, 1);//modulo_10($agencia.$conta.$carteira.$nnum);
        $dddag    = substr($codigo, 31, 3);
        $dv2      = $this->geraModulo10($resnnum.$dac1.$dddag);
        // campo 3
        $resag    = substr($codigo, 34, 1);
        $contadac = substr($codigo, 35, 6); //substr($codigo,35,5).modulo_10(substr($codigo,35,5));
        $zeros    = substr($codigo, 41, 3);
        $dv3      = $this->geraModulo10($resag.$contadac.$zeros);
        // campo 4
        $dv4      = substr($codigo, 4, 1);
        // campo 5
        $fator    = substr($codigo, 5, 4);
        $valor    = substr($codigo, 9, 10);

        $campo1 = substr($banco.$moeda.$ccc.$ddnnum.$dv1, 0, 5) . '.' . substr($banco.$moeda.$ccc.$ddnnum.$dv1, 5, 5);
        $campo2 = substr($resnnum.$dac1.$dddag.$dv2, 0, 5) . '.' . substr($resnnum.$dac1.$dddag.$dv2, 5, 6);
        $campo3 = substr($resag.$contadac.$zeros.$dv3, 0, 5) . '.' . substr($resag.$contadac.$zeros.$dv3, 5, 6);
        $campo4 = $dv4;
        $campo5 = $fator.$valor;

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }
}
