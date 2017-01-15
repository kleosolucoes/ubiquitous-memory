<?php

namespace Application\Model\Entity;

/**
 * Nome: EmpresaSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o empresa_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="empresa_situacao")
 */
class EmpresaSituacao extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Empresa", inversedBy="empresaSituacao")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     */
    private $empresa;
    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="empresaSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;
    /** @ORM\Column(type="integer") */
    protected $situacao_id;
    /** @ORM\Column(type="integer") */
    protected $empresa_id;
  
    function getEmpresa() {
        return $this->empresa;
    }
    function getSituacao() {
        return $this->situacao;
    }
    function getSituacao_id() {
        return $this->situacao_id;
    }
    function getEmpresa_id() {
        return $this->empresa_id;
    }
    function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }
    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }
    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }
    function setEmpresa_id($empresa_id) {
        $this->empresa_id = $empresa_id;
    }

}
