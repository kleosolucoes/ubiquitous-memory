<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Shopping;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: ShoppingORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine
 */
class ShoppingORM extends KleoORM {
/**
     * Localizar todos os shoppings
     * @return Shopping[]
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
