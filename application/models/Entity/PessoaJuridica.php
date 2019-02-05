<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table (name="pessoa_juridica")
 */
class PessoaJuridica{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=32, nullable=true)
     */
    protected $inscricao_estadual;

    /**
     * @OneToMany(targetEntity="RefComercial", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $ref_comercial;

    /**
     * @OneToMany(targetEntity="RefBancaria", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $ref_bancaria;

    /**
     * @OneToMany(targetEntity="AutorizadoCliente", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $autorizados;

    /**
     * @ManyToOne(targetEntity="Pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    private $pessoa;
    
    public function __construct() {
        $this->ref_comercial = new ArrayCollection();
        $this->ref_bancaria = new ArrayCollection();
        $this->autorizados = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function getInscricaoEstadual(){
        return $this->inscricao_estadual;
    }

    public function setInscricaoEstadual($inscricao_estadual){
        $this->inscricao_estadual = $inscricao_estadual;
    }

    /**
     * Getter e Setter RefComercial
     * @return Entity\RefComercial
     */
    public function getRefComercial() : RefComercial{
        return $this->ref_comercial->toArray();
    }

    public function addRefComercial(RefComercial $ref_comercial){
        $this->ref_comercial->add($ref_comercial);
    }
    
    public function removeElementRefComercial(RefComercial $ref_comercial) {
        $this->ref_comercial->removeElement($ref_comercial);
    }
    
    public function removeRefComercial($id) {
        $this->ref_comercial->remove($id);
    }

    /**
     * Getter e Setter RefBancaria
     * @return array
     */
    public function getRefBancaria(){
        return $this->ref_bancaria->toArray();
    }

    public function addRefBancaria(RefBancaria $ref_bancaria){
        $this->ref_bancaria->add($ref_bancaria);
    }
    
    public function removeElementRefBancaria(RefBancaria $ref_bancaria) {
        $this->ref_bancaria->removeElement($ref_bancaria);
    }
    
    public function removeRefBancaria($id) {
        $this->ref_bancaria->remove($id);
    }
    
    /**
     * Getter e Setter Autorizados
     * @return array
     */
    public function getAutorizados(){
        return $this->ref_comercial->toArray();
    }

    public function addAutorizado(AutorizadoCliente $ref_comercial){
        $this->autorizados->add($ref_comercial);
    }
    
    public function removeElementAutorizado(AutorizadoCliente $autorizado) {
        $this->autorizados->removeElement($autorizado);
    }
    
    public function removeAutorizado($id) {
        $this->autorizados->remove($id);
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