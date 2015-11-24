<?php
namespace Boletos;

/**
 * Classe para o Cedente
 * @method string getAgencia()
 * @method string getConta()
 * @method string getEndereco()
 * @method string getLogo()
 * @method string getNome()
 * @method string getCpfCnpj()
 * @method string getCidadeUf()
 */
class Cedente extends BaseClass
{
    /**
     * @var string Nome do Sacado
     */
    protected $nome;

    /**
     * @var string Endereço do Sacado
     */
    protected $endereco;

    /**
     * @var string Path para o logo da empresa
     */
    protected $logo;

    /**
     * @var string Agência
     */
    protected $agencia;

    /**
     * @var string Conta
     */
    protected $conta;

    /**
     * @var string CPF - CNPJ
     */
    protected $cpf_cnpj;

    /**
     * @var string Cidade / UF
     */
    protected $cidade_uf;

    public function __construct()
    {
        $this->logo = base64_encode(fread(fopen(__DIR__ . '/templates/imagens/logoempresa.png', 'r'), filesize(__DIR__ . '/templates/imagens/logoempresa.png')));
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    public function setLogo($logo)
    {
        $this->logo = base64_encode(fread(fopen($logo, $logo)));
        return $this;
    }

    public function setCpfCnpj($documento)
    {
        $this->cpf_cnpj = $documento;
        return $this;
    }

    public function setCidadeUf($cidade_uf)
    {
        $this->cidade_uf = $cidade_uf;
        return $this;
    }
}
