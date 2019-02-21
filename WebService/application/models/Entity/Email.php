<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="email_contato")
 */
class Email{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=150)
     */
    protected $email;

    /**
     * @Column (type="string", length=50, nullable=true)
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

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
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