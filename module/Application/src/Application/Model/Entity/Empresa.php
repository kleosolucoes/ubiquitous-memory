<?php

namespace Application\Model\Entity;

/**
 * Nome: Empresa.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para a empresa
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Form\KleoForm;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity 
 * @ORM\Table(name="empresa")
 */
class Empresa extends KleoEntity implements InputFilterAwareInterface{
    
  
    protected $inputFilter;
    protected $inputFilterCadastrarEmpresa;
    /**
     * @ORM\OneToMany(targetEntity="EmpresaSituacao", mappedBy="empresa") 
     */
    protected $empresaSituacao;
  
    public function __construct() {
        $this->empresaSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $telefone;

   /** @ORM\Column(type="integer") */
    protected $cnpj;
  
    /**
     * Retorna o empresa situacao ativo
     * @return EmpresaSituacao
     */
    function getEmpresaSituacaoAtivo() {
        $empresaSituacao = null;
        foreach ($this->getEmpresaSituacao() as $es) {
            if ($es->verificarSeEstaAtivo()) {
                $empresaSituacao = $es;
                break;
            }
        }
        return $empresaSituacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
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

    function getEmpresaSituacao() {
        return $this->empresaSituacao;
    }
    function setEmpresaSituacao($empresaSituacao) {
        $this->empresaSituacao = $empresaSituacao;
    }
    public function exchangeArray($data) {
        $this->nome = (!empty($data[KleoForm::inputNome]) ? strtoupper($data[KleoForm::inputNome]) : null);
        $this->ddd = (!empty($data[KleoForm::inputDDD]) ? strtoupper($data[KleoForm::inputDDD]) : null);
        $this->telefone = (!empty($data[KleoForm::inputTelefone]) ? strtoupper($data[KleoForm::inputTelefone]) : null);
        $this->cnpj = (!empty($data[KleoForm::inputCNPJ]) ? strtoupper($data[KleoForm::inputCNPJ]) : null);
    }
  public function getInputFilterCadastrarEmpresa() {
        if (!$this->inputFilterCadastrarEmpresa) {
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
           
            $this->inputFilterCadastrarEmpresa = $inputFilter;
        }
        return $this->inputFilterCadastrarEmpresa;
    }
  
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }
    public function getInputFilter() {
        
    }

}
