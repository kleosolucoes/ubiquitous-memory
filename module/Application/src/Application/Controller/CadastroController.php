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
        return new ViewModel();
    }
  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavel
     */
    public function responsavelAction() {
        return new ViewModel();
    }
  
  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizado
     */
    public function responsavelFinalizadoAction() {
        return new ViewModel();
    }
  
  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsaveis
     */
    public function responsaveisAction() {
      $this->setLayoutAdm();
      $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
      $responsaveis = $repositorioORM->getResponsavelORM()->encontrarTodos();
      $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodas();
        return new ViewModel(
        array(
         'responsaveis' => $responsaveis,
         'situacoes' => $situacoes,
        )
        );
    }
  
   /**
     * Seta o layout da administracao
     */
    private function setLayoutAdm() {
        $this->layout('layout/adm');
    }

}
