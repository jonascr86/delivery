<?php

namespace Delivery\Dao;

use Delivery\Model\Pessoa;

class PessoaDao extends DAO {

    protected $pessoa;
    protected $tabela;

    function __construct($tabela, Pessoa $pessoa) {
        parent::__construct();
        $this->pessoa = $pessoa;
        $this->tabela = $tabela;
    }

    public function editar() {
        
    }

    public function salvar() {

        $data = [
            'bairro_id' => $this->pessoa->getBairro_id(),
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
        ];

        return $this->database->insert($this->tabela, $data);
    }

    public function edit($conds) {

        $data = [
            'bairro_id' => $this->pessoa->getBairro_id(),
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
        ];
        try {
            return $this->database->update($this->tabela, $conds, $data);
        } catch (Exception $exc) {

            $this->redirect($this->UrlBuilder()->doAction('pessoa', ['successMsg' => $exc->getTraceAsString()]));
        }
    }

    function getEstados($where = null) {
        if ($where) {
            $sql = "SELECT id, sigla FROM estado "
                    . "WHERE id = estado_id ORDER BY sigla;";
            $estados = $this->database()->fetchRowMany($sql, ['estado_id' => $where]);
        } else {
            $sql = "SELECT id, sigla FROM estado "
                    . "ORDER BY sigla;";
            $estados = $this->database()->fetchRowMany($sql);
        }
        return $estados;
    }

    function getCidades($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome, estado_id FROM cidade "
                . "WHERE estado_id = :estado_id "
                . "ORDER BY nome;";
        $cidades = $this->database()->fetchRowMany($sql, ['estado_id' => $where]);
        return $cidades;
    }

    function getBairros($where) {
        if (!isset($where)) {
            return false;
        }
        $sql = "SELECT id, nome FROM bairro "
                . "WHERE cidade_id = :cidade_id "
                . "ORDER BY nome;";
        $bairros = $this->database()->fetchRowMany($sql, ['cidade_id' => $where]);
        return $bairros;
    }

}
