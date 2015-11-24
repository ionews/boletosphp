<?php
namespace boletos;

/**
 * Class básica para definir getter's e setter's
 * @author Diego Pires <diegocpires@gmail.com>
 */
class BaseClass
{
    /**
     * Metodo mágico para criar os getter's e setter's
     * @param String Nome do método chamado
     * @param Array Argumentos
     * @throws Exception Caso não exista o atributo
     * @author Diego Pires <diegocpires@gmail.com>
     */
    public function __call($nome, $argumentos)
    {
        if (substr($nome, 0, 3) == "get") {
            $nomePropriedade = substr($nome, 3);
            return $this->getMagico($nomePropriedade, $argumentos);
        }
        if (substr($nome, 0, 3) == "set") {
            $nomePropriedade = substr($nome, 3);
            return $this->setMagico($nomePropriedade, $argumentos);
        }
        throw new \Exception("Método $nome não existe", 1);
    }
    /**
     * Função para recuperar automaticamente atributos da classe
     * @param String Nome do atributo
     * @return void
     * @throws Exception Caso não exista o atributo
     * @author Diego Pires <diegocpires@gmail.com>
     */
    public function getMagico($nome, $argumentos = array())
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if (property_exists(self::nomeClasse(), $nomePropriedade)) {
            return $this->{$nomePropriedade};
        } else {
            throw new \Exception("Atributo ".$nomePropriedade." não existe", 1);
        }
    }
    /**
     * Função para setar automaticamente atributos da classe
     * @param String Nome da atributo
     * @param  Valor a ser setado
     * @return this
     * @throws Exception Caso não exista o atributo
     * @author Diego Pires <diegocpires@gmail.com>
     */
    public function setMagico($nome, $argumentos)
    {
        $nomePropriedade = self::fromCamelCase($nome);
        if (property_exists(self::nomeClasse(), $nomePropriedade)) {
            $this->{$nomePropriedade} = $argumentos[0];
        } else {
            throw new \Exception("Atributo ".$nomePropriedade." não existe", 1);
        }
        return $this;
    }
    /**
     * Função para retornar o nome da classe
     * @return String Nome da Classe
     * @author Diego Pires <diegocpires@gmail.com>
     */
    public static function nomeClasse()
    {
        return get_called_class();
    }
    
    /**
     * Função responsável por converter camel case em underline
     * @param  String Valor a ser convertido
     * @param  String Separador a ser utilizado
     * @return String Valor convertido
     * @author Diego Pires <diegocpires@gmail.com>
     */
    public static function fromCamelCase($valor, $separador = "_")
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $separador.'$0', $valor)));
    }
}
