<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Endereco_model
 *
 * @author Thiago Moura
 */
class Endereco_model extends MY_Model{
    public function __construct(){
        parent::__construct('endereco',array('cep','uf','municipio','bairro','logradouro','num_ini','num_fim','lado','complemento'));
    }
    
    public function consulta_cep($cep){
        $select['where']['cep'] = str_replace('-', '', $cep);
        $select['order_by'] = 'logradouro';
        $this->selecionar($select);
        return $this->registro();
    }
    
    public function salva_cep($dados){        
        $insert['cep'] = $dados['cep'];
        $insert['uf'] = $dados['uf'];
        
        if(array_key_exists('municipio', $dados)){ //Busca pelo código do municipio ou insere um novo
            $this->load->model('municipio_model');
            $select = array();
            $select['select'] = 'id';
            $select['where']['uf'] = $dados['uf'];
            $select['where']['nome'] = $dados['municipio'];
            if($this->municipio_model->selecionar($select) && $this->municipio_model->num_registros()==1){
                $insert['municipio'] = $this->municipio_model->campo('id');
            }else{
                $inm = array();
                $inm['uf'] = $dados['uf'];
                $inm['nome'] = $dados['municipio'];
                if($this->municipio_model->inserir($inm)){
                    $insert['municipio'] = $this->municipio_model->id_inserido();
                }
            }
        }
        if(array_key_exists('municipio', $insert) && array_key_exists('bairro', $dados)){ //Busca pelo código do bairro ou insere um novo
            $this->load->model('bairro_model');
            $select = array();
            $select['select'] = 'id';
            $select['where']['uf'] = $dados['uf'];
            $select['where']['municipio'] = $insert['municipio'];
            $select['where']['nome'] = $dados['bairro'];
            if($this->bairro_model->selecionar($select) && $this->bairro_model->num_registros()==1){
                $insert['bairro'] = $this->bairro_model->campo('id');
            }else{
                $inb['uf'] = $dados['uf'];
                $inb['municipio'] = $insert['municipio'];
                $inb['nome'] = $dados['bairro'];
                if($this->bairro_model->inserir($inb)){
                    $insert['bairro'] = $this->bairro_model->id_inserido();
                }
            }
        }
        if(array_key_exists('municipio', $insert) && array_key_exists('logradouro', $dados)){
            $this->load->model('logradouro_model');
            $select = array();
            $select['select'] = 'id';
            $select['where']['uf'] = $dados['uf'];
            $select['where']['municipio'] = $insert['municipio'];
            $select['where']['nome'] = $dados['logradouro'];
            if($this->logradouro_model->selecionar($select) && $this->logradouro_model->num_registros()==1){ //Busca pelo código do logradouro ou insere um novo
                $insert['logradouro'] = $this->logradouro_model->campo('id');
            }else{
                $inl['uf'] = $dados['uf'];
                $inl['municipio'] = $insert['municipio'];
                $inl['nome'] = $dados['logradouro'];
                if($this->logradouro_model->inserir($inl)){
                    $insert['logradouro'] = $this->logradouro_model->id_inserido();
                }
            }
        }
        if(array_key_exists('complemento', $dados)){
            $insert['complemento'] = $dados['complemento'];
        }
        return $this->inserir($insert);
    }
}
