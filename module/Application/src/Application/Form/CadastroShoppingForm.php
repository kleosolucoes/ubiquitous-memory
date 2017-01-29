<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Select;

/**
 * Nome: CadastroShoppingForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de shopping  
 *              
 */
class CadastroShoppingForm extends KleoForm {

  public function __construct($name = null, $todosEstados = null) {
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

    $arrayEstados = [];
    if($todosEstados){
      foreach($todosEstados as $estado){
        $arrayEstados[$estado->getId()] = $estado->getNome();  
      }
    }
    $inputSelectEstados = new Select();
    $inputSelectEstados->setName(self::inputEstadoId);
    $inputSelectEstados->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputEstadoId,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectEstados->setValueOptions($arrayEstados);
    $inputSelectEstados->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectEstados);
  }

  public function setarEstados($estados){
    $arrayEstados = [];
    if($estados){
      foreach($estados as $estado){
        $arrayEstados[$estado->getId()] = $estado->getNome();  
      }
    }
    $inputEstados = $this->get(self::inputEstadoId);
    $inputEstados->setValueOptions($arrayEstados);
  }
}