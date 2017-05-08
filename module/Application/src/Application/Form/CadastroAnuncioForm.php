<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Number;
use Zend\Form\Element\File;
use Zend\Form\Element\Select;

/**
 * Nome: CadastroAnuncioForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de anuncios
 *              
 */
class CadastroAnuncioForm extends KleoForm {

  public function __construct($name = null, $categorias = null) {
    parent::__construct($name);

    $this->add(
      (new Text())
      ->setName(self::inputTitulo)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputTitulo,
      self::stringRequired => self::stringRequired,
      self::stringPlaceholder => 'Ex: Blusa Lacoste Branca Masculina',
    ])
    );

    $this->add(
      (new TextArea())
      ->setName(self::inputDescricao)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDescricao,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputPreco)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputPreco,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto1)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto1,
      self::stringRequired => self::stringRequired,
      'onchange'=>'carregarFoto(this, 1);',
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto2)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto2,
      'onchange'=>'carregarFoto(this, 2);',
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto3)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto3,
      'onchange'=>'carregarFoto(this, 3);',
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto4)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto4,
      'onchange'=>'carregarFoto(this, 4);',
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto5)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto5,
      'onchange'=>'carregarFoto(this, 5);',
    ])
    );

    $inputSelectDiaValidade = new Select();
    $inputSelectDiaValidade->setName(self::inputDiaValidade);
    $inputSelectDiaValidade->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDiaValidade,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectDiaValidade->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectDiaValidade);
    $this->setarOpcoes(1); 

    $inputSelectMesValidade = new Select();
    $inputSelectMesValidade->setName(self::inputMesValidade);
    $inputSelectMesValidade->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputMesValidade,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectMesValidade->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectMesValidade);
    $this->setarOpcoes(2);    

    $inputSelectCategorias = new Select();
    $inputSelectCategorias->setName(self::inputCategoriaId);
    $inputSelectCategorias->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputCategoriaId,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectCategorias->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectCategorias);
    $this->setarCategorias($categorias);

    $inputSelectSubCategoria = new Select();
    $inputSelectSubCategoria->setName(self::inputSubCategoriaId);
    $inputSelectSubCategoria->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputSubCategoriaId,
    ));
    $inputSelectSubCategoria->setEmptyOption(self::traducaoSelecione);
    $this->add($inputSelectSubCategoria);
    $this->setarCategorias($categorias, 2);
  }

  public function setarCategorias($categorias, $tipo = 1){
    $arrayCategorias = [];
    $nomeDoCampo = self::inputCategoriaId;
    if($tipo === 2){
      $nomeDoCampo = self::inputSubCategoriaId;
    }
    if($categorias){
      foreach($categorias as $categoria){
        $adicionar = false;
        if($tipo === 1){
          if($categoria->getCategoria_id() == null){
            $adicionar = true;
          }
        }
        if($tipo === 2){
          if($categoria->getCategoria_id()){
            $adicionar = true;
          }
        }
        if($adicionar){
          $arrayCategorias[$categoria->getId()] = $categoria->getNome();
        }
      }
    }
    $inputCategoria = $this->get($nomeDoCampo);
    $inputCategoria->setValueOptions($arrayCategorias);
  }


  public function setarOpcoes($tipo){
    $arrayOptions = [];
    $nomeDoCampo = self::inputDiaValidade;
    if($tipo === 2){
      $nomeDoCampo = self::inputMesValidade;
    }
    $inicioIndex = 1;
    $fimIndex = 0;
    if($tipo === 1){
      $fimIndex =31;
    }
    if($tipo === 2){
      $fimIndex =12;
    }

    for($index = $inicioIndex; $index <= $fimIndex; $index++){
      $indexAjustada = str_pad($index, 2, 0,STR_PAD_LEFT);
      if($tipo === 1){
        $arrayOptions[$indexAjustada] = $indexAjustada . ' Dia';
      }
      if($tipo === 2){
        $arrayOptions[$indexAjustada] = $indexAjustada;
      }
    }
    $input = $this->get($nomeDoCampo);
    $input->setValueOptions($arrayOptions);
  }
}