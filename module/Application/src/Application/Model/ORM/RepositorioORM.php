<?php

namespace Application\Model\ORM;

use Doctrine\ORM\EntityManager;

/**
 * Nome: RepositorioORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso ao repositorio ORM
 */
class RepositorioORM {

    private $_doctrineORMEntityManager;
    private $_responsavelORM;
    private $_lojaORM;
    private $_situacaoORM;
    private $_estadoORM;
    private $_responsavelSituacaoORM;
    private $_lojaSituacaoORM;
    private $_shoppingORM;

    /**
     * Contrutor
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {
        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    /**
     * Metodo public para obter a instancia do ResponsavelORM
     * @return ResponsavelORM
     */
    public function getResponsavelORM() {
        if (is_null($this->_responsavelORM)) {
            $this->_responsavelORM = new ResponsavelORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Responsavel');
        }
        return $this->_responsavelORM;
    }
  
    /**
     * Metodo public para obter a instancia do KleoORM
     * @return KleoORM
     */
    public function getSituacaoORM() {
        if (is_null($this->_situacaoORM)) {
            $this->_situacaoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Situacao');
        }
        return $this->_situacaoORM;
    }  
  
    /**
     * Metodo public para obter a instancia do KleoORM
     * @return KleoORM
     */
    public function getEstadoORM() {
        if (is_null($this->_estadoORM)) {
            $this->_estadoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Estado');
        }
        return $this->_estadoORM;
    }  
  
    /**
     * Metodo public para obter a instancia do ResponsavelSituacaoORM
     * @return KleoORM
     */
    public function getResponsavelSituacaoORM() {
        if (is_null($this->_responsavelSituacaoORM)) {
            $this->_responsavelSituacaoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\ResponsavelSituacao');
        }
        return $this->_responsavelSituacaoORM;
    }
  
    /**
     * Metodo public para obter a instancia do KleoORM
     * @return KleoORM
     */
    public function getLojaSituacaoORM() {
        if (is_null($this->_lojaSituacaoORM)) {
            $this->_lojaSituacaoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\LojaSituacao');
        }
        return $this->_lojaSituacaoORM;
    }

  
    /**
     * Metodo public para obter a instancia do LojaORM
     * @return LojaORM
     */
    public function getLojaORM() {
        if (is_null($this->_lojaORM)) {
            $this->_lojaORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Loja');
        }
        return $this->_lojaORM;
    }

    /**
     * Metodo public para obter a instancia do KleoORM
     * @return KleoORM
     */
    public function getShoppingORM() {
        if (is_null($this->_shoppingORM)) {
            $this->_shoppingORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Shopping');
        }
        return $this->_shoppingORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
