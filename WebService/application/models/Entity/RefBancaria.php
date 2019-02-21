<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="ref_bancaria_cliente")
 */
class RefBancaria{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=20)
     */
    protected $agencia;

    /**
     * @Column (type="string", length=20)
     */
    protected $conta;

    /**
     * @ManyToOne(targetEntity="Banco")
     * @JoinColumn(name="banco_id", referencedColumnName="id")
     */
    protected $banco;
    
    /**
     * @ManyToOne(targetEntity="PessoaJuridica")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getAgencia(){
        return $this->agencia;
    }

    public function setAgencia($agencia){
        $this->agencia = $agencia;
    }

    public function getConta(){
        return $this->conta;
    }

    public function setConta($conta){
        $this->conta = $conta;
    }
    
    /**
     * Getter e Setter Banco
     * @return Entity\Banco
     */
    public function getBanco(){
        return $this->banco;
    }

    public function setBanco(Banco $banco){
        $this->banco = $banco;
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