<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Responsavel;
use Application\Controller\KleoController;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: ResponsavelORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine
 */
class ResponsavelORM extends KleoORM {
/**
     * Localizar todos os responsaveis
     * @return Responsavel[]
     * @throws Exception
     */
    public function encontrarTodos() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        return $entidades;
    }
  
  /**
     * Localizar responsavel por token
     * @param String $token
     * @return Responsavel
     * @throws Exception
     */
    public function encontrarPorToken($token) {
      $entidade = null;
        try {
            $entidade = $this->getEntityManager()
                    ->getRepository($this->getEntity())
                    ->findOneBy(array(KleoController::stringToken => $token));
            return $entidade;
        } catch (Exception $exc) {
            echo $exc->getMessages();
        }
    }
}
