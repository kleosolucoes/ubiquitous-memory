<?php

namespace Application\Model\Entity;

/**
 * Nome: Shopping.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o shopping
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Form\KleoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity 
 * @ORM\Table(name="shopping")
 */
class Shopping extends KleoEntity implements InputFilterAwareInterface{
    
  
    protected $inputFilter;
    protected $inputFilterCadastrarShopping;
    
    /** @ORM\Column(type="string") */
    protected $nome;
  
    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
    }
  
    public function exchangeArray($data) {
        $this->nome = (!empty($data[KleoForm::inputNome]) ? strtoupper($data[KleoForm::inputNome]) : null);
    }
  
    public function getInputFilterCadastrarShopping() {
        if (!$this->inputFilterCadastrarShopping) {
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
            $this->inputFilterCadastrarShopping = $inputFilter;
        }
        return $this->inputFilterCadastrarShopping;
    }
  
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }
    public function getInputFilter() {
        
    }

}
