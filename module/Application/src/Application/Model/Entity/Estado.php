<?php

namespace Application\Model\Entity;

/**
 * Nome: Situacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o estado
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="estado")
 */
class Estado extends KleoEntity {
  
    /**
     * @ORM\OneToMany(targetEntity="Shopping", mappedBy="estado") 
     */
    protected $shoppings;    
  
  
    public function __construct() {
        $this->shoppings = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
    }
    function getShoppings() {
        return $this->shoppings;
    }
    function setShopping($shoppings) {
        $this->shoppings = $shoppings;
    }

}
