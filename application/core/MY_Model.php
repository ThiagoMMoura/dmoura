<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of MY_Model
 *
 * @author Thiago Moura
 */
class MY_Model extends CI_Model{
    protected $nome_tabela;
    protected $nome_colunas_tabela;
    private $_query;
    private $_registros;
    private $_erros;
    private $_ultimo_sql = NULL;
    private $_id_inserida;
    private $_registro_atual;
    private $_iterator;
    
    public function __construct($tabela,$colunas = array()){
        parent::__construct();
        $this->nome_tabela = $tabela;
        $this->nome_colunas_tabela = $colunas;
        $this->_inicializar();
    }
    
    /**
     * Função que inicializa os campos privados da classe.
     */
    private function _inicializar(){
        $this->_erros = NULL;
        $this->_query = NULL;
        $this->_registros = array();
        $this->_id_inserida = NULL;
        $this->_registro_atual = 0;
        $this->_iterator = FALSE;
    }

    private function _set_ultimo_sql($sql = NULL){
        if($sql!=NULL){
            $this->_ultimo_sql = $sql;
        }else{
            $this->_ultimo_sql = preg_replace('/\s/', ' ',$this->db->last_query());
        }
    }
    
    public function obter_ultimo_sql(){
        return $this->_ultimo_sql;
    }
    
    public function obter_nome_colunas(){
        return $this->nome_colunas_tabela;
    }
    
    public function obter_nome_tabela(){
        return $this->nome_tabela;
    }
    
    /**
     * 
     * @return CI_DB_result
     */
    public function get_query() {
        return $this->_query;
    }

    public function set_query(CI_DB_result $query) {
        $this->_query = $query;
        $this->_set_ultimo_sql();
        
        if(!$this->_query){
            $this->_erros = $this->db->error();
            return FALSE;
        }
        $this->_registros = $query->result_array();
        return TRUE;
    }
    
    /**
     * Retorna o número de registros resultados do SELECT.
     * 
     * @return int Retorna <b>0</b> caso nenhum select efetuado ou nenhum
     * registro retornado.
     */
    public function num_registros(){
        return $this->_query!=NULL?$this->_query->num_rows():0;
    }
    
    /**
     * Retorna um array com todos os registros resultados do SELECT.
     * 
     * @return array
     */
    public function registros(){
        return $this->_registros;
    }
    
    /**
     * Retorna um registro pelo <code>indice</code> indicado ou o registro atual
     * da <code>iteração</code> caso ainda existam registros.
     * 
     * @param int $indice
     * @return mixed Retorna um array indexado pelas colunas do registro, ou 
     * retorna <code>FALSE</code> caso não haja nenhum registro.
     */
    public function registro($indice = NULL){
        if($indice===NULL){
            if($this->num_registros() > 0 && $this->_registro_atual < $this->num_registros()){
                return $this->_registros[$this->_registro_atual];
            }
        }else{
            if($this->num_registros()>0 && array_key_exists($indice, $this->_registros)){
                return $this->_registros[$indice];
            }
        }
        return FALSE;
    }
    
    /**
     * A função <code>possui_proximo()</code> retorna <code><b>TRUE</b></code> se
     * houver um próximo registro.
     * @return boolean
     */
    public function possui_proximo(){
        if($this->_iterator){
            $this->_registro_atual++;
            if($this->_registro_atual < $this->num_registros()){
                return TRUE;
            }
        }else{
            if($this->num_registros() > 0){
                $this->_iterator = TRUE;
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Busca campo do registro atual.
     * 
     * @param string $nome campo do registro as ser retornado.
     * @return mixed Caso houver algum registro e houver um campo com o nome
     * informado por parametro, o campo deste registro será retornado, senão,
     * retorna <code>FALSE</code>.
     */
    public function campo($nome){
        $registro = $this->registro();
        if($registro){
            if(array_key_exists($nome, $registro)){
                return $registro[$nome];
            }
        }
        return FALSE;
    }
    
    /**
     * Id do ultimo registro inserido.
     * 
     * @return mixed Caso não houver nenhum registro inserido na sessão atual,
     * a função retorna <code>FALSE</code>.
     */
    public function id_inserido(){
        if($this->_id_inserida!=NULL){
            return $this->_id_inserida;
        }
        return FALSE;
    }
    
    /**
     * Método que efetua select no banco string SQL ou array de comandos.
     * 
     * @param mixed $mixed Array com comandos SQL para formar o SQL de consulta,
     * ou string SQL para executar. Comandos disponíveis: 
     *  <code>select</code> - Colunas da tabela para o select;
     *  <code>where</code> - Condições de seleção de dados;
     *  <code>order_by</code> - Colunas de ordenação;
     *  <code>group_by</code> - Colunas de agrupamento;
     *  <code>having</code> - Clausula HAVING;
     * @return bool
     */
    public function selecionar($mixed = array()){
        $this->_inicializar();
        
        if(is_array($mixed)){
            if(array_key_exists('select', $mixed)){
                $this->db->select($mixed['select']);
            }else{
                $this->db->select('*');
            }
            if(array_key_exists('where', $mixed)){
                $this->db->where($mixed['where']);
            }
            if(array_key_exists('order_by', $mixed)){
                $this->db->order_by($mixed['order_by']);
            }
            if(array_key_exists('group_by', $mixed)){
                $this->db->group_by($mixed['group_by']);
            }
            if(array_key_exists('having', $mixed)){
                $this->db->having($mixed['having']);
            }
            if(array_key_exists('like', $mixed)){
                $this->db->like($mixed['like']);
            }
            if(array_key_exists('join', $mixed)){
                if(!is_array($mixed['join'][0])){
                    $mixed['join'] = array($mixed['join']);
                }
                foreach($mixed['join'] as $join){
                    if(count($mixed['join'])==3){
                        $this->db->join($join[0],$join[1],$join[2]);
                    }else{
                        $this->db->join($join[0],$join[1]);
                    }
                }
            }
            return $this->set_query($this->db->get($this->nome_tabela));
        }else{
            return $this->set_query($this->db->query($mixed));
        }
    }
    
    public function obter_registro_por_id($id){
        if($id>0){
            $this->selecionar(array('where' => 'id = ' . $id));
            
            if($this->num_registros()==1){
                return $this->registro();
            }
        }
        return FALSE;
    }
    
    /**
     * Função para inserir registros no banco de dados.
     * 
     * @param array $data Array definido por nome dos campos e seus valores respectivos.
     * @return boolean Retorna <code>TRUE</code> em caso de sucesso e <code>FALSE</code> em
     * caso de falha.
     */
    public function inserir($data){
        $this->_inicializar();
        $valores = array();
        if(is_array($data)){
            foreach($this->nome_colunas_tabela as $key){
                if(array_key_exists($key, $data)){
                    $valores[$key] = $data[$key];
                }
            }
            if($this->db->insert($this->nome_tabela,$valores)){
                $this->_id_inserida = $this->db->insert_id();
                $this->_ultimo_sql = $this->db->last_query();
                return TRUE;
            }
            $this->_erros = $this->db->error();
        }
        return FALSE;
    }
    
    /**
     * Função para inserir um lote de registros no banco de dados.
     * 
     * @param array $data Array definido por array com nome dos campos e seus valores respectivos.
     * @return boolean Retorna <code>TRUE</code> em caso de sucesso e <code>FALSE</code> em
     * caso de falha.
     */
    public function inserir_lote($data){
        $this->_inicializar();
        $valores = [];
        if(is_array($data)){
            foreach($data as $k => $d){
                $valores[$k] = [];
                foreach($this->nome_colunas_tabela as $key){
                    if(array_key_exists($key, $d)){
                        $valores[$k][$key] = $d[$key];
                    }
                }
            }
            if($this->db->insert_batch($this->nome_tabela,$valores)){
                $this->_id_inserida = $this->db->insert_id();
                $this->_ultimo_sql = $this->db->last_query();
                return TRUE;
            }
            $this->_erros = $this->db->error();
        }
        return FALSE;
    }
    
    public function obter_registro_inserido(){
        return $this->obter_registro_por_id($this->id_inserido());
    }
    
    /**
     * Realiza UPDATE dos registros passados por parametro no banco de dados.
     * 
     * @param array $data Array de dados a serem inseridos.
     * @param mixed $where Clausula WHERE do camando UPDATE para determinar o 
     * registro a ser alterado.
     * @return boolean Retorna <code>TRUE</code> em caso de sucesso, <code>FALSE</code> em caso
     * de falha.
     */
    public function alterar($data,$where = ''){
        $this->_inicializar();
        $valores = array();
        if(is_array($data)){
            foreach($this->nome_colunas_tabela as $key){
                if(array_key_exists($key, $data)){
                    $valores[$key] = $data[$key];
                    if($where==NULL){
                        $where = $valores[$key];
                    }
                }
            }
            if($this->db->update($this->nome_tabela,$valores,$where)){
                $this->_ultimo_sql = $this->db->last_query();
                return TRUE;
            }
            $this->_erros = $this->db->error();
        }
        return FALSE;
    }
    
    /**
     * Deleta com a ID passada por parametro.
     * 
     * @param int $id ID do registro a ser deletado.
     * @return boolean retorna <code>TRUE</code> se houve sucesso ou <code>FALSE</code> se caso falhou.
     */
    public function deletar($id){
        if($id!=NULL){
            if(is_array($id)){
                $this->db->where_in('id',$id);
            }else{
                $this->db->where('id', $id);
            }
            return $this->db->delete($this->nome_tabela);
        }
        return FALSE;
    }
}
