<?php

namespace Application\Model\Entity;

/**
 * Nome: Loja.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para a loja
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Form\KleoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity 
 * @ORM\Table(name="loja")
 */
class Loja extends KleoEntity implements InputFilterAwareInterface{


  protected $inputFilter;
  protected $inputFilterCadastrarLoja;
  /**
     * @ORM\OneToMany(targetEntity="LojaSituacao", mappedBy="loja") 
     */
  protected $lojaSituacao;

  /**
     * @ORM\ManyToOne(targetEntity="Responsavel", inversedBy="lojas")
     * @ORM\JoinColumn(name="responsavel_id", referencedColumnName="id")
     */
  private $responsavel;

  /**
     * @ORM\ManyToOne(targetEntity="Shopping", inversedBy="shopping")
     * @ORM\JoinColumn(name="shopping_id", referencedColumnName="id")
     */
  private $shopping;

  public function __construct() {
    $this->lojaSituacao = new ArrayCollection();
  }

  /** @ORM\Column(type="integer") */
  protected $telefone;

  /** @ORM\Column(type="integer") */
  protected $cnpj;

  /** @ORM\Column(type="integer") */
  protected $situacao_id;

  /** @ORM\Column(type="integer") */
  protected $responsavel_id;

  /** @ORM\Column(type="integer") */
  protected $shopping_id;

  /**
     * Retorna a situacao ativa
     * @return LojaSituacao
     */
  function getLojaSituacaoAtivo() {
    $lojaSituacao = null;
    foreach ($this->getLojaSituacao() as $ls) {
      if ($ls->verificarSeEstaAtivo()) {
        $lojaSituacao = $ls;
        break;
      }
    }
    return $lojaSituacao;
  }

  function setTelefone($telefone) {
    $this->telefone = $telefone;
  }

  function getTelefone() {
    return $this->telefone;
  }

  function setCnpj($cnpj) {
    $this->cnpj = $cnpj;
  }

  function getCnpj() {
    return $this->cnpj;
  }

  function getLojaSituacao() {
    return $this->lojaSituacao;
  }

  function setLojaSituacao($lojaSituacao) {
    $this->lojaSituacao = $lojaSituacao;
  }

  function setResponsavel($responsavel) {
    $this->responsavel = $responsavel;
  }

  function getResponsavel() {
    return $this->responsavel;
  }

  function setResponsavel_id($responsavel_id) {
    $this->responsavel_id = $responsavel_id;
  }

  function getResponsavel_id() {
    return $this->responsavel_id;
  }

  function setSituacao_id($situacao_id) {
    $this->situacao_id = $situacao_id;
  }

  function getSituacao_id() {
    return $this->situacao_id;
  }

  function setShopping_id($shopping_id) {
    $this->shopping_id = $shopping_id;
  }

  function getShopping_id() {
    return $this->shopping_id;
  }

  function setShopping($shopping) {
    $this->shopping = $shopping;
  }

  function getShopping() {
    return $this->shopping;
  }

  public function exchangeArray($data) {
    $this->telefone = (!empty($data[KleoForm::inputDDD]) && !empty($data[KleoForm::inputTelefone]) 
                       ? $data[KleoForm::inputDDD] . $data[KleoForm::inputTelefone] : null);
    $this->cnpj = (!empty($data[KleoForm::inputCNPJ]) ? $data[KleoForm::inputCNPJ] : null);
  }
  public function getInputFilterCadastrarLoja() {
    if (!$this->inputFilterCadastrarLoja) {
      $inputFilter = new InputFilter();

      $inputFilter->add(array(
        'name' => KleoForm::inputDDD,
        'required' => true,
        'filter' => array(
        array('name' => 'StripTags'), // removel xml e html string
        array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
        array('name' => 'Int'), // transforma string para inteiro
      ),
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
        array(
        'name' => 'StringLength',
        'options' => array(
        'encoding' => 'UTF-8',
        'min' => 2,
        'max' => 2,
      ),
      ),
      ),
      ));
      $inputFilter->add(array(
        'name' => KleoForm::inputTelefone,
        'required' => true,
        'filter' => array(
        array('name' => 'StripTags'), // removel xml e html string
        array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
        array('name' => 'Int'), // transforma string para inteiro
      ),
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
        array(
        'name' => 'StringLength',
        'options' => array(
        'encoding' => 'UTF-8',
        'min' => 8, 
        'max' => 9, 
      ),
      ),
      ),
      ));


      $inputFilter->add(array(
        'name' => KleoForm::inputCNPJ,
        'required' => true,
        'filter' => array(
        array('name' => 'StripTags'), // removel xml e html string
        array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
        array('name' => 'Int'), // transforma string para inteiro
      ),
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
        array(
        'name' => 'StringLength',
        'options' => array(
        'encoding' => 'UTF-8',
        'min' => 14,
        'max' => 14,
      ),
      ),
      ),
      ));
      $inputFilter->add(array(
        'name' => KleoForm::inputShoppingId,
        'required' => true,
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
      ),
      ));

      $this->inputFilterCadastrarLoja = $inputFilter;
    }
    return $this->inputFilterCadastrarLoja;
  }

  public function setInputFilter(InputFilterInterface $inputFilter) {
    throw new Exception("Nao utilizado");
  }
  public function getInputFilter() {

  }

}
