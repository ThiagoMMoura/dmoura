<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="banco")
 */
class Banco{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=150)
     */
    protected $nome;

    /**
     * @Column (type="string", length=6)
     */
    protected $codigo;

    /**
     * @Column (type="string", length=150, nullable=true)
     */
    protected $site;

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }
    
    public function getSite(){
        return $this->site;
    }

    public function setSite($site){
        $this->site = $site;
    }

}