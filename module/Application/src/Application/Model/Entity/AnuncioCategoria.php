<?php

namespace Application\Model\Entity;

/**
 * Nome: AnuncioCategoria.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o anuncio_categoria
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="anuncio_categoria")
 */
class AnuncioCategoria extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="anuncioCategoria")
     * @ORM\JoinColumn(name="anuncio_id", referencedColumnName="id")
     */
    private $anuncio;
    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="anuncioCategoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;
    /** @ORM\Column(type="integer") */
    protected $categoria_id;
    /** @ORM\Column(type="integer") */
    protected $anuncio_id;
  
    function getAnuncio() {
        return $this->anuncio;
    }
    function getCategoria() {
        return $this->categoria;
    }
    function getCategoria_id() {
        return $this->categoria_id;
    }
    function getAnuncio_id() {
        return $this->anuncio_id;
    }
    function setAnuncio($anuncio) {
        $this->anuncio = $anuncio;
    }
    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }
    function setCategoria_id($categoria_id) {
        $this->categoria_id = $categoria_id;
    }
    function setAnuncio_id($anuncio_id) {
        $this->anuncio_id = $anuncio_id;
    }

}
