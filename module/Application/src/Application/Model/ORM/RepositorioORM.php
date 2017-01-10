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
            $this->_responsavelORM = new KleoORM($this->getDoctrineORMEntityManager(), 'Application\Model\Entity\Responsavel');
        }
        return $this->_responsavelORM;
    }

    /**
     * Metodo public para obter a instancia EntityManager com acesso ao banco de dados
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

}
