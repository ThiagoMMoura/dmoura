<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="endereco")
 */
class Endereco extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="string",length=8)
     */
    protected $cep;

    /**
     * @ManyToOne(targetEntity="Estado")
     * @JoinColumn(name="uf", referencedColumnName="id")
     */
    protected $uf;

    /**
     * @ManyToOne(targetEntity="Municipio")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    protected $municipio;
    
    /**
     * @ManyToOne(targetEntity="Bairro")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    protected $bairro;
    
    /**
     * @ManyToOne(targetEntity="Logradouro")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    protected $logradouro;
    
    /**
     * @Column (type="integer")
     */
    protected $num_ini;
    
    /**
     * @Column (type="integer")
     */
    protected $num_fim;
    
    /**
     * @Column (type="boolean", nullable=true)
     */
    protected $lado;
    
    /**
     * @Column (type="string", length=150, nullable=true)
     */
    protected $complemento;

    public function __construct($id = NULL) {
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getUf() : Estado{
        return $this->uf;
    }

    public function setUf($uf){
        $this->uf = $uf;
    }

    public function getMunicipio() : Municipio{
        return $this->municipio;
    }

    public function setMunicipio($municipio){
        $this->municipio = $municipio;
    }
    
    public function getBairro() : Bairro{
        return $this->bairro;
    }

    public function setBairro($bairro){
        $this->bairro = $bairro;
    }
    
    public function getLogradouro() : Logradouro{
        return $this->logradouro;
    }

    public function setLogradouro($logradouro){
        $this->logradouro = $logradouro;
    }
    
    public function getNumInicio(){
        return $this->num_ini;
    }

    public function setNumInicio($num_ini){
        $this->num_ini = $num_ini;
    }
    
    public function getNumFim(){
        return $this->num_fim;
    }

    public function setNumFim($num_fim){
        $this->num_fim = $num_fim;
    }
    
    public function getLado(){
        return $this->lado;
    }

    public function setLado($lado){
        $this->lado = $lado;
    }
    
    public function getComplemento(){
        return $this->complemento;
    }

    public function setComplemento($complemento){
        $this->complemento = $complemento;
    }
}