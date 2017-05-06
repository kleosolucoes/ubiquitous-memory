<?php

namespace Application\Model\Entity;

/**
 * Nome: Categoria.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o categoria
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Form\KleoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity 
 * @ORM\Table(name="categoria")
 */
class Categoria extends KleoEntity implements InputFilterAwareInterface{

  protected $inputFilter;
  protected $inputFilterCadastrarCategoria;

  /**
     * @ORM\OneToMany(targetEntity="AnuncioCategoria", mappedBy="anuncioCategoria") 
     */
  protected $anuncioCategoria;

  public function __construct() {
    $this->anuncioCategoria = new ArrayCollection();
  }

  /** @ORM\Column(type="string") */
  protected $nome;

  /** @ORM\Column(type="integer") */
  protected $categoria_id;
  
  function setNome($nome) {
    $this->nome = $nome;
  }

  function getNome() {
    return $this->nome;
  }
  function getAnuncioCategoria() {
    return $this->anuncioCategoria;
  }
  function setAnuncioCategoria($anuncioCategoria) {
    $this->anuncioCategoria = $anuncioCategoria;
  }
  
  function setCategoria_id($categoria_id) {
    $this->categoria_id = $categoria_id;
  }

  function getCategoria_id() {
    return $this->categoria_id;
  }
  
  public function exchangeArray($data) {
    $this->nome = (!empty($data[KleoForm::inputNome]) ? strtoupper($data[KleoForm::inputNome]) : null);
    $this->categoria_id = (!empty($data[KleoForm::inputCategoriaId]) ? strtoupper($data[KleoForm::inputCategoriaId]) : null);
  }
  
   public function getInputFilterCadastrarCategoria() {
    if (!$this->inputFilterCadastrarCategoria) {
      $inputFilter = new InputFilter();
      $inputFilter->add(array(
        'name' => KleoForm::inputNome,
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
        'min' => 3,
        'max' => 80,
      ),
      ),
      ),
      ));
      
        $this->inputFilterCadastrarCategoria = $inputFilter;
    }
    return $this->inputFilterCadastrarCategoria;
  }

  public function setInputFilter(InputFilterInterface $inputFilter) {
    throw new Exception("Nao utilizado");
  }
  public function getInputFilter() {

  }

}
