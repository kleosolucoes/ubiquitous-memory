<?php

namespace Application\Model\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Nome: Responsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o responsavel
 */
class Responsavel extends KleoEntity {

    /** @ORM\Column(type="string") */
    protected $nome;

    /** @ORM\Column(type="int") */
    protected $telefone;

    /** @ORM\Column(type="string") */
    protected $email;

    /** @ORM\Column(type="string") */
    protected $empresa;

    /** @ORM\Column(type="int") */
    protected $cnpj;

    function setNome($id) {
        $this->id = $id;
    }

    function getNome() {
        return $this->id;
    }

    function setTelefone($id) {
        $this->id = $id;
    }

    function getTelefone() {
        return $this->id;
    }

    function setEmail($id) {
        $this->id = $id;
    }

    function getEmail() {
        return $this->id;
    }

    function setEmpresa($id) {
        $this->id = $id;
    }

    function getEmpresa() {
        return $this->id;
    }

    function setCnpj($id) {
        $this->id = $id;
    }

    function getCnpj() {
        return $this->id;
    }

}
