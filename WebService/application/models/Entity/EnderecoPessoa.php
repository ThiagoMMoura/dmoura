<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="endereco_pessoa")
 */
class EnderecoPessoa extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     */
    protected $id;
    
    /**
     * @Column (type="string", length=100)
     */
    protected $destinatario;
    
    /**
     * @Column (type="string", length=50)
     */
    protected $tipo;
    
    /**
     * @ManyToOne(targetEntity="Endereco")
     * @JoinColumn(name="cep", referencedColumnName="cep")
     */
    protected $cep;

    /**
     * @Column (type="string", length=16)
     */
    protected $numero;

    /**
     * @Column (type="string", length=250, nullable=true)
     */
    protected $complemento;
    
    /**
     * @Column (type="string", length=100, nullable=true)
     */
    protected $referencia;
    
    /**
     * @Column (type="boolean")
     */
    protected $principal;
    
    /**
     * @ManyToOne (targetEntity="Pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getDestinatario(){
        return $this->destinatario;
    }

    public function setDestinatario($destinatario){
        $this->destinatario = $destinatario;
    }
    
    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }
    
    public function getEnderecoPostal() : Endereco{
        return $this->cep;
    }
    
    public function setEnderecoPostal(Endereco $cep) {
        $this->cep = $cep;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setNumero($numero){
        $this->numero = $numero;
    }

    public function getComplemento(){
        return $this->complemento;
    }

    public function setComplemento($complemento){
        $this->complemento = $complemento;
    }
    
    public function getReferencia(){
        return $this->referencia;
    }

    public function setReferencia($referencia){
        $this->referencia = $referencia;
    }
    
    public function getPrincipal(){
        return $this->principal;
    }

    public function isPrincipal() : bool{
        return (bool) $this->principal;
    }
    
    public function setPrincipal($principal){
        $this->principal = $principal;
    }
    
    public function getPessoa() : Pessoa{
        return $this->pessoa;
    }
    
    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }
}