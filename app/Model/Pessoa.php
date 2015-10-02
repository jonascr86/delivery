<?php
namespace Delivery\Model;

class Pessoa {

    private $id;
    private $bairro_id;
    private $cidade_id;
    private $nome;
    private $idade;
    private $data_nascimento;
    private $cpf;
    private $rg;
    private $nome_mae;
    private $sexo;
    
  

    private $telefone;
    private $celular;
    private $email;
    private $endereco;
    private $estado_id;
    
    function Pessoa() {
        
    }
    
//    function __construct($id, $bairro_id, $cidade_id, $nome, $idade, 
//            $data_nascimento, $cpf, $rg, $nome_mae, $sexo, $telefone, 
//            $celular, $email,$endereco, $usuario, $senha) {
//        $this->id = $id;
//        $this->cidade_id = $cidade_id;
//        $this->bairro_id = $bairro_id;
//        $this->nome = $nome;
//        $this->idade = $idade;
//        $this->data_nascimento = $data_nascimento;
//        $this->cpf = $cpf;
//        $this->rg = $rg;
//        $this->nome_mae = $nome_mae;
//        $this->sexo = $sexo;
//        $this->telefone = $telefone;
//        $this->celular = $celular;
//        $this->email = $email;
//        $this->endereco = $endereco;
//        $this->usuario = $usuario;
//        $this->senha = $senha;
//    }

      function getEstado_id() {
        return $this->estado_id;
    }

    function setEstado_id($estado_id) {
        $this->estado_id = $estado_id;
    }
    function getBairro_id() {
        return $this->bairro_id;
    }

    function getCidade_id() {
        return $this->cidade_id;
    }

    function setBairro_id($bairro_id) {
        $this->bairro_id = $bairro_id;
    }

    function setCidade_id($cidade_id) {
        $this->cidade_id = $cidade_id;
    }

        function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getIdade() {
        return $this->idade;
    }

    function getData_nascimento() {
        return $this->data_nascimento;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getRg() {
        return $this->rg;
    }

    function getNome_mae() {
        return $this->nome_mae;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getCelular() {
        return $this->celular;
    }

    function getEmail() {
        return $this->email;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setIdade($idade) {
        $this->idade = $idade;
    }

    function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setRg($rg) {
        $this->rg = $rg;
    }

    function setNome_mae($nome_mae) {
        $this->nome_mae = $nome_mae;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

}
