<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdmController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }

    public function empresaCadastroAction() {
        return new ViewModel();
    }

    public function empresaCadastradoAction() {
        return new ViewModel();
    }

    public function empresasAction() {
        $this->setLayoutAdm();
        return new ViewModel();
    }

    public function shoppingCadastroAction() {
        $this->setLayoutAdm();
        return new ViewModel();
    }

    public function shoppingsAction() {
        $this->setLayoutAdm();
        return new ViewModel();
    }

    /**
     * Seta o layout da administracao
     */
    private function setLayoutAdm() {
        $this->layout('layout/adm');
    }

}
