<?php

namespace Application\Model\Entity;

/**
 * Nome: Responsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o responsavel
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
 * @ORM\Table(name="responsavel")
 */
class Responsavel extends KleoEntity implements InputFilterAwareInterface{
    
  
    protected $inputFilter;
    protected $inputFilterCadastrarResponsavel;
    /**
     * @ORM\OneToMany(targetEntity="ResponsavelSituacao", mappedBy="responsavel") 
     */
    protected $responsavelSituacao;
  
    public function __construct() {
        $this->responsavelSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="integer") */
    protected $telefone;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $empresa;

    /** @ORM\Column(type="integer") */
    protected $cnpj;
  
  /**
     * Retorna o responsavel situacao ativo
     * @return ResponsavelSituacao
     */
    function getResponsavelSituacaoAtivo() {
        $responsavelSituacao = null;
        foreach ($this->getResponsavelSituacao() as $rs) {
            if ($rs->verificarSeEstaAtivo()) {
                $responsavelSituacao = $rs;
                break;
            }
        }
        return $responsavelSituacao;
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

    function setEmail($email) {
        $this->email = $email;
    }

    function getEmail() {
        return $this->email;
    }

    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    function getEmpresa() {
        return $this->empresa;
    }

    function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    function getCnpj() {
        return $this->cnpj;
    }

    function getResponsavelSituacao() {
        return $this->responsavelSituacao;
    }
    function setResponsavelSituacao($responsavelSituacao) {
        $this->responsavelSituacao = $responsavelSituacao;
    }
    public function exchangeArray($data) {
        $this->nome = (!empty($data[KleoForm::inputNome]) ? strtoupper($data[KleoForm::inputNome]) : null);
        $this->ddd = (!empty($data[KleoForm::inputDDD]) ? strtoupper($data[KleoForm::inputDDD]) : null);
        $this->telefone = (!empty($data[KleoForm::inputTelefone]) ? strtoupper($data[KleoForm::inputTelefone]) : null);
        $this->email = (!empty($data[KleoForm::inputEmail]) ? strtoupper($data[KleoForm::inputEmail]) : null);
        $this->repetirEmail = (!empty($data[KleoForm::inputRepetirEmail]) ? strtoupper($data[KleoForm::inputRepetirEmail]) : null);
        $this->empresa = (!empty($data[KleoForm::inputEmpresa]) ? strtoupper($data[KleoForm::inputEmpresa]) : null);
        $this->cnpj = (!empty($data[KleoForm::inputCNPJ]) ? strtoupper($data[KleoForm::inputCNPJ]) : null);
    }
  public function getInputFilterCadastrarResponsavel() {
        if (!$this->inputFilterCadastrarResponsavel) {
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
            
            $email = new Input(KleoForm::inputEmail);
            $email->getValidatorChain()
                  ->attach(new Validator\EmailAddress());
            $inputFilter->add($email);
          
            $inputFilter->add(array(
                'name' => KleoForm::inputEmpresa,
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
           
            $this->inputFilterCadastrarResponsavel = $inputFilter;
        }
        return $this->inputFilterCadastrarResponsavel;
    }
  
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception("Nao utilizado");
    }
    public function getInputFilter() {
        
    }

}
