<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="ref_comercial_cliente")
 */
class RefComercial{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=100)
     */
    protected $razao;

    /**
     * @Column (type="string", length=14)
     */
    protected $cnpj;

    /**
     * @Column (type="string", length=12)
     */
    protected $telefone1;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="operadora_id1", referencedColumnName="id")
     */
    protected $operadora1;
    
    /**
     * @Column (type="string", length=12)
     */
    protected $telefone2;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="operadora_id2", referencedColumnName="id")
     */
    protected $operadora2;
    
    /**
     * @Column (type="string", length=12)
     */
    protected $telefone3;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="operadora_id3", referencedColumnName="id")
     */
    protected $operadora3;
    
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
     * @Column (type="string", length=250)
     */
    protected $complemento;
    
    /**
     * @ManyToOne(targetEntity="PessoaJuridica")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getRazao(){
        return $this->razao;
    }

    public function setRazao($razao){
        $this->razao = $razao;
    }

    public function getCNPJ(){
        return $this->cnpj;
    }

    public function setCNPJ($cnpj){
        $this->cnpj = $cnpj;
    }
    
    public function getTelefone1(){
        return $this->telefone1;
    }

    public function setTelefone1($telefone){
        $this->telefone1 = $telefone;
    }

    public function getOperadora1(){
        return $this->operadora1;
    }

    public function setOperadora1(OperadoraTelefone $operadora){
        $this->operadora1 = $operadora;
    }
    
    public function getTelefone2(){
        return $this->telefone2;
    }

    public function setTelefone2($telefone){
        $this->telefone2 = $telefone;
    }

    public function getOperadora2(){
        return $this->operadora2;
    }

    public function setOperadora2(OperadoraTelefone $operadora){
        $this->operadora2 = $operadora;
    }
    
    public function getTelefone3(){
        return $this->telefone3;
    }

    public function setTelefone3($telefone){
        $this->telefone3 = $telefone;
    }

    public function getOperadora3(){
        return $this->operadora3;
    }

    public function setOperadora3(OperadoraTelefone $operadora){
        $this->operadora3 = $operadora;
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
    
    /**
     * Getter e Setter Pessoa
     * @return Entity\Pessoa
     */
    public function getPessoa(){
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa){
        $this->pessoa = $pessoa;
    }
}