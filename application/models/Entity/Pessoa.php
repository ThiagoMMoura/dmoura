<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table (name="pessoa")
 */
class Pessoa extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=100, nullable=false)
     */
    protected $nome;

    /**
     * @Column (type="string", length=100)
     */
    protected $apelido;

    /**
     * @Column (type="string", length=14, unique=true)
     */
    protected $ncp;
    
    /**
     * @Column (type="date")
     */
    protected $nascimento;
    
    /**
     * @OneToMany(targetEntity="Endereco", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $enderecos;
    
    /**
     * @OneToMany(targetEntity="Email", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $emails;
    
    /**
     * @OneToMany(targetEntity="Telefone", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $telefones;
    
    /**
     * @OneToMany(targetEntity="Site", mappedBy="pessoa", orphanRemoval=true)
     */
    protected $sites;
    
    /**
     * @Column(type="string", length=32, nullable=false)
     */
    protected $type;
    
    /**
     * @Column (type="boolean")
     */
    protected $ativo;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    public function __construct() {
        $this->enderecos = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->telefones = new ArrayCollection();
        $this->sites = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getApelido(){
        return $this->apelido;
    }

    public function setApelido($apelido){
        $this->apelido = $apelido;
    }

    public function getAtivo(){
        return $this->ativo;
    }

    public function isAtivo() : bool{
        return (bool) $this->getAtivo();
    }
    
    public function setAtivo($ativo){
        $this->ativo = $ativo;
    }
    
    public function getNcp(){
        return $this->ncp;
    }

    public function setNcp($ncp){
        $this->ncp = $ncp;
    }
    
    public function getNascimento(){
        return $this->nascimento;
    }

    public function setNascimento($nascimento){
        $this->nascimento = $nascimento;
    }
    
    /**
     * Getter Enderecos
     * 
     * @return array
     */
    public function getEnderecos(){
        return $this->enderecos->toArray();
    }

    public function addEndereco(Endereco $endereco){
        $this->enderecos->add($endereco);
    }
    
    public function removeElementEndereco(Endereco $endereco) {
        $this->enderecos->removeElement($endereco);
    }
    
    public function removeEndereco($id) {
        $this->enderecos->remove($id);
    }
    
    /**
     * Getter Emails
     * 
     * @return array
     */
    public function getEmails(){
        return $this->emails->toArray();
    }

    public function addEmail(Email $email){
        $this->emails->add($email);
    }
    
    public function removeElementEmail(Email $email) {
        $this->emails->removeElement($email);
    }
    
    public function removeEmail($id) {
        $this->emails->remove($id);
    }
    
    /**
     * Getter Telefones
     * 
     * @return array
     */
    public function getTelefones(){
        return $this->telefones->toArray();
    }

    public function addTelefone(Telefone $telefone){
        $this->telefones->add($telefone);
    }
    
    public function removeElementTelefone(Telefone $telefone) {
        $this->telefones->removeElement($telefone);
    }
    
    public function removeTelefone($id) {
        $this->telefones->remove($id);
    }
    
    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
    }

    /**
     * Getter Sites
     * 
     * @return array
     */
    public function getSites(){
        return $this->sites->toArray();
    }

    public function addSite(Site $site){
        $this->sites->add($site);
    }
    
    public function removeElementSite(Site $site) {
        $this->sites->removeElement($site);
    }
    
    public function removeSite($id) {
        $this->sites->remove($id);
    }
    
    /**
     * Getter e Setter User
     * @return Entity\User
     */
    public function getUser() : User{
        return $this->user;
    }

    public function setUser(User $user){
        $this->user = $user;
    }
}