<?php

namespace Application\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Number;
use Zend\Form\Element\Email;
use Zend\Form\Element\Tel;
use Zend\Form\Element\Select;
use Zend\Form\Element\File;
use Application\Model\Entity\Responsavel;

/**
 * Nome: ResponsavelAtualizacaoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario de atualizacao de responsaveis  
 *              
 */
class ResponsavelAtualizacaoForm extends CadastroResponsavelForm {

  public function __construct($name = null, Responsavel $responsavel) {
    parent::__construct($name);

    $inputId = $this->get(self::inputId);
    $inputId->setValue($responsavel->getId());
    
    $inputNome = $this->get(self::inputNome);
    $inputNome->setValue($responsavel->getNome());
    
    $inputDDD = $this->get(self::inputDDD);
    $inputDDD->setValue(substr($responsavel->getTelefone(), 0 , 2));
    
    $inputTelefone = $this->get(self::inputTelefone);
    $inputTelefone->setValue(substr($responsavel->getTelefone(), 2));
    
    $inputEmail = $this->get(self::inputEmail);
    $inputEmail->setValue($responsavel->getEmail());
    
    $inputNomeFantasia = $this->get(self::inputNomeFantasia);
    $inputNomeFantasia->setValue($responsavel->getNomeFantasia());
    
    $inputCNPJ = $this->get(self::inputCNPJ);
    $inputCNPJ->setValue($responsavel->getCNPJ());
    
    $this->add(
      (new Number())
      ->setName(self::inputCPF)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputCPF,
      self::stringRequired => self::stringRequired,
    ])
    );

    /* Dia da data de nascimento */
    $arrayDiaDataNascimento = array();
    for ($indiceDiaDoMes = 1; $indiceDiaDoMes <= 31; $indiceDiaDoMes++) {
      $numeroAjustado = str_pad($indiceDiaDoMes, 2, 0, STR_PAD_LEFT);
      $arrayDiaDataNascimento[$indiceDiaDoMes] = $numeroAjustado;
    }
    $inputSelectDiaDataNascimento = new Select();
    $inputSelectDiaDataNascimento->setName(self::inputDia);
    $inputSelectDiaDataNascimento->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDia,
      self::stringRequired => self::stringRequired,

    ));
    $inputSelectDiaDataNascimento->setValueOptions($arrayDiaDataNascimento);
    $inputSelectDiaDataNascimento->setEmptyOption(self::traducaoDia);
    $this->add($inputSelectDiaDataNascimento);
    /* Mês da data de nascimento */
    $arrayMesDataNascimento = array();
    for ($indiceMesNoAno = 1; $indiceMesNoAno <= 12; $indiceMesNoAno++) {
      $numeroAjustado = str_pad($indiceMesNoAno, 2, 0, STR_PAD_LEFT);
      $arrayMesDataNascimento[$indiceMesNoAno] = $numeroAjustado;
    }
    $inputSelectMesDataNascimento = new Select();
    $inputSelectMesDataNascimento->setName(self::inputMes);
    $inputSelectMesDataNascimento->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputMes,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectMesDataNascimento->setValueOptions($arrayMesDataNascimento);
    $inputSelectMesDataNascimento->setEmptyOption(self::traducaoMes);
    $this->add($inputSelectMesDataNascimento);
    /* Ano da data de nascimento */
    $arrayAnoDataNascimento = array();
    $anoAtual = date('Y');
    for ($indiceAno = $anoAtual; $indiceAno >= ($anoAtual - 100); $indiceAno--) {
      $arrayAnoDataNascimento[$indiceAno] = $indiceAno;
    }
    $inputSelectAnoDataNascimento = new Select();
    $inputSelectAnoDataNascimento->setName(self::inputAno);
    $inputSelectAnoDataNascimento->setAttributes(array(
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputAno,
      self::stringRequired => self::stringRequired,
    ));
    $inputSelectAnoDataNascimento->setValueOptions($arrayAnoDataNascimento);
    $inputSelectAnoDataNascimento->setEmptyOption(self::traducaoAno);
    $this->add($inputSelectAnoDataNascimento);

    $this->add(
      (new Text())
      ->setName(self::inputRazaoSocial)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputRazaoSocial,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputDDDEmpresa)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputDDDEmpresa,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Tel())
      ->setName(self::inputTelefoneEmpresa)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputTelefoneEmpresa,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Email())
      ->setName(self::inputEmailEmpresa)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputTelefoneEmpresa,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new Number())
      ->setName(self::inputNumeroLojas)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputNumeroLojas,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputUploadCPF)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputUploadCPF,
      self::stringRequired => self::stringRequired,
    ])
    );

    $this->add(
      (new File())
      ->setName(self::inputUploadContratoSocial)
      ->setAttributes([
      self::stringClass => self::stringClassFormControl,
      self::stringId => self::inputUploadContratoSocial,
      self::stringRequired => self::stringRequired,
    ])
    );
  }
}