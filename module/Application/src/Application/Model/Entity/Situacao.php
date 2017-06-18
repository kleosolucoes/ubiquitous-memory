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

  const primeiroContato = 1;
  const aguardandoDocumentacao = 2;
  const documentosEntregues = 3;
  const ativo = 4;
  const recusado = 5;
  /**
     * @ORM\OneToMany(targetEntity="ResponsavelSituacao", mappedBy="responsavelSituacao") 
     */
  protected $responsavelSituacao;

  /**
     * @ORM\OneToMany(targetEntity="LojaSituacao", mappedBy="empresaSituacao") 
     */
  protected $lojaSituacao;

  /**
     * @ORM\OneToMany(targetEntity="AnuncioSituacao", mappedBy="anuncioSituacao") 
     */
  protected $anuncioSituacao;


  public function __construct() {
    $this->responsavelSituacao = new ArrayCollection();
    $this->lojaSituacao = new ArrayCollection();
    $this->anuncioSituacao = new ArrayCollection();
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

  function getLojaSituacao() {
    return $this->lojaSituacao;
  }
  function setLojaSituacao($lojaSituacao) {
    $this->lojaSituacao = $lojaSituacao;
  }

  function getAnuncioSituacao() {
    return $this->anuncioSituacao;
  }
  function setAnuncioSituacao($anuncioSituacao) {
    $this->anuncioSituacao = $anuncioSituacao;
  }

}
