<?php

namespace Application\Model\ORM;

use Application\Model\Entity\Situacao;
use Doctrine\ORM\EntityManager;
use Exception;

/**
 * Nome: SituacaoORM.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe com acesso doctrine
 */
class SituacaoORM extends KleoORM {
/**
     * Localizar todos os responsaveis
     * @return Situacao[]
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
