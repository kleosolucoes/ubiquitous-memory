<?php

namespace Application\Model\Entity;

/**
 * Nome: Anuncio.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o anuncio
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Form\KleoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Input;
use Zend\Validator;

/**
 * @ORM\Entity 
 * @ORM\Table(name="anuncio")
 */
class Anuncio extends KleoEntity implements InputFilterAwareInterface{


  protected $inputFilter;
  protected $inputFilterCadastrarAnuncio;

  /**
  * @ORM\ManyToOne(targetEntity="Responsavel", inversedBy="anuncio")
  * @ORM\JoinColumn(name="responsavel_id", referencedColumnName="id")
  */
  private $responsavel;

  /**
  * @ORM\OneToMany(targetEntity="AnuncioCategoria", mappedBy="anuncio") 
  */
  protected $anuncioCategoria;

  /**
  * @ORM\OneToMany(targetEntity="AnuncioLoja", mappedBy="anuncio") 
  */
  protected $anuncioLoja;

  /**
  * @ORM\OneToMany(targetEntity="AnuncioSituacao", mappedBy="anuncio") 
  */
  protected $anuncioSituacao;

  public function __construct() {
    $this->anuncioCategoria = new ArrayCollection();
    $this->anuncioLoja = new ArrayCollection();
    $this->anuncioSituacao = new ArrayCollection();
  }

  /** @ORM\Column(type="string") */
  protected $titulo;

  /** @ORM\Column(type="string") */
  protected $descricao;

  /** @ORM\Column(type="decimal") */
  protected $preco;

  /** @ORM\Column(type="string") */
  protected $foto1;

  /** @ORM\Column(type="string") */
  protected $foto2;

  /** @ORM\Column(type="string") */
  protected $foto3;

  /** @ORM\Column(type="string") */
  protected $foto4;

  /** @ORM\Column(type="string") */
  protected $foto5;

  /** @ORM\Column(type="integer") */
  protected $responsavel_id;

  /**
  * Retorna a situacao ativo
  * @return AnuncioSituacao
  */
  public function getAnuncioSituacaoAtivo() {
    $anuncioSituacao = null;
    foreach ($this->getAnuncioSituacao() as $as) {
      if ($as->verificarSeEstaAtivo()) {
        $anuncioSituacao = $as;
        break;
      }
    }
    return $anuncioSituacao;
  }


  function setTitulo($titulo) {
    $this->titulo = $titulo;
  }

  function getTitulo() {
    return $this->titulo;
  }

  function setDescricao($descricao) {
    $this->descricao = $descricao;
  }

  function getDescricao() {
    return $this->descricao;
  }

  function setPreco($preco) {
    $this->preco = $preco;
  }

  function getPreco() {
    return $this->preco;
  }

  function setFoto1($foto) {
    $this->foto1 = $foto;
  }

  function getFoto1() {
    return $this->foto1;
  }

  function setFoto2($foto) {
    $this->foto2 = $foto;
  }

  function getFoto2() {
    return $this->foto2;
  }

  function setFoto3($foto) {
    $this->foto3 = $foto;
  }

  function getFoto3() {
    return $this->foto3;
  }

  function setFoto4($foto) {
    $this->foto4 = $foto;
  }

  function getFoto4() {
    return $this->foto4;
  }

  function setFoto5($foto) {
    $this->foto5 = $foto;
  }

  function getFoto5() {
    return $this->foto5;
  }

  function setAnuncioSituacao($anuncioSituacao) {
    $this->anuncioSituacao = $anuncioSituacao;
  }

  function getAnuncioSituacao() {
    return $this->anuncioSituacao;
  }

  function setAnuncioCategoria($anuncioCategoria) {
    $this->anuncioCategoria = $anuncioCategoria;
  }

  function getAnuncioCategoria() {
    return $this->anuncioCategoria;
  }

  function setAnuncioLoja($anuncioLoja) {
    $this->anuncioLoja = $anuncioLoja;
  }

  function getAnuncioLoja() {
    return $this->anuncioLoja;
  }

  function setResponsavel($responsavel) {
    $this->responsavel = $responsavel;
  }

  function getResponsavel() {
    return $this->responsavel;
  }

  public function exchangeArray($data) {
    $this->titulo = (!empty($data[KleoForm::inputTitulo]) ? strtoupper($data[KleoForm::inputTitulo]) : null);
    $this->descricao = (!empty($data[KleoForm::inputDescricao]) ? strtoupper($data[KleoForm::inputDescricao]) : null);
    $this->preco = (!empty($data[KleoForm::inputPreco]) ? strtoupper($data[KleoForm::inputPreco]) : null);
    $this->foto1 = (!empty($data[KleoForm::inputFoto1]) ? strtoupper($data[KleoForm::inputFoto1]) : null);
    $this->foto2 = (!empty($data[KleoForm::inputFoto2]) ? strtoupper($data[KleoForm::inputFoto2]) : null);
    $this->foto3 = (!empty($data[KleoForm::inputFoto3]) ? strtoupper($data[KleoForm::inputFoto3]) : null);
    $this->foto4 = (!empty($data[KleoForm::inputFoto4]) ? strtoupper($data[KleoForm::inputFoto4]) : null);
    $this->foto5 = (!empty($data[KleoForm::inputFoto5]) ? strtoupper($data[KleoForm::inputFoto5]) : null);
  }

  public function setInputFilter(InputFilterInterface $inputFilter) {
    throw new Exception("Nao utilizado");
  }

  public function getInputFilter() {

  }

  public function getInputFilterCadastrarAnuncio(){
    if(!$this->inputFilterCadastrarAnuncio) {

      $inputFilter = new InputFilter();
      $inputFilter->add(array(
        'name' => KleoForm::inputTitulo,
        'required' => true,
        'filter' => array(
        array('name' => 'StripTags'), // removel xml e html string
        array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
        array('name' => 'StringToUpper'), // transforma em maiusculo
      ),
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
        array(
        'name' => 'StringLength',
        'options' => array(
        'encoding' => 'UTF-8',
        'min' => 10,
        'max' => 60,
      ),
      ),
      ),
      ));

      $inputFilter->add(array(
        'name' => KleoForm::inputDescricao,
        'required' => true,
        'filter' => array(
        array('name' => 'StripTags'), // removel xml e html string
        array('name' => 'StringTrim'), // removel espaco do inicio e do final da string
        array('name' => 'StringToUpper'), // transforma em maiusculo
      ),
        'validators' => array(
        array(
        'name' => 'NotEmpty',
      ),
        array(
        'name' => 'StringLength',
        'options' => array(
        'encoding' => 'UTF-8',
        'min' => 10,
        'max' => 100,
      ),
      ),
      ),
      ));

      $preco = new Input(KleoForm::inputPreco);
//       $filter = new \Zend\I18n\Filter\NumberFormat("de_DE");
//       $preco->getValidatorChain()
//         ->attach(new NumberFormat("pt_BR", NumberFormatter::TYPE_DOUBLE));
      $inputFilter->add($preco);

      $this->inputFilterCadastrarAnuncio = $inputFilter;
    }
    return $this->inputFilterCadastrarAnuncio;
  }

}
