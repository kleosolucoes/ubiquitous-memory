<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Responsavel;
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
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum registro");
        }
        return $entidades;
    }
}
