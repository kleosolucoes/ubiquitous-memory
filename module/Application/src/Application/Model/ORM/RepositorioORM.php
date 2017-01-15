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
    private $_empresaORM;
    private $_situacaoORM;
    private $_responsavelSituacaoORM;
    private $_empresaSituacaoORM;

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
     * Metodo public para obter a instancia do SituacaoORM
     * @return SituacaoORM
     */
    public function getSituacaoORM() {
        if (is_null($this->_situacaoORM)) {
            $this->_situacaoORM = new SituacaoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Situacao');
        }
        return $this->_situacaoORM;
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
     * Metodo public para obter a instancia do EmpresaSituacaoORM
     * @return KleoORM
     */
    public function getEmpresaSituacaoORM() {
        if (is_null($this->_empresaSituacaoORM)) {
            $this->_empresaSituacaoORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\EmpresaSituacao');
        }
        return $this->_empresaSituacaoORM;
    }

  
    /**
     * Metodo public para obter a instancia do EmpresaORM
     * @return EmpresaORM
     */
    public function getEmpresaORM() {
        if (is_null($this->_empresaORM)) {
            $this->_empresaORM = new EmpresaORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Empresa');
        }
        return $this->_empresaORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
