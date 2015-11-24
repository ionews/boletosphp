<?php
namespace Boletos\Bancos;

use Boletos\Boleto;

class BanPara extends Boleto
{

    public function __construct()
    {
        parent::__construct();

        $this->setCodigoBanco('037')
            ->geraCodigoBanco()
            ->setNumeroMoeda(9);

        $this->logo_banco = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/logobanpara.png', 'r'), filesize(self::$basepath . '/templates/imagens/logobanpara.png')));
    }

    public function getAgenciaCodigoBoleto()
    {
        $agencia  = $this->formataValor($this->getCedente()->getAgencia(), 4, 0);
        $conta    = $this->getCedente()->getConta();
        $conta_dv = $this->getContaDv();

        return $agencia . '/' . $conta . "-" . $conta_dv;
    }

    public function calculaDigitoVerificadorCodigoBarras()
    {
        $cedente = $this->cedente;

        $campo1  = $this->getCodigoBanco();
        $campo1 .= $this->getNumeroMoeda();
        $campo1 .= $cedente->getAgencia();
        $campo1 .= substr($this->getCarteira(), 0, 1);
        $campo1 .= $this->geraModulo10($campo1);

        $campo2  = substr($this->getCarteira(), 1);
        $campo2 .= substr($this->getNossoNumero(), 0, 5);
        $campo2 .= $this->geraModulo10($campo2);

        $campo3  = substr($this->getNossoNumero(), 7);
        $campo3 .= $this->geraModulo11($cedente->getAgencia() . $cedente->getConta() . $this->getCarteira() . $this->getNossoNumero());
        $campo3 .= $this->formataValor($cedente->getConta() . $this->getContaDv(), 8, 0);
        $campo3 .= $this->geraModulo10($campo3);

        $campo5  = $this->geraFatorVencimento();
        $campo5 .= $this->formataValor($cedente->getConta() . $this->getContaDv(), 8, 0);

        $linha  = $campo1 . $campo2 . $campo3 . $campo5;
        $codigo = substr($linha, 0, 4) . substr($linha, 32, 15) . substr($linha, 4, 5) . substr($linha, 10, 10) . substr($linha, 21, 10);

        return $this->geraModulo11($codigo, 9, 1);
    }

    public function geraCodigoBarras()
    {
        $this->verificaCodigoBarras();
        
        $cedente = $this->cedente;

        $codigo_barras  = $this->getCodigoBanco();
        $codigo_barras .= $this->getNumeroMoeda();
        $codigo_barras .= $this->geraFatorVencimento();
        $codigo_barras .= $this->formataValor($this->getValorBoleto(), 10, 0, "valor");
        $codigo_barras .= $cedente->getAgencia();
        $codigo_barras .= $this->getCarteira();
        $codigo_barras .= $this->getNossoNumero();
        $codigo_barras .= $this->geraModulo11($cedente->getAgencia() . $cedente->getConta() . $this->getCarteira() . $this->getNossoNumero());
        $codigo_barras .= $this->formataValor($cedente->getConta() . $this->getContaDv(), 8, 0);

        $this->codigo_barras    = $codigo_barras;
        $this->codigo_barras_dv = $this->calculaDigitoVerificadorCodigoBarras();
        $this->codigo_barras44  = substr($this->codigo_barras, 0, 4) . $this->codigo_barras_dv . substr($this->codigo_barras, 4, 43);

        return $this;
    }

    public function getLinhaDigitavel()
    {
        $codigo = $this->getCodigoBarras44();

        /*
         * Campo 1 (AAABC.CCCDX)
         *    AAA = Código do Banco na Câmara de Compensação (BANPARÁ = 037)
         *    B = Código da moeda = "9" (*)
         *    C = Primeiro dígito do número da agência do cedente
         *    CCC = Três últimos números da agência do cedente
         *    D = Primeiro dígito do Código da Carteira
         *    X = DAC que amarra o campo 1
         */
        $aaa    = substr($codigo, 0, 3);
        $b      = substr($codigo, 3, 1);
        $c      = substr($codigo, 19, 1);
        $ccc    = substr($codigo, 20, 3);
        $d      = substr($codigo, 23, 1);
        $x      = $this->geraModulo10($aaa . $b . $c . $ccc . $d);
        $campo1 = $aaa . $b . $c . '.' . $ccc . $d . $x;

        /*
         * Campo 2 (DDDEE.EEEEEY)
         *    DDD = Restante dos dígitos do Código da Carteira
         *    EE EEEEE = Nosso Número
         *    Y = DAC que amarra o campo 2
         */
        $ddd      = substr($codigo, 24, 3);
        $ee       = substr($codigo, 27, 2);
        $eeeee    = substr($codigo, 29, 5);
        $y        = $this->geraModulo10($ddd . $ee . $eeeee);
        $campo2   = $ddd . $ee . '.' . $eeeee . $y;

        /*
         * Campo 3 (EFGGGG.GGGGZ)
         *    E = Restante do Nosso Número
         *    F = DAC [Agência /Conta (sem digito verificador) /Carteira/Nosso Número]
         *    GGGG GGGG = Número da conta corrente com Dígito Verificador
         *    Z = DAC que amarra o campo 3
         */
        $tmp    = $this->formataValor($this->cedente->getConta() . $this->getContaDv(), 8, 0);

        $e      = substr($codigo, 34, 1);
        $f      = $this->geraModulo11($this->cedente->getAgencia() . $this->cedente->getConta() . $this->getCarteira() . $this->getNossoNumero());
        $gggg1  = substr($tmp, 0, 3);
        $gggg2  = substr($tmp, 3);
        $z      = $this->geraModulo10($e . $f . $gggg1 . $gggg2);
        $campo3 = $e . $f . $gggg1 . '.' . $gggg2 . $z;

        /*
         * Campo 4 (K)
         *    K = DAC do Código de Barras (Mód. 11)
         */
        $campo4      = substr($codigo, 4, 1);

        /*
         * Campo 5 (UUUUVVVVVVVVVV)
         *    UUUU = Fator de vencimento
         *    VVVVVVVVVV = Valor do Título (*)
         */
        $uuuu       = substr($codigo, 5, 4);
        $vvvvvvvvvv = substr($codigo, 9, 10);
        $campo5     = $uuuu . $vvvvvvvvvv;
        
        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }
}
