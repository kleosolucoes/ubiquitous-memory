<?php

namespace Application\Model\Entity;

/**
 * Nome: Situacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="situacao")
 */
class Situacao extends KleoEntity {
  
    /**
     * @ORM\OneToMany(targetEntity="ResponsavelSituacao", mappedBy="responsavelSituacao") 
     */
    protected $responsavelSituacao;
    
    /**
     * @ORM\OneToMany(targetEntity="LojaSituacao", mappedBy="empresaSituacao") 
     */
    protected $lojaSituacao;
  
  
    public function __construct() {
        $this->responsavelSituacao = new ArrayCollection();
        $this->lojaSituacao = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
    }
    function getResponsavelSituacao() {
        return $this->responsavelSituacao;
    }
    function setResponsavelSituacao($responsavelSituacao) {
        $this->responsavelSituacao = $responsavelSituacao;
    }

}
