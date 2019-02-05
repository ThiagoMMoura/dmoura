<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="logradouro")
 */
class Logradouro{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="Municipio")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    protected $municipio;

    /**
     * @Column (type="string", length=150)
     */
    protected $nome;

    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getMunicipio() : Municipio{
        return $this->municipio;
    }

    public function setMunicipio(Municipio $municipio){
        $this->municipio = $municipio;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }
}