<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Nome: InputFormulario.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Classe helper view para input dos formulários
 */
class InputFormulario extends AbstractHelper {

    private $label;
    private $tamanhoGrid;
    private $input;

    public function __construct() {
        
    }

    public function __invoke($label, $input, $tamanhoGrid = null) {
        $this->setLabel($label);
        $this->setTamanhoGrid($tamanhoGrid);
        $this->setInput($input);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $tamanhoGrid = 12;
        if ($this->getTamanhoGrid()) {
            $tamanhoGrid = $this->getTamanhoGrid();
        }
        $html .= '<div class="form-group col-lg-' . $tamanhoGrid . '">';
        $html .= '<label for="">' . $this->getLabel() . '</label>';
        $html .= $this->view->formInput($this->getInput());
       $html .=  $this->view->formElementErrors()
                ->setMessageOpenFormat('<div><p class="text-danger"><small>')
                ->setMessageSeparatorString('</small></p><p class="text-danger"><small>')
                ->setMessageCloseString('<small></p></div>')
                ->render($this->getInput());
        $html .= '</div>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getInput() {
        return $this->input;
    }

    function setInput($input) {
        $this->input = $input;
    }

    function getTamanhoGrid() {
        return $this->tamanhoGrid;
    }

    function setTamanhoGrid($tamanhoGrid) {
        $this->tamanhoGrid = $tamanhoGrid;
    }

}
