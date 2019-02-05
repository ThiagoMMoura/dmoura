<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="municipio")
 */
class Municipio{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="Estado")
     * @JoinColumn(name="uf", referencedColumnName="id")
     */
    protected $uf;

    /**
     * @Column (type="string", length=100)
     */
    protected $nome;

    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getUf() : Estado{
        return $this->uf;
    }

    public function setUf(Estado $uf){
        $this->uf = $uf;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }
}