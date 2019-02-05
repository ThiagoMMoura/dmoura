<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="autorizado_cliente")
 */
class AutorizadoCliente{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=100)
     */
    protected $nome;

    /**
     * @Column (type="string", length=250, nullable=true)
     */
    protected $descricao;

    /**
     * @ManyToOne(targetEntity="Pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
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