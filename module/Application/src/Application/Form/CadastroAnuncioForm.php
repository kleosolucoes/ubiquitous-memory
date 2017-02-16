<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Number;
use Zend\Form\Element\File;

/**
 * Nome: CadastroAnuncioForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de cadastro de anuncios
 *              
 */
class CadastroAnuncioForm extends KleoForm {

  public function __construct($name = null) {
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
      'onchange'=>'carregarFoto(this);',
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto2)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto2,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto3)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto3,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto4)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto4,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputFoto5)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputFoto5,
    ])
    );

  }
}