<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Empresa;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: EmpresaORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine
 */
class EmpresaORM extends KleoORM {
/**
     * Localizar todas as empresas
     * @return Empresa[]
     * @throws Exception
     */
    public function encontrarTodas() {
        $entidades = $this->getEntityManager()->getRepository($this->getEntity())->findAll();
        if (!$entidades) {
            throw new Exception("Não foi encontrado nenhum registro");
        }
        return $entidades;
    }
}
