<?php

namespace Application\Form;

use Zend\Form\Element\Select;
use Zend\Form\Element\Number;
use Zend\Form\Element\Tel;

/**
 * Nome: CadastroLojaForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de loja  
 *              
 */
class CadastroLojaForm extends KleoForm {

  public function __construct($name = null, $shoppings = null) {
    parent::__construct($name);

    $this->add(
      (new Number())
      ->setName(self::inputDDD)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDDD,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Tel())
      ->setName(self::inputTelefone)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputTelefone,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputCNPJ)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputCNPJ,
      self::stringRequired => self::stringRequired,
    ])
    ); 

    $arrayShoppings = [];
    if($shoppings){
      foreach($shoppings as $shopping){
        $arrayShoppings[$shopping->getId()] = $shopping->getNome() . ' - ' . $shopping->getEstado()->getNome();  
      }
    }
    $inputSelectShoppings = new Select();
    $inputSelectShoppings->setName(self::inputShoppingId);
    $inputSelectShoppings->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputShoppingId,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectShoppings->setValueOptions($arrayShoppings);
    $inputSelectShoppings->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectShoppings);
  }
  
  public function setarShoppings($shoppings){
   $arrayShoppings = [];
    if($shoppings){
      foreach($shoppings as $shopping){
        $arrayShoppings[$shopping->getId()] = $shopping->getNome() . ' - ' . $shopping->getEstado()->getNome();  
      }
    }
    $inputShopping = $this->get(self::inputShoppingId);
    $inputShopping->setValueOptions($arrayShoppings);
  }

}