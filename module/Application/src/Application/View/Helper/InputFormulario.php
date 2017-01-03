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

    public function __construct() {
        
    }

    public function __invoke($label, $tamanhoGrid = null) {
        $this->setLabel($label);
        $this->setTamanhoGrid($tamanhoGrid);
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
        $html .= '<input type="text" class="form-control" id="">';
        $html .= '</div>';
        return $html;
    }

    function getLabel() {
        return $this->label;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function getTamanhoGrid() {
        return $this->tamanhoGrid;
    }

    function setTamanhoGrid($tamanhoGrid) {
        $this->tamanhoGrid = $tamanhoGrid;
    }

}
