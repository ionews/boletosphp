<?php
namespace Boletos;

/**
 * Classe base para geração de boletos bancários
 * @method string getCodigoBarras()
 * @method int getCodigoBanco()
 * @method int getCodigoBancoDv()
 * @method Boleto setCodigoBarrasDv()
 * @method string getCodigoBarrasDv()
 * @method string getCodigoBarras44()
 * @method Boleto setCodigoBarras44(string $string)
 * @method Boleto setInstrucao1(string $string)
 * @method Boleto setInstrucao2(string $string)
 * @method Boleto setInstrucao3(string $string)
 * @method Boleto setInstrucao4(string $string)
 * @method string getInstrucao1()
 * @method string getInstrucao2()
 * @method string getInstrucao3()
 * @method string getInstrucao4()
 * @method string getCarteira()
 * @method \Pires\Boleto\Cedente getCedente()
 * @method Boleto setCodigoBarras(string $string)
 * @method string getNossoNumero()
 * @method int getNumeroMoeda()
 * @method int getPrazoPagamento()
 * @method float getTaxaEmissao()
 * @method \DateTime getDataVencimento()
 * @method \DateTime getDataEmissao()
 */
abstract class Boleto extends BaseClass
{

    protected static $basepath = __DIR__;

    /**
     * @var String Código do Banco
     */
    protected $codigo_banco;

    /**
     * @var String Código do Banco com DV
     */
    protected $codigo_banco_dv;

    /**
     * @var String Código de Barras
     */
    protected $codigo_barras;

    /**
     * @var String Dígito Verificador do Código de Barras
     */
    protected $codigo_barras_dv;

    /**
     * @var String Código de Barras com 44 dígitos
     */
    protected $codigo_barras44;

    /**
     * @var int Número Moeda
     */
    protected $numero_moeda;

    /**
     * @var int Dias de prazo para pagamento do boleto
     */
    protected $prazo_pagamento;

    /**
     * @var float Taxa de emissão do boleto
     */
    protected $taxa_emissao;

    /**
     * @var \Datetime Data de vencimento do boleto
     */
    protected $data_vencimento;

    /**
     * @var \Datetime Data de emissão do boleto
     */
    protected $data_emissao;

    /**
     * @var \Datetime Data de processamento do boleto
     */
    protected $data_processamento;

    /**
     * @var float Valor do boleto
     */
    protected $valor_boleto;

    /**
     * @var String Nosso número
     */
    protected $nosso_numero;

    /**
     * @var String Nosso número no boleto
     */
    protected $nosso_numero_boleto;

    /**
     * @var String Número do documento
     */
    protected $numero_documento;

    /**
     * @var \Boletos\Sacado Sacado
     */
    protected $sacado;

    /**
     * @var String Linha 1 da Observação
     */
    protected $observacao1;

    /**
     * @var String Linha 2 da Observação
     */
    protected $observacao2;

    /**
     * @var String Linha 3 da Observação
     */
    protected $observacao3;

    /**
     * @var String Linha 4 da Observação
     */
    protected $observacao4;

    /**
     * @var String Linha 1 das Instruções
     */
    protected $instrucao1;

    /**
     * @var String Linha 2 das Instruções
     */
    protected $instrucao2;

    /**
     * @var String Linha 3 das Instruções
     */
    protected $instrucao3;

    /**
     * @var String Linha 4 das Instruções
     */
    protected $instrucao4;

    /**
     * @var String Linha 5 das Instruções
     */
    protected $instrucao5;

    /**
     * @var String Linha 6 das Instruções
     */
    protected $instrucao6;

    /**
     * @var String Linha 7 das Instruções
     */
    protected $instrucao7;

    /**
     * @var String Linha 8 das Instruções
     */
    protected $instrucao8;

    /**
     * @var int Quantidade
     */
    protected $quantidade;

    /**
     * @var float Valor Unitário
     */
    protected $valor_unitario;

    /**
     * @var String Aceite
     */
    protected $aceite;

    /**
     * @var String Espécie
     */
    protected $especie;

    /**
     * @var String Espécie Doc
     */
    protected $especie_doc;

    /**
     * @var String Dígito Verificador
     */
    protected $conta_dv;

    /**
     * @var String Carteira
     */
    protected $carteira;

    /**
     * @var String Convenio
     */
    protected $convenio;

    /**
     * @var String Contrato
     */
    protected $contrato;

    /**
     * @var String Variacao de Carteira
     */
    protected $variacao_carteira;

    /**
     * @var \Boletos\Cedente
     */
    protected $cedente;

    /**
     * @var float descontos do boleto
     */
    protected $desconto;

    /**
     * @var float outras deduções do boleto
     */
    protected $deducao;

    /**
     * @var float Juros do boleto
     */
    protected $mora_juro;

    /**
     * @var float Acrécimo no valor do boleto
     */
    protected $acrescimo;

    /**
     * @var float Acrécimo no valor do boleto
     */
    protected $valor_cobrado;

    /**
     * @var String Nome do Avalista
     */
    protected $avalista;

    /**
     * @var String Imagem do logotipo do banco
     */
    protected $logo_banco;

    /**
     * @var String Imagem Um
     */
    protected $imagem_um;

    /**
     * @var String Imagem Dois
     */
    protected $imagem_dois;

    /**
     * @var String Imagem Três
     */
    protected $imagem_tres;

    /**
     * @var String Imagem Seis
     */
    protected $imagem_seis;

    /**
     * @var String Imagem P
     */
    protected $imagem_p;

    /**
     * @var String Imagem B
     */
    protected $imagem_b;

    /**
     * Construtor da classe
     * @param \Respect\Config\Container Container com configurações iniciais
     */
    public function __construct()
    {
        $this->imagem_um = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/1.png', 'r'), filesize(self::$basepath . '/templates/imagens/1.png')));
        $this->imagem_dois = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/2.png', 'r'), filesize(self::$basepath . '/templates/imagens/2.png')));
        $this->imagem_tres = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/3.png', 'r'), filesize(self::$basepath . '/templates/imagens/3.png')));
        $this->imagem_seis = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/6.png', 'r'), filesize(self::$basepath . '/templates/imagens/6.png')));
        $this->imagem_p = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/p.png', 'r'), filesize(self::$basepath . '/templates/imagens/6.png')));
        $this->imagem_b = base64_encode(fread(fopen(self::$basepath . '/templates/imagens/b.png', 'r'), filesize(self::$basepath . '/templates/imagens/6.png')));
    }

    public function setContaDv($conta_dv)
    {
        $this->conta_dv = $this->formataValor($conta_dv, 1, 0);
    }

    public function setDataVencimento($data)
    {
        $data->add(new \DateInterval("P{$this->prazo_pagamento}D"));
        $this->data_vencimento = $data;
    }

    public function setNossoNumero($numero)
    {
        $this->nosso_numero = $this->formataValor($numero, 8, 0);
    }

    public function setValorBoleto($valor)
    {
        $this->valor_boleto += $valor;
    }

    public function html()
    {
        $this->geraCodigoBarras();

        $ref = new \ReflectionClass($this);
        $render = new Render($ref->getShortName(), ['boleto' => $this]);
        
        echo $render->html();
    }

    protected function geraCodigoBanco()
    {
        $parte1 = substr($this->getCodigoBanco(), 0, 3);
        $parte2 = $this->geraModulo11($parte1);
        $this->setCodigoBancoDv($parte1 . "-" . $parte2);
        return $this;
    }


    protected function geraModulo11($num, $base = 9, $r = 0)
    {
        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i-1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;

            if ($digito == 10) {
                $digito = 0;
            }
            $digito = 11 - $digito;

            if ($digito > 9 || $digito == 0) {
                $digito = 1;
            }
        } else if ($r == 1) {
            $digito = $soma % 11;
            $digito = 11 - $digito;

            if ($digito == 0 || $digito == 10 || $digito == 11) {
                $digito = 1;
            }
        }

        return $digito;
    }

    protected function geraModulo10($num)
    {
        $numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo (falor 10)
            $temp  = $numeros[$i] * $fator;
            $temp0 = 0;
            foreach (preg_split('//', $temp, -1, PREG_SPLIT_NO_EMPTY) as $k => $v) {
                $temp0 += $v;
            }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }
        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }
        return $digito;
    }

    public function geraFatorVencimento()
    {
        if (is_null($this->getDataVencimento())) {
            throw new \InvalidArgumentException("Data de Vencimento não informada", 1);
        } else if (! $this->getDataVencimento() instanceof \DateTime) {
            throw new \InvalidArgumentException("Data de Vencimento não é do tipo DateTime", 1);
        } else {
            $ano = $this->getDataVencimento()->format("Y");
            $mes = $this->getDataVencimento()->format("m");
            $dia = $this->getDataVencimento()->format("d");
            return (abs(($this->dateToDays("1997", "10", "07")) - ($this->dateToDays($ano, $mes, $dia))));
        }
    }

    private function dateToDays($year, $month, $day)
    {
        $century = substr($year, 0, 2);
        $year = substr($year, 2, 2);
        if ($month > 2) {
            $month -= 3;
        } else {
            $month += 9;
            if ($year) {
                $year--;
            } else {
                $year = 99;
                $century --;
            }
        }
        return (floor((146097 * $century) / 4) +
                floor((1461 * $year) / 4) +
                floor((153 * $month + 2) / 5) + $day +  1721119);
    }

    private function verificaCodigoBanco()
    {
        if (is_null($this->getCodigoBanco())) {
            throw new \InvalidArgumentException("Código do Banco não informado", 1);
        }
        return $this;
    }

    private function verificaNumeroMoeda()
    {
        if (is_null($this->getNumeroMoeda())) {
            throw new \InvalidArgumentException("Número da Moeda não informado", 1);
        }
        return $this;
    }

    private function verificaValorBoleto()
    {
        if (is_null($this->getValorBoleto())) {
            throw new \InvalidArgumentException("Valor do Boleto não informado", 1);
        }
        return $this;
    }

    private function verificaCarteira()
    {
        if (is_null($this->getCarteira())) {
            throw new \InvalidArgumentException("Valor do Boleto não informado", 1);
        }
        return $this;
    }

    private function verificaNossoNumero()
    {
        if (is_null($this->getNossoNumero())) {
            throw new \InvalidArgumentException("Nosso Número não informado", 1);
        }
        return $this;
    }

    private function verificaAgencia()
    {
        if (is_null($this->getCedente()->getAgencia())) {
            throw new \InvalidArgumentException("Agência não informada", 1);
        }
        return $this;
    }

    private function verificaConta()
    {
        if (is_null($this->getCedente()->getConta())) {
            throw new \InvalidArgumentException("Conta não informada", 1);
        }
        return $this;
    }

    public function verificaCodigoBarras()
    {
        $this
            ->verificaCodigoBanco()
            ->verificaNumeroMoeda()
            ->verificaValorBoleto()
            ->verificaCarteira()
            ->verificaNossoNumero()
            ->verificaAgencia()
            ->verificaConta();

        return $this;
    }

    /**
     * Getter para o valor do boleto
     * @param boolean Se retornará formatado ou não
     * @param boolean Se retornará com símbolo da moeda
     */
    public function getValorBoleto($formatado = false, $symbol = false)
    {
        $valor = $this->valor_boleto + $this->taxa_emissao - ($this->desconto + $this->deducao) + $this->mora_juro + $this->acrescimo;
        if (!$formatado) {
            return $valor;
        } else {
            $numero = number_format($valor, 2, ",", ".");

            if ($symbol === true) {
                $numero = "R$ ".$numero;
            }
            return $numero;
        }
    }

    /**
     * Função responsável por formatar o valor do boleto
     */
    protected function formataValor($numero, $loop, $insert, $tipo = "geral")
    {
        if ($tipo == "geral") {
            $numero = str_replace(",", "", $numero);
            // $numero = str_replace(".","",$numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        } else if ($tipo == "valor") {
            /*
                retira as virgulas formata o numero preenche com zeros
            */
            $numero = str_replace(",", "", $numero);
            $numero = str_replace(".", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        } else if ($tipo == "convenio") {
            while (strlen($numero) < $loop) {
                $numero = $numero . $insert;
            }
        }
        return $numero;
    }

    /**
     * Função responsável gerar a agência para o código de barras
     */
    public function getAgenciaCodigoBoleto()
    {
        $agencia = $this->getCedente()->getAgencia();
        $conta   = $this->getCedente()->getConta();

        return $agencia . '/' . $conta . '-' . $this->geraModulo10($agencia . $conta, 9, 1);
    }

    public function getNossoNumeroBoleto()
    {
        if (empty($this->nosso_numero_boleto)) {
            $this->nosso_numero_boleto = $this->nosso_numero;
        }

        return $this->nosso_numero_boleto;
    }

    public function esquerda($entra, $comp)
    {
        return substr($entra, 0, $comp);
    }

    public function direita($entra, $comp)
    {
        return substr($entra, strlen($entra)-$comp, $comp);
    }

    /**
     * Função responsável por gerar a imagem do código de barras
     */
    public function generateBarCode()
    {
        $fino = 1 ;
        $largo = 3 ;
        $altura = 50 ;

        $barcodes[0] = "00110" ;
        $barcodes[1] = "10001" ;
        $barcodes[2] = "01001" ;
        $barcodes[3] = "11000" ;
        $barcodes[4] = "00101" ;
        $barcodes[5] = "10100" ;
        $barcodes[6] = "01100" ;
        $barcodes[7] = "00011" ;
        $barcodes[8] = "10010" ;
        $barcodes[9] = "01010" ;

        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2 ;
                $texto = "" ;
                for ($i = 1; $i < 6; $i++) {
                    $texto .=  substr($barcodes[$f1], ($i-1), 1) . substr($barcodes[$f2], ($i-1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }

        $imagem = '<img src="data:image/png;base64, '.$this->getImagemP().'" width="'.$fino.'" height="'.$altura.'" border="0">';
        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemB().'" width="'.$fino.'" height="'.$altura.'" border="0">';
        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemP().'" width="'.$fino.'" height="'.$altura.'" border="0">';
        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemB().'" width="'.$fino.'" height="'.$altura.'" border="0">';

        $texto = $this->getCodigoBarras44();
        if ((strlen($texto) % 2) <> 0) {
            $texto = "0" . $texto;
        }

        while (strlen($texto) > 0) {
            $i = round($this->esquerda($texto, 2));
            $texto = $this->direita($texto, strlen($texto)-2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i+=2) {
                if (substr($f, ($i-1), 1) == "0") {
                    $f1 = $fino ;
                } else {
                    $f1 = $largo ;
                }

                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino ;
                } else {
                    $f2 = $largo ;
                }

                $imagem .= '<img src="data:image/png;base64, '.$this->getImagemP().'" width="'.$f1.'" height="'.$altura.'" border="0">';
                $imagem .= '<img src="data:image/png;base64, '.$this->getImagemB().'" width="'.$f2.'" height="'.$altura.'" border="0">';
            }
        }

        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemP().'" width="'.$largo.'" height="'.$altura.'" border="0">';
        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemB().'" width="'.$fino.'" height="'.$altura.'" border="0">';
        $imagem .= '<img src="data:image/png;base64, '.$this->getImagemP().'" width="1" height="'.$altura.'" border="0">';

        return $imagem;
    }

    /**
     * Função abstrata para gerar Dígito Verificador do Código de barras, implementar em casa banco
     */
    abstract public function calculaDigitoVerificadorCodigoBarras();

    /**
     * Função abstrata para gerar Linha digitável, implementar em casa banco
     */
    abstract public function geraCodigoBarras();

    /**
     * Função abstrata para gerar Linha digitável, implementar em casa banco
     */
    abstract public function getLinhaDigitavel();
}
