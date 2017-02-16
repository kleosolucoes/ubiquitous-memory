<?php

namespace Application\Model\Entity;

/**
 * Nome: AnuncioSituacao.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Entidade anotada base para o anuncio_situacao
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="anuncio_situacao")
 */
class AnuncioSituacao extends KleoEntity {

    /**
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="anuncioSituacao")
     * @ORM\JoinColumn(name="anuncio_id", referencedColumnName="id")
     */
    private $anuncio;
    /**
     * @ORM\ManyToOne(targetEntity="Situacao", inversedBy="anuncioSituacao")
     * @ORM\JoinColumn(name="situacao_id", referencedColumnName="id")
     */
    private $situacao;
    /** @ORM\Column(type="integer") */
    protected $situacao_id;
    /** @ORM\Column(type="integer") */
    protected $anuncio_id;
  
    function getAnuncio() {
        return $this->anuncio;
    }
    function getSituacao() {
        return $this->situacao;
    }
    function getSituacao_id() {
        return $this->situacao_id;
    }
    function getAnuncio_id() {
        return $this->anuncio_id;
    }
    function setAnuncio($anuncio) {
        $this->anuncio = $anuncio;
    }
    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }
    function setSituacao_id($situacao_id) {
        $this->situacao_id = $situacao_id;
    }
    function setAnuncio_id($anuncio_id) {
        $this->anuncio_id = $anuncio_id;
    }

}
