<?php

namespace Application\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;

/**
 * Nome: KleoForm.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Formulario base  
 *              
 */
class KleoForm extends Form {

    const inputNome = 'inputNome';
    const inputDDD = 'inputDDD';
    const inputTelefone = 'inputTelefone';
    const inputEmail = 'inputEmail';
    const inputRepetirEmail = 'inputRepetirEmail';
    const inputEmpresa = 'inputEmpresa';
    const inputCNPJ = 'inputCNPJ';
    const inputId = 'inputId';
    const inputCSRF = 'inputCSRF';

    const stringClass = 'class';
    const stringClassFormControl = 'form-control';
    const stringId = 'id';
    const stringPlaceholder = 'placeholder';
    const stringAction = 'action';
    const stringRequired = 'required';

    const traducaoNome = 'Nome';
    const traducaoDDD = 'DDD';
    const traducaoTelefone = 'Telefone';
    const traducaoEmail = 'Email';
    const traducaoRepetirEmail = 'Repetir Email';
    const traducaoEmpresa = 'Empresa';
    const traducaoCNPJ = 'CNPJ';
    
    public function __construct($name = null) {
    
      parent::__construct($name);
        $this->setAttributes(array(
            'method' => 'post',
        ));
      
        $this->add(
                (new Hidden())
                        ->setName('inputId')
                        ->setAttributes([
                            'id' => 'inputId',
                        ])
        );
      
        $this->add(
                (new Csrf())
                        ->setName('inputCSRF')
        );


    }

}