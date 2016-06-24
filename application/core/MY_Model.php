<?php

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
    //private $_registro;
    private $_erros;
    private $_ultimo_sql = NULL;
    private $_id_inserida;
    
    public function __construct(){
        parent::__construct();
        $this->_inicializar();
    }
    
    private function _inicializar(){
        $this->_erros = NULL;
        $this->_query = NULL;
        $this->resultados = array();
        $this->resultado = array();
        $this->_id_inserida = NULL;
    }

    private function _set_ultimo_sql($sql = NULL){
        if($sql!=NULL){
            $this->_ultimo_sql = $sql;
        }else{
            $this->_ultimo_sql = preg_replace('/\s/', ' ',$this->db->last_query());
        }
    }
    
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
    
    public function num_registros(){
        return $this->query!=NULL?$this->query->num_rows():0;
    }
    
    public function registros(){
        return $this->_registros;
    }
    
    public function registro($indice = 0){
        if(count($this->registros()>0) && array_key_exists($indice, $this->_registros)){
            return $this->_registros[$indice];
        }
        return FALSE;
    }
    
    public function id_inserido(){
        if($this->_id_inserida!=NULL){
            return $this->_id_inserida;
        }
        return FALSE;
    }
    
    public function obter_numero_resultados(){
        return $this->query!=NULL?$this->query->num_rows():0;
    }
    
    /**
     * Método que efetua select no banco string SQL ou array de comandos.
     * 
     * @param mixed $mixed - Array com comandos SQL para formar o SQL de consulta,
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
            return $this->setQuery($this->db->get($this->nome_tabela));
        }else{
            return $this->setQuery($this->db->query($mixed));
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
                $this->_ultimo_sql();
                return TRUE;
            }
            $this->_erros = $this->db->error();
        }
        return FALSE;
    }
    
    public function obter_registro_inserido(){
        return $this->obter_registro_por_id($this->id_inserido());
    }
    
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
                $this->_ultimo_sql();
                return TRUE;
            }
            $this->_erros = $this->db->error();
        }
        return FALSE;
    }
    
    public function deletar($id){
        if($id!=NULL){
            $this->db->where('id', $id);
            return $this->db->delete($this->dbtable);
        }
        return FALSE;
    }
}
