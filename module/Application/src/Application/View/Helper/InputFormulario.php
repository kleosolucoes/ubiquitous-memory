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

    public function __construct() {
        
    }

    public function __invoke($label) {
        $this->setLabel($label);
        return $this->renderHtml();
    }

    public function renderHtml() {
        $html = '';
        $html .= '<div class="form-group">';
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

}
