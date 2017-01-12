<?php

namespace Application\Model\Entity;

/**
 * Nome: Responsavel.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o responsavel
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="responsavel")
 */
class Responsavel extends KleoEntity {

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
}
