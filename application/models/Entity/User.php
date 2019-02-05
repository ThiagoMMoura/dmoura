<?php
namespace Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity (repositoryClass="Entity\UserRepository")
 * @Table (name="user")
 */
class User extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (type="string", length=100, unique=true)
     */
    protected $alias;

    /**
     * @Column (type="string", length=150)
     */
    protected $email;

    /**
     * @Column (type="string", length=150)
     */
    private $senha;

    /**
     * @Column (type="integer")
     */
    protected $nivel;
    
    /**
     * @ManyToMany(targetEntity="Setor", inversedBy="usersAlocados")
     * @JoinTable(name="user_alocado_setor")
     */
    protected $alocado;
    
    public function __construct() {
        $this->alocado = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->alias;
    }

    public function setName($alias){
        $this->alias = $alias;
    }

    public function getAlias(){
        return $this->getName();
    }

    public function setAlias($alias){
        $this->setName($alias);
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function setSenha($senha){
        $this->senha = $senha;
    }

    public function getNivel(){
        return $this->nivel;
    }

    public function setNivel($nivel){
        $this->nivel = $nivel;
    }
    
    /**
     * Apelido para getSetores
     * 
     * @return array
     */
    public function getAlocado(){
        return $this->getSetores();
    }
    
    public function getSetores() {
        return $this->alocado->toArray();
    }
    
    public function removeElementSetor(Setor $setor) {
        $this->alocado->removeElement($setor);
    }
    
    public function removeSetor($id) {
        $this->alocado->remove($id);
    }
    
    public function alocar(Setor $setor){
        $this->addSetor($setor);
    }
    
    public function addSetor(Setor $setor){
        $this->alocado->add($setor);
    }
    
    public function isAlocadoIn($id) {
        foreach ($this->getSetores() as $setor) {
            if ($setor->getId() == $id) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
}

class UserRepository extends EntityRepository
{
    public function getAllUsers()
    {
        return $this->_em->createQuery('SELECT u.id,u.alias,u.email,u.nivel FROM Entity\User u')
                         ->getResult();
    }
    
    public function hasEmail($email,$excludeId = NULL){
        if ($excludeId) {
            return $this->_em->createQuery('SELECT COUNT(u.id) FROM Entity\User u WHERE u.email = ?1 AND u.id NOT IN (?2)')
                ->setParameter(1, $email)
                ->setParameter(2, $excludeId)
                ->getSingleScalarResult() > 0;
        }
        
        return $this->_em->getRepository('Entity\User')->count(['email'=>$email]) > 0;
    }
    
    public function hasAlias($alias,$excludeId = NULL){
        if ($excludeId) {
            return $this->_em->createQuery('SELECT COUNT(u.id) FROM Entity\User u WHERE u.alias = ?1 AND u.id NOT IN (?2)')
                ->setParameter(1, $alias)
                ->setParameter(2, $excludeId)
                ->getSingleScalarResult() > 0;
        }
        
        return $this->_em->getRepository('Entity\User')->count(['alias'=>$alias]) > 0;
    }
}