<?php

namespace Application\Model\Entity;

/**
 * Nome: AnuncioLoja.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o anuncio_loja
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="anuncio_loja")
 */
class AnuncioLoja extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="anuncioLoja")
     * @ORM\JoinColumn(name="anuncio_id", referencedColumnName="id")
     */
    private $anuncio;
    /**
     * @ORM\ManyToOne(targetEntity="Loja", inversedBy="anuncioLoja")
     * @ORM\JoinColumn(name="loja_id", referencedColumnName="id")
     */
    private $loja;
    /** @ORM\Column(type="integer") */
    protected $loja_id;
    /** @ORM\Column(type="integer") */
    protected $anuncio_id;
  
    function getAnuncio() {
        return $this->anuncio;
    }
    function getLoja() {
        return $this->loja;
    }
    function getLoja_id() {
        return $this->loja_id;
    }
    function getAnuncio_id() {
        return $this->anuncio_id;
    }
    function setAnuncio($anuncio) {
        $this->anuncio = $anuncio;
    }
    function setLoja($loja) {
        $this->loja = $loja;
    }
    function setLoja_id($loja_id) {
        $this->loja_id = $loja_id;
    }
    function setAnuncio_id($anuncio_id) {
        $this->anuncio_id = $anuncio_id;
    }

}
