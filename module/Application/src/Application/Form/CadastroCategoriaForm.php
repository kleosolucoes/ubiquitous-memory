<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Select;

/**
 * Nome: CadastroCategoriaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de categoria  
 *              
 */
class CadastroCategoriaForm extends KleoForm {

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