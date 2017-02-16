<?php

namespace Application\Model\Entity;

/**
 * Nome: Categoria.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o categoria
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="categoria")
 */
class Categoria extends KleoEntity {
  
    /**
     * @ORM\OneToMany(targetEntity="AnuncioCategoria", mappedBy="anuncioCategoria") 
     */
    protected $anuncioCategoria;
    
    public function __construct() {
        $this->anuncioCategoria = new ArrayCollection();
    }

    /** @ORM\Column(type="string") */
    protected $nome;

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
    }
    function getAnuncioCategoria() {
        return $this->anuncioCategoria;
    }
    function setAnuncioCategoria($anuncioCategoria) {
        $this->anuncioCategoria = $anuncioCategoria;
    }

}
