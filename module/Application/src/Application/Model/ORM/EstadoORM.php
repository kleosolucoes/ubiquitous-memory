<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Estado;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: EstadoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine
 */
class EstadoORM extends KleoORM {
/**
     * Localizar todos os estados
     * @return Estado[]
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
