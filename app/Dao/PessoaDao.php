<?php

namespace Delivery\Dao;

use Delivery\Model\Pessoa;

class PessoaDao extends DAO {

    protected $pessoa;
    protected $tabela;
    public $erro = "";

    function __construct($tabela = null, Pessoa $pessoa = null) {
        parent::__construct();
        $this->pessoa = $pessoa;
        $this->tabela = $tabela;
    }

    public function editar($conds) {

        $data = array(
            'bairro' => $this->pessoa->getBairro(),
            'cidade_id' => $this->pessoa->getCidade_id(),
            'nome' => $this->pessoa->getNome(),
            'idade' => $this->pessoa->getIdade(),
            'data_nascimento' => $this->pessoa->getData_nascimento(),
            'cpf' => $this->pessoa->getCpf(),
            'rg' => $this->pessoa->getRg(),
            'nome_mae' => $this->pessoa->getNome_mae(),
            'sexo' => $this->pessoa->getSexo(),
            'telefone' => $this->pessoa->getTelefone(),
            'celular' => $this->pessoa->getCelular(),
            'email' => $this->pessoa->getEmail(),
            'endereco' => $this->pessoa->getEndereco(),
        );
        try {
            $this->database->update($this->tabela, $conds, $data);
            return true;
        } catch (\Simplon\Mysql\MysqlException $ex) {
            \Delivery\Utils\Utils::displayErrorMessage($ex->getMessage());
            return false;
        }
    }

    public function salvar() {

        $data = array(
            'bairro' => $this->pessoa->getBairro(),
            'cidade_id' => $this->pessoa->getCidade_id(),
            'nome' => $this->pessoa->getNome(),
            'idade' => $this->pessoa->getIdade(),
            'data_nascimento' => $this->pessoa->getData_nascimento(),
            'cpf' => $this->pessoa->getCpf(),
            'rg' => $this->pessoa->getRg(),
            'nome_mae' => $this->pessoa->getNome_mae(),
            'sexo' => $this->pessoa->getSexo(),
            'telefone' => $this->pessoa->getTelefone(),
            'celular' => $this->pessoa->getCelular(),
            'email' => $this->pessoa->getEmail(),
            'endereco' => $this->pessoa->getEndereco(),
        );

        return $this->database->insert($this->tabela, $data);
    }

    public function getErro() {
        return $this->erro;
    }

    public function obterPessoaPorEmail($where) {
        try {
            $sql = "SELECT * FROM pessoa "
                    . "WHERE email = :email;";
            
            $retorno = $this->database()->fetchRow($sql, array(':email' => $where['email']));
            if($retorno){
                $pessoa = new Pessoa();
                $pessoa->setId($retorno['id']);
                $pessoa->setNome($retorno['nome']);
                $pessoa->setIdade($retorno['idade']);
                $pessoa->setEndereco($retorno['endereco']);
                $pessoa->setSexo($retorno['sexo']);
                $pessoa->setData_nascimento($retorno['data_nascimento']);
                $pessoa->setCpf($retorno['cpf']);
                $pessoa->setRg($retorno['rg']);
                $pessoa->setEmail($retorno['email']);
                
                return $pessoa;
            }
            
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex;
        }
    }

    public function obterPessoaPorId($where) {
        try {
            $sql = "SELECT * FROM pessoa "
                    . "WHERE id = :id;";
            
            $retorno = $this->database()->fetchRow($sql, array(':id' => $where['id']));
            if($retorno){
                $pessoa = new Pessoa();
                $pessoa->setId($retorno['id']);
                $pessoa->setNome($retorno['nome']);
                $pessoa->setIdade($retorno['idade']);
                $pessoa->setEndereco($retorno['endereco']);
                $pessoa->setSexo($retorno['sexo']);
                $pessoa->setData_nascimento($retorno['data_nascimento']);
                $pessoa->setCpf($retorno['cpf']);
                $pessoa->setRg($retorno['rg']);
                $pessoa->setEmail($retorno['email']);
                
                return $pessoa;
            }
            
        } catch (\Simplon\Mysql\MysqlException $ex) {
            return $ex;
        }
    }
}
