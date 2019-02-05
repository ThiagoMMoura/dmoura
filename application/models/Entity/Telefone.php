<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="telefone")
 */
class Telefone{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=12)
     */
    protected $telefone;

    /**
     * @ManyToOne(targetEntity="TipoTelefone")
     * @JoinColumn(name="idtipo", referencedColumnName="id")
     */
    protected $tipo;

    /**
     * @ManyToOne(targetEntity="OperadoraTelefone")
     * @JoinColumn(name="idoperadora", referencedColumnName="id")
     */
    protected $operadora;

    /**
     * @ManyToOne(targetEntity="Pessoa")
     * @JoinColumn(name="idpessoa", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getTelefone(){
        return $this->telefone;
    }

    public function setTelefone($telefone){
        $this->telefone = $telefone;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo(TipoTelefone $tipo){
        $this->tipo = $tipo;
    }

    public function getOperadora(){
        return $this->operadora;
    }

    public function setOperadora(OperadoraTelefone $operadora){
        $this->operadora = $operadora;
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