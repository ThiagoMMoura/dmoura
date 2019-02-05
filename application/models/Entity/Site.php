<?php
namespace Entity;


/**
 * @Entity
 * @Table (name="site_contato")
 */
class Site{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=150)
     */
    protected $link;

    /**
     * @Column (type="string", length=50)
     */
    protected $tipo;

    /**
     * @ManyToOne(targetEntity="Pessoa")
     * @JoinColumn(name="idpessoa", referencedColumnName="id")
     */
    private $pessoa;

    public function getId(){
        return $this->id;
    }

    public function getLink(){
        return $this->link;
    }

    public function setLink($link){
        $this->link = $link;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setTipo($tipo){
        $this->tipo = $tipo;
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