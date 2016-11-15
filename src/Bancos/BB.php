<?php
namespace Boletos\Bancos;

use Boletos\Boleto;

class BB extends Boleto
{
    protected $convenio;

    protected $formato_convenio;

    public function __construct()
    {
        parent::__construct();

        $this->setCodigoBanco('001')
            ->geraCodigoBanco()
            ->setNumeroMoeda(9);

        $this->logo_banco = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/logobb.jpg', 'r'), filesize(self::$basepath . '/templates/imagens/logobb.jpg')));
    }

    public function getAgenciaCodigoBoleto()
    {
        $agencia = $this->formataValor($this->getCedente()->getAgencia(), 4, 0);
        $conta   = $this->formataValor($this->getCedente()->getConta(), 8, 0);

        return $agencia . '-' . $this->geraModulo11($agencia, 9, 0) . ' / ' . $conta . "-" . $this->geraModulo11($conta, 9, 0);
    }

    public function calculaDigitoVerificadorCodigoBarras()
    {
        return $this->geraModulo11($this->getCodigoBarras(), 9, 1);
    }

    public function geraCodigoBarras()
    {
        parent::verificaCodigoBarras();

        $cedente     = $this->getCedente();
        $livre_zeros = '000000';

        $codigo_barras  = $this->getCodigoBanco();
        $codigo_barras .= $this->getNumeroMoeda();
        $codigo_barras .= $this->geraFatorVencimento();
        $codigo_barras .= $this->formataValor($this->getValorBoleto(), 10, 0, 'valor');
        $convenio       = $this->formataValor($this->getConvenio(), $this->getFormatoConvenio(), 0, 'convenio');
        $nosso_numero   = $this->formataValor($this->getNossoNumero(), (10 - $this->getFormatoConvenio()), 0);

        if ($this->getFormatoConvenio() == '6') {
            $codigo_barras .= $convenio;

            if ($this->getFormataNossoNumero() == '1') {
                $nosso_numero   = $this->formataValor($this->getNossoNumero(), 5, 0);
                $codigo_barras .= $nosso_numero;
                $codigo_barras .= $cedente->getAgencia();
                $codigo_barras .= $cedente->getConta();
                $codigo_barras .= $this->getCarteira();

            } else if ($this->getFormataNossoNumero() == '2') {
                $nservico = "21";
                $nosso_numero   = $this->formataValor($this->getNossoNumero(), 17, 0);
                $codigo_barras .= $nosso_numero;
                $codigo_barras .= $nservico;
            }
        } else {
            $codigo_barras .= $livre_zeros;
            $codigo_barras .= $convenio;
            $codigo_barras .= $nosso_numero;
            $codigo_barras .= $this->getCarteira();
        }

        $this->setNossoNumeroBoleto($convenio . $nosso_numero);
        if ($this->getFormatoConvenio() != 7) {
            $this->setNossoNumeroBoleto($this->getNossoNumeroBoleto() . "-" . $this->geraModulo11($this->getNossoNumeroBoleto()));
        }

        $this->codigo_barras    = $codigo_barras;
        $this->codigo_barras_dv = $this->calculaDigitoVerificadorCodigoBarras();
        $this->codigo_barras44  = substr($this->getCodigoBarras(), 0, 4) . $this->getCodigoBarrasDv() . substr($this->getCodigoBarras(), 4, 43);

        return $this;
    }

    public function getLinhaDigitavel()
    {
        $codigo = $this->getCodigoBarras44();

        /*
         * Campo 1 (AAABL.LLLLX)
         *    AAA = Código do Banco na Câmara de Compensação (BB = 001)
         *    B = Código da moeda = "9" (*)
         *    L = Primeiro dígito do Convenio e Nosso Numero
         *    LLLL = Quatro próximos números do Convenio e/ou Nosso Numero
         *    X = DAC que amarra o campo 1
         */
        $aaa  = substr($codigo, 0, 3);
        $b    = substr($codigo, 3, 1);
        $l    = substr($codigo, 19, 1);
        $llll = substr($codigo, 20, 4);
        $x    = $this->geraModulo10($aaa . $b . $l . $llll);
        $campo1 = "$aaa$b$l.$llll$x";

        /*
         * Campo 2 (LLLLL.LLLLLY)
         *    LLLLL LLLLL = Próximos dígitos do Convenio e/ou Nosso Numero
         *    Y = DAC que amarra o campo 2
         */
        $lllll1 = substr($codigo, 24, 5);
        $lllll2 = substr($codigo, 29, 5);
        $y      = $this->geraModulo10($lllll1 . $lllll2);
        $campo2 = "$lllll1.$lllll2$y";


        /*
         * Campo 3 (LLLLL.LLLLLZ)
         *    E = Restante do Convenio e/ou Nosso Número
         *    F = DAC [Agência /Conta (sem digito verificador) /Carteira/Nosso Número]
         *    GGGG GGGG = Número da conta corrente com Dígito Verificador
         *    Z = DAC que amarra o campo 3
         */
        $lllll1 = substr($codigo, 34, 5);
        $lllll2 = substr($codigo, 39, 5);
        $z = $this->geraModulo10($lllll1 . $lllll2);
        $campo3 = "$lllll1.$lllll2$z";

        /*
         * Campo 4 (K)
         *    K = DAC do Código de Barras (Mód. 11)
         */
        $campo4 = substr($codigo, 4, 1);


        /*
         * Campo 5 (VVVVVVVVVVVVVV)
         *    VVVVVVVVVVVVVV = Valor do Título (*)
         */
        $campo5 = substr($codigo, 5, 14);

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }

    public function geraModulo11($num, $base = 9, $r = 0)
    {
        $soma = 0;
        $fator = 2;
        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num,$i-1,1);
            $parcial[$i] = $numeros[$i] * $fator;
            $soma += $parcial[$i];
            if ($fator == $base) {
                $fator = 1;
            }
            $fator++;
        }
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;

            //corrigido
            if ($digito == 10) {
                $digito = "X";
            }

            /*
            alterado por mim, Daniel Schultz

            Vamos explicar:

            O módulo 11 só gera os digitos verificadores do nossonumero,
            agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digitável)
            só que é foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...

            No BB, os dígitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
            mas nunca pode ser X ou 0 (zero) para a linha digitável, justamente por ser totalmente numérica.

            Quando passamos os dados para a função, fica assim:

            Agencia = sempre 4 digitos
            Conta = até 8 dígitos
            Nosso número = de 1 a 17 digitos

            A unica variável que passa 17 digitos é a da linha digitada, justamente por ter 43 caracteres

            Entao vamos definir ai embaixo o seguinte...

            se (strlen($num) == 43) { não deixar dar digito X ou 0 }
            */

            if (strlen($num) == "43") {
                //então estamos checando a linha digitável
                if ($digito == "0" or $digito == "X" or $digito > 9) {
                        $digito = 1;
                }
            }
            return $digito;
        }
        elseif ($r == 1){
            $resto = $soma % 11;
            return $resto;
        }
    }

    protected function geraModulo10($num)
    {
        $numtotal10 = 0;
        $fator = 2;

        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num,$i-1,1);
            $parcial10[$i] = $numeros[$i] * $fator;
            $numtotal10 .= $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            }
            else {
                $fator = 2;
            }
        }

        $soma = 0;
        for ($i = strlen($numtotal10); $i > 0; $i--) {
            $numeros[$i] = substr($numtotal10,$i-1,1);
            $soma += $numeros[$i];
        }
        $resto = $soma % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;
    }
}
