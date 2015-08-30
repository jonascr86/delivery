<?php
namespace Delivery\Dao;
use Delivery\Model\Pessoa;

class PessoaDao extends DAO{
    
    protected $pessoa;
    protected $tabela;
    
    function __construct($tabela, Pessoa $pessoa) {
        parent::__construct();
        $this->pessoa = $pessoa;
        $this->tabela = $tabela;
    }

    public function apagar() {
        
    }

    public function editar() {
        
    }

    public function listar() {
        
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
                    'usuario' => $this->pessoa->getUsuario(),
                    'senha' => $this->pessoa->getSenha(),
                ];
        
        $this->database->insert($this->tabela, $data);
    }

}
