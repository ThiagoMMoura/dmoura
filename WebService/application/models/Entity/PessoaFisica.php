<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="user")
 */
class PessoaFisica{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", name="cpf", length=11, unique=true)
     */
    protected $cpf;

    /**
     * @Column (type="date")
     */
    protected $nascimento;

    /**
     * @Column (type="string", name="sexo", length=50)
     */
    protected $genero;

    /**
     * @Column (type="string")
     */
    protected $nacionalidade;

    /**
     * @Column (type="string")
     */
    protected $naturalidade;

    /**
     * @Column (type="string", name="estado_civil")
     */
    protected $estadoCivil;

    /**
     * @Column (type="string")
     */
    protected $conjuge;

    /**
     * @Column (type="string")
     */
    protected $cnpj;

    /**
     * @Column (type="string")
     */
    protected $razao;

    /**
     * @Column (type="string")
     */
    protected $telefone1;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="idoperadora1", referencedColumnName="id")
     */
    protected $operadora1;

    /**
     * @Column (type="string")
     */
    protected $telefone2;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="idoperadora2", referencedColumnName="id")
     */
    protected $operadora2;

    /**
     * @Column (type="string")
     */
    protected $telefone3;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="idoperadora3", referencedColumnName="id")
     */
    protected $operadora3;

    /**
     * @Column (type="string")
     */
    protected $cargo;

    /**
     * @ManyToOne(targetEntity="Endereco")
     * @JoinColumn(name="cep", referencedColumnName="cep")
     */
    protected $cep;

    /**
     * @Column (type="string")
     */
    protected $numero;

    /**
     * @Column (type="string")
     */
    protected $complemento;

    /**
     * @ManyToOne(targetEntity="Pessoa")
     * @JoinColumn(name="idpessoa", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getCPF(){
        return $this->cpf;
    }

    public function setCPF($cpf){
        $this->cpf = $cpf;
    }

    public function getNascimento(){
        return $this->nascimento;
    }

    public function setNascimento($nascimento){
        $this->nascimento = $nascimento;
    }

    public function getGenero(){
        return $this->genero;
    }

    public function setGenero($genero){
        $this->genero = $genero;
    }

    public function getNacionalidade(){
        return $this->nacionalidade;
    }

    public function setNacionalidade($nacionalidade){
        $this->nacionalidade = $nacionalidade;
    }

    /**
     * Getter e Setter Naturalidade
     */
    public function getNaturalidade(){
        return $this->naturalidade;
    }

    public function setNaturalidade($naturalidade){
        $this->naturalidade = $naturalidade;
    }

    /**
     * Getter e Setter EstadoCivil
     */
    public function getEstadoCivil(){
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil){
        $this->estadoCivil = $estadoCivil;
    }

    /**
     * Getter e Setter Conjuge
     */
    public function getConjuge(){
        return $this->conjuge;
    }

    public function setConjuge($conjuge){
        $this->conjuge = $conjuge;
    }

    /**
     * Getter e Setter CNPJ
     */
    public function getCNPJ(){
        return $this->cnpj;
    }

    public function setCNPJ($cnpj){
        $this->cnpj = $cnpj;
    }

    /**
     * Getter e Setter Razao
     */
    public function getRazao(){
        return $this->razao;
    }

    public function setRazao($razao){
        $this->razao = $razao;
    }

    /**
     * Getter e Setter Telefone1
     */
    public function getTelefone1(){
        return $this->telefone1;
    }

    public function setTelefone1($telefone1){
        $this->telefone1 = $telefone1;
    }

    /**
     * Getter e Setter Operadora1
     * @return OperadoraTelefone
     */
    public function getOperadora1(){
        return $this->operadora1;
    }

    public function setOperadora1(OperadoraTelefone $operadora1){
        $this->operadora1 = $operadora1;
    }

    /**
     * Getter e Setter Telefone2
     */
    public function getTelefone2(){
        return $this->telefone2;
    }

    public function setTelefone2($telefone2){
        $this->telefone2 = $telefone2;
    }

    /**
     * Getter e Setter Operadora2
     * @return OperadoraTelefone
     */
    public function getOperadora2(){
        return $this->operadora2;
    }

    public function setOperadora2(OperadoraTelefone $operadora2){
        $this->operadora2 = $operadora2;
    }

    /**
     * Getter e Setter Telefone3
     */
    public function getTelefone3(){
        return $this->telefone3;
    }

    public function setTelefone3($telefone3){
        $this->telefone3 = $telefone3;
    }

    /**
     * Getter e Setter Operadora3
     * @return OperadoraTelefone
     */
    public function getOperadora3(){
        return $this->operadora3;
    }

    public function setOperadora3(OperadoraTelefone $operadora3){
        $this->operadora3 = $operadora3;
    }

    /**
     * Getter e Setter Cargo
     */
    public function getCargo(){
        return $this->cargo;
    }

    public function setCargo($cargo){
        $this->cargo = $cargo;
    }

    /**
     * Getter e Setter CEP
     * @return Cep
     */
    public function getCEP(){
        return $this->cep;
    }

    public function setCEP(Cep $cep){
        $this->cep = $cep;
    }

    /**
     * Getter e Setter Numero
     */
    public function getNumero(){
        return $this->numero;
    }

    public function setNumero($numero){
        $this->numero = $numero;
    }

    /**
     * Getter e Setter Complemento
     */
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