<?php

namespace Application\Model\Entity;

/**
 * Nome: Responsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o responsavel
 */

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="responsavel")
 */
class Responsavel extends KleoEntity {

    /** @ORM\Column(type="string") */
    private $nome;

    /** @ORM\Column(type="integer") */
    private $telefone;

    /** @ORM\Column(type="string") */
    private $email;

    /** @ORM\Column(type="string") */
    private $empresa;

    /** @ORM\Column(type="integer") */
    private $cnpj;

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

}
