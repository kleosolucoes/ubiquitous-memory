<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\ORM\RepositorioORM;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de crud
 */
class CadastroController extends KleoController {

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            parent::__construct($doctrineORMEntityManager);
        }
    }

    /**
     * Função padrão, traz a tela principal
     * GET /cadastro
     */
    public function indexAction() {

        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());

        $responsavel = new Responsavel();
//         $responsavel->setNome('leonardo pereira');
//         $responsavel->setTelefone(61998510703);
//         $responsavel->setEmail('falecomleonardopereira@gmail.com');
//         $responsavel->setEmpresa('Kleo soluções');
//         $responsavel->setCnpj(41698113000190);
//         $responsavel->setDataEHoraDeCriacao();

//         $objectManager->persist($responsavel);
//         $objectManager->flush();

//         die(var_dump($responsavel->getId())); // yes, I'm lazy
        return new ViewModel();
    }

}
