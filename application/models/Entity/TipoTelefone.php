<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="tipo_telefone")
 */
class TipoTelefone extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (name="tipo", type="string", length=50)
     */
    protected $tipo;

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->tipo;
    }

    public function setName($name){
        $this->tipo = $name;
    }
    
    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
}
