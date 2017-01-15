<?php

namespace Application\Form;

use Zend\Form\Element\Text;

/**
 * Nome: CadastroShoppingForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de shopping  
 *              
 */
class CadastroShoppingForm extends KleoForm {

    public function __construct($name = null) {
        parent::__construct($name);
        
         $this->add(
                (new Text())
                        ->setName(self::inputNome)
                        ->setAttributes([
                            self::stringClass => self::stringClassFormControl,
                            self::stringId => self::inputNome,
                            self::stringRequired => self::stringRequired,
                         ])
        );
    }
}