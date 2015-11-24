<?php
namespace Boletos;

/**
 * Classe para o Sacado
 * @method string getEndereco()
 * @method string getNome()
 * @method string getEnderecoComplemento()
 */
class Sacado extends BaseClass
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
     * @var string Complemento do endereço do Sacado
     */
    protected $endereco_complemento;

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

    public function setEnderecoComplemento($endereco_complemento)
    {
        $this->endereco_complemento = $endereco_complemento;
        return $this;
    }
}
