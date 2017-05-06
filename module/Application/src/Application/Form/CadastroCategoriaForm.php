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

  public function __construct($name = null, $todasCategorias = null) {
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

    $inputSelectCategorias = new Select();
    $inputSelectCategorias->setName(self::inputCategoriaId);
    $inputSelectCategorias->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputCategoriaId,
    ));
    $inputSelectCategorias->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectCategorias);

    $this->setarCategorias($todasCategorias);

  }

  public function setarCategorias($categorias){
    $arrayCategoria = [];
    if($categorias){
      foreach($categorias as $categoria){
        $arrayCategoria[$categoria->getId()] = $categoria->getNome();  
      }
    }
    $inputCategorias = $this->get(self::inputCategoriaId);
    $inputCategorias->setValueOptions($arrayCategoria);
  }
}