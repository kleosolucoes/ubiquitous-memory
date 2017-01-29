<?php

namespace Application\Model\Entity;

/**
 * Nome: LojaSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o loja_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="loja_situacao")
 */
class LojaSituacao extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Loja", inversedBy="lojaSituacao")
     * @ORM\JoinColumn(name="loja_id", referencedColumnName="id")
     */
    private $loja;
    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="empresaSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;
    /** @ORM\Column(type="integer") */
    protected $situacao_id;
    /** @ORM\Column(type="integer") */
    protected $loja_id;
  
    function getLoja() {
        return $this->loja;
    }
    function getSituacao() {
        return $this->situacao;
    }
    function getSituacao_id() {
        return $this->situacao_id;
    }
    function getLoja_id() {
        return $this->loja_id;
    }
    function setLoja($loja) {
        $this->loja = $loja;
    }
    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }
    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }
    function setLoja_id($loja_id) {
        $this->loja_id = $loja_id;
    }

}
