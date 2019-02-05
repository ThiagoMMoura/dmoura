<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="estado")
 */
class Estado{
    
    /**
     * @Id
     * @Column (name="uf", type="string", length=2)
     */
    protected $id;

    /**
     * @Column (name="nome", type="string", length=50)
     */
    protected $name;

    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getUf(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
}