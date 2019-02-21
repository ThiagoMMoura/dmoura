<?php
namespace Entity;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity (repositoryClass="Entity\OperadoraTelefoneRepository")
 * @Table (name="operadora_telefone")
 */
class OperadoraTelefone extends MetaInfo{
    
    /**
     * @Id
     * @Column (type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column (name="operadora", type="string", length=50)
     */
    protected $operadora;

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->operadora;
    }

    public function setName($operadora){
        $this->operadora = $operadora;
    }

    public function getOperadora(){
        return $this->operadora;
    }

    public function setOperadora($operadora){
        $this->operadora = $operadora;
    }
    
}

class OperadoraTelefoneRepository extends EntityRepository
{
    
    public function hasOperadora($operadora,$excludeId = NULL){
        if ($excludeId) {
            return $this->_em->createQuery('SELECT COUNT(o.id) FROM Entity\OperadoraTelefone o WHERE o.operadora = ?1 AND o.id NOT IN (?2)')
                ->setParameter(1, $operadora)
                ->setParameter(2, $excludeId)
                ->getSingleScalarResult() > 0;
        }
        
        return $this->_em->getRepository('Entity\OperadoraTelefone')->count(['operadora'=>$operadora]) > 0;
    }
    
}
