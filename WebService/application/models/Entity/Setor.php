<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table (name="setor")
 */
class Setor extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=50)
     */
    protected $titulo;

    /**
     * @Column (type="string", length=250)
     */
    protected $descricao;
    
    /**
     * @ManyToMany(targetEntity="User", mappedBy="alocado")
     */
    private $usersAlocados;

    public function __construct($id = NULL) {
        $this->id = $id;
        $this->usersAlocados = new ArrayCollection();
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getTitulo(){
        return $this->titulo;
    }

    public function setTitulo($titulo){
        $this->titulo = $titulo;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }
    
    public function getUsers() {
        return $this->usersAlocados;
    }
}