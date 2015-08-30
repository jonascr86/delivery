<?php

namespace Delivery\Dao;
use Delivery\Model\Pessoa;

class PessoaDao {
    
    function salvarPessoa(Pessoa $pessoa) {
        $sql = "INSERT INTO cliente (bairro_id, cidade_id, nome, idade, "
           . "data_nascimento, cpf, rg, nome_mae, sexo, telefone, " 
           . "celular, email, endereco, usuario, senha) VALUES "
           . "(1,1,'NOME',1,'25/01/86','00000000000','1212121212', "
           . "'MAE','M','00000000', '00000000','email@mail.com.br', "
           . "'ENDERECO','usuario','senha');";
        
    }
}
