<?php
namespace Entity;

/**
 * @Entity
 * @Table (name="alocado")
 */
class Alocado extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne (targetEntity="User", cascade={"all"}, fetch="LAZY")
     * @JoinColumn(name="iduser", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ManyToOne (targetEntity="Setor", fetch="LAZY")
     * @JoinColumn(name="idsetor", referencedColumnName="id")
     */
    protected $setor;

    public function __construct(User $user, Setor $setor) {
        $this->user = $user;
        $this->setor = $setor;
    }
    
    public function getId(){
        return $this->id;
    }

    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->titulo = $user;
    }

    public function getSetor(){
        return $this->setor;
    }

    public function setSetor($setor){
        $this->setor = $setor;
    }
}