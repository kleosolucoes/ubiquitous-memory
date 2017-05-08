<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Loja;
use Application\Model\Entity\LojaSituacao;
use Application\Model\Entity\Shopping;
use Application\Model\Entity\Anuncio;
use Application\Model\Entity\AnuncioSituacao;
use Application\Model\Entity\AnuncioCategoria;
use Application\Model\Entity\Categoria;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\CadastroLojaForm;
use Application\Form\CadastroShoppingForm;
use Application\Form\ResponsavelSituacaoForm;
use Application\Form\ResponsavelAtualizacaoForm;
use Application\Form\LojaSituacaoForm;
use Application\Form\CadastroAnuncioForm;
use Application\Form\CadastroCategoriaForm;
use Application\Form\KleoForm;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de crud
 */
class CadastroController extends KleoController {

  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      parent::__construct($doctrineORMEntityManager);
    }
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastro
     */
  public function indexAction() {    
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavel
     */
  public function responsavelAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $cadastroResponsavelForm = $formulario;
    }else{
      $cadastroResponsavelForm = new CadastroResponsavelForm('cadastroResponsavel');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $cadastroResponsavelForm,
    )
    );
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizar
     */
  public function responsavelFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $responsavel = new Responsavel();
        $cadastrarResponsavelForm = new CadastroResponsavelForm();
        $cadastrarResponsavelForm->setInputFilter($responsavel->getInputFilterCadastrarResponsavel());
        $cadastrarResponsavelForm->setData($post_data);

        /* validação */
        if ($cadastrarResponsavelForm->isValid()) {
          $validatedData = $cadastrarResponsavelForm->getData();
          $responsavel->exchangeArray($cadastrarResponsavelForm->getData());
          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId(1);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $validatedData[KleoForm::inputEmail];

          $titulo = self::emailTitulo;
          $mensagem = '<p>Seu cadastro inicial foi concluido</p>
          <p>Em breve um dos nosso executivos entrará em contato.</p>';

          self::enviarEmail($emails, $titulo, $mensagem);
          unset($emails);
          $emails[] = self::emailLeo;
          $emails[] = self::emailKort;
          $urlResponsaveis = self::url . 'cadastroResponsaveis';

          $titulo = 'Primeiro Contato';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Resposavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$responsavel->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>Email '. $responsavel->getEmail(). '</p>';
          $mensagem .= '<p><a href="'.$urlResponsaveis.'">Visualizar</a></p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'responsavelFinalizado',
            self::stringMensagem => 'Cadastro concluido.'
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavel',
            self::stringFormulario => $cadastrarResponsavelForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizado
     */
  public function responsavelFinalizadoAction() {
    return new ViewModel();
  } 
  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelAlterado
     */
  public function responsavelAlteradoAction() {
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsaveis
     */
  public function responsaveisAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $responsaveis = $repositorioORM->getResponsavelORM()->encontrarTodos();
    return new ViewModel(
      array(
      'responsaveis' => $responsaveis,
    )
    );
  }

  /**
     * Formulario para alterar situacao
     * GET /cadastroResponsavelSituacao
     */
  public function responsavelSituacaoAction() {
    $this->setLayoutAdm();    
    $this->getSessao();

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $sessao = self::getSessao();
    $idResponsavel = $sessao->idSessao;
    if(empty($idResponsavel)){
      return $this->redirect()->toRoute(self::rotaCadastro, array(
        self::stringAction => 'responsaveis',
      ));
    }
    unset($sessao->idSessao);

    $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($idResponsavel); 
    $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodos();

    $responsavelSituacaoForm = new ResponsavelSituacaoForm('ResponsavelSituacao', $idResponsavel, $situacoes, 
                                                           $responsavel->getResponsavelSituacaoAtivo()->getSituacao()->getId());
    return new ViewModel(
      array(
      self::stringFormulario => $responsavelSituacaoForm,
      'responsavel' => $responsavel,
    ));
  }

  /**
  * Ação para alterar situacao
  * GET /cadastroResponsavelSituacaoFinalizar
  */
  public function responsavelSituacaoFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
        $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($post_data[KleoForm::inputId]);

        $gerar = false;
        if($responsavel->getResponsavelSituacaoAtivo()->getSituacao()->getId() !== intval($post_data[KleoForm::inputSituacao])){
          $gerar = true;
        }
        if($gerar){
          $token = '';
          $situacaoAguardandoDocumentacao = 2;
          $situacaoAtivo = 4;

          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAguardandoDocumentacao){
            $token = $responsavel->gerarToken();
            $responsavel->setToken($token);
            $repositorioORM->getResponsavelORM()->persistir($responsavel, false);
          }

          $responsavelSituacaoAtivo = $responsavel->getResponsavelSituacaoAtivo();
          $responsavelSituacaoAtivo->setDataEHoraDeInativacao();
          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacaoAtivo, false);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($post_data[KleoForm::inputSituacao]);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $responsavel->getEmail();
          $titulo = self::emailTitulo;
          $mensagem = '';
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAguardandoDocumentacao){
            $mensagem = '<p>Precisamos que você atualize seus dados</p>';
            $mensagem .= '<p>Clique no link abaixo para atualizar</p>';
            $mensagem .= '<p><a href="'.self::url.'cadastroResponsavelAtualizacao/'.$token.'">Clique aqui</a></p>';
          }
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAtivo){
            $mensagem = '<p>Cadastro ativado</p>';
            $mensagem .= '<p>Usuario: '.$responsavel->getEmail().'</p>';
            $mensagem .= '<p>Senha: 123456</p>';
            $mensagem .= '<p><a href="'.self::url.'">Clique aqui para acessar</a></p>';
          }
          self::enviarEmail($emails, $titulo, $mensagem);
        }

        return $this->redirect()->toRoute(self::rotaCadastro, array(
          self::stringAction => 'responsaveis',
        ));

      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
  }

  /**
     * Formulario para ver responsavel
     * GET /cadastroResponsavelVer
     */
  public function responsavelVerAction() {
    $this->setLayoutAdm();    
    $this->getSessao();

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $sessao = self::getSessao();
    $idResponsavel = $sessao->idSessao;
    if(empty($idResponsavel)){
      return $this->redirect()->toRoute(self::rotaCadastro, array(
        self::stringAction => 'responsaveis',
      ));
    }
    unset($sessao->idSessao);

    $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($idResponsavel); 

    return new ViewModel(
      array(
      'responsavel' => $responsavel,
    ));
  }

  /**
     * Formulario para alterar situacao
     * GET /cadastroResponsavelAtualizacao
     */
  public function responsavelAtualizacaoAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $responsavelAtualizacaoForm = $formulario;
    }else{
      $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
      $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
      $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 
      $responsavel->setId($token);
      $responsavelAtualizacaoForm = new ResponsavelAtualizacaoForm('ResponsavelAtualizacao', $responsavel);
    }

    return new ViewModel(
      array(
      self::stringFormulario => $responsavelAtualizacaoForm,
    ));
  }

  /**
     * Atualiza os dados do responsavel
     * GET /cadastroResponsavelAtualizar
     */
  public function responsavelAtualizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $token = $post_data[KleoForm::inputId];
        $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 

        $responsavelAtualizacaoForm = new ResponsavelAtualizacaoForm(null, $responsavel);
        $responsavelAtualizacaoForm->setInputFilter($responsavel->getInputFilterAtualizarResponsavel(false));

        $post = array_merge_recursive(
          $request->getPost()->toArray(),
          $request->getFiles()->toArray()
        );

        $responsavelAtualizacaoForm->setData($post);

        if ($responsavelAtualizacaoForm->isValid()) {

          $responsavel->exchangeArray($responsavelAtualizacaoForm->getData());
          $responsavel->setToken(null);

          $responsavel = self::escreveDocumentos($responsavel);

          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $responsavelSituacaoAtivo = $responsavel->getResponsavelSituacaoAtivo();
          $responsavelSituacaoAtivo->setDataEHoraDeInativacao();
          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacaoAtivo, false);

          $situacaoDocumentacaoEntregues = 3;
          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($situacaoDocumentacaoEntregues);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $validatedData[KleoForm::inputEmail];

          $titulo = self::emailTitulo;
          $mensagem = '<p>Dados atualizados.</p>
                    <p>Em breve um dos nosso executivos entrará em contato.</p>';

          self::enviarEmail($emails, $titulo, $mensagem);
          unset($emails);
          $emails[] = self::emailLeo;
          $emails[] = self::emailKort;

          $titulo = 'Documentos Entregues';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Responsavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$responsavel->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>Email '. $responsavel->getEmail(). '</p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'responsavelAlterado',
            self::stringMensagem => 'Atualização concluida.'
          ));
        } else {      
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavelAtualizacao',
            self::stringFormulario => $responsavelAtualizacaoForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Seta o layout da administracao
     */
  private function setLayoutAdm() {
    $this->layout('layout/adm');
  }

  /**
     * Função de cadastro de empresa
     * GET /cadastroLoja
     */
  public function lojaAction() {
    $this->setLayoutAdm();

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $shoppings = $repositorioORM->getShoppingORM()->encontrarTodos();

    if($formulario){
      $cadastroLojaForm = $formulario;
      $formulario->setarShoppings($shoppings);
    }else{
      $cadastroLojaForm = new CadastroLojaForm('cadastroLoja', $shoppings);
    }
    return new ViewModel(
      array(self::stringFormulario => $cadastroLojaForm,)
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroLojaFinalizar
     */
  public function LojaFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $loja = new Loja();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());  
        $shoppings = $repositorioORM->getShoppingORM()->encontrarTodos();

        $cadastrarLojaForm = new CadastroLojaForm(null, $shoppings);
        $cadastrarLojaForm->setInputFilter($loja->getInputFilterCadastrarLoja());
        $cadastrarLojaForm->setData($post_data);

        /* validação */
        if ($cadastrarLojaForm->isValid()) {
          $validatedData = $cadastrarLojaForm->getData();
          $loja->exchangeArray($cadastrarLojaForm->getData());

          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $idResposanvelLogado = 50;// temporario

          $shopping = $repositorioORM->getShoppingORM()->encontrarPorId($validatedData[KleoForm::inputShoppingId]);          $repositorioORM->getLojaORM()->persistir($loja);
          $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($idResposanvelLogado);
          $loja->setShopping($shopping);
          $loja->setResponsavel($responsavel);
          $repositorioORM->getLojaORM()->persistir($loja);

          $situacaoPrimeiroContato = 1;
          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($situacaoPrimeiroContato);
          $lojaSituacao = new LojaSituacao();
          $lojaSituacao->setLoja($loja);
          $lojaSituacao->setSituacao($situacao);
          $repositorioORM->getLojaSituacaoORM()->persistir($lojaSituacao);

          unset($emails);
          $emails[] = self::emailLeo;
          $emails[] = self::emailKort;
          $urlLojas = self::url . 'cadastroLojas';

          $titulo = 'Nova Loja';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Resposavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$loja->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>CNPJ '. $loja->getCNPJ(). '</p>';
          $mensagem .= '<p>Shopping '. $shopping->getNome(). '</p>';
          $mensagem .= '<p><a href="'.$urlLojas.'">Visualizar</a></p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'lojas',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'loja',
            self::stringFormulario => $cadastrarLojaForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Tela com listagem de empresas
     * GET /cadastroLojas
     */
  public function lojasAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $lojas = $repositorioORM->getLojaORM()->encontrarTodos();
    return new ViewModel(
      array('lojas' => $lojas,)
    );
  }


  /**
     * Formulario para alterar situacao da loja
     * GET /cadastroLojaSituacao
     */
  public function lojaSituacaoAction() {
    $this->setLayoutAdm();    
    $this->getSessao();

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $sessao = self::getSessao();
    $idLoja = $sessao->idSessao;
    if(empty($idLoja)){
      return $this->redirect()->toRoute(self::rotaCadastro, array(
        self::stringAction => 'lojas',
      ));
    }
    unset($sessao->idSessao);

    $loja = $repositorioORM->getLojaORM()->encontrarPorId($idLoja); 
    $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodos();

    $lojaSituacaoForm = new LojaSituacaoForm('LojaSituacao', $idLoja, $situacoes, 
                                             $loja->getLojaSituacaoAtivo()->getSituacao()->getId());
    return new ViewModel(
      array(
      self::stringFormulario => $lojaSituacaoForm,
      'loja' => $loja,
    ));
  }

  /**
  * Ação para alterar situacao
  * GET /cadastroLojaSituacaoFinalizar
  */
  public function lojaSituacaoFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
        $loja = $repositorioORM->getLojaORM()->encontrarPorId($post_data[KleoForm::inputId]);

        $gerar = false;
        if($loja->getLojaSituacaoAtivo()->getSituacao()->getId() !== intval($post_data[KleoForm::inputSituacao])){
          $gerar = true;
        }
        if($gerar){
          $token = '';
          $situacaoAtivo = 4;
          $situacaoRecusado = 5;

          $lojaSituacaoAtivo = $loja->getLojaSituacaoAtivo();
          $lojaSituacaoAtivo->setDataEHoraDeInativacao();
          $repositorioORM->getResponsavelSituacaoORM()->persistir($lojaSituacaoAtivo, false);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($post_data[KleoForm::inputSituacao]);
          $lojaSituacao = new LojaSituacao();
          $lojaSituacao->setLoja($loja);
          $lojaSituacao->setSituacao($situacao);

          $repositorioORM->getLojaSituacaoORM()->persistir($lojaSituacao);

          $emails[] = $loja->getResponsavel()->getEmail();
          $titulo = self::emailTitulo;
          $mensagem = '';
          $mensagem .= '<p>Shopping: '.$loja->getShopping()->getNome().'</p>';
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoRecusado){
            $mensagem .= '<p>Loja recusada</p>';
          }
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAtivo){
            $mensagem .= '<p>Loja ativada</p>';
          }
          self::enviarEmail($emails, $titulo, $mensagem);
        }
        return $this->redirect()->toRoute(self::rotaCadastro, array(
          self::stringAction => 'lojas',
        ));

      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
  }


  /**
     * Função de cadastro de shopping
     * GET /cadastroShopping
     */
  public function shoppingAction() {
    $this->setLayoutAdm();

    $formulario = $this->params()->fromRoute(self::stringFormulario);

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $estados = $repositorioORM->getEstadoORM()->encontrarTodos();

    if($formulario){
      $cadastroShoppingForm = $formulario;
      $cadastroShoppingForm->setarEstados($estados);
    }else{
      $cadastroShoppingForm = new CadastroShoppingForm('cadastroShopping', $estados);
    }

    return new ViewModel(
      array(self::stringFormulario => $cadastroShoppingForm,)
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroShoppingFinalizar
     */
  public function shoppingFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $shopping = new Shopping();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());  
        $estados = $repositorioORM->getEstadoORM()->encontrarTodos();

        $cadastrarShoppingForm = new CadastroShoppingForm(null, $estados);
        $cadastrarShoppingForm->setInputFilter($shopping->getInputFilterCadastrarShopping());
        $cadastrarShoppingForm->setData($post_data);

        /* validação */
        if ($cadastrarShoppingForm->isValid()) {
          $validatedData = $cadastrarShoppingForm->getData();
          $shopping->exchangeArray($cadastrarShoppingForm->getData());

          $estado = $repositorioORM->getEstadoORM()->encontrarPorId($validatedData[KleoForm::inputEstadoId]);
          $shopping->setEstado($estado);
          $repositorioORM->getShoppingORM()->persistir($shopping);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'shoppings',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'shopping',
            self::stringFormulario => $cadastrarShoppingForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Tela com listagem de shoppings
     * GET /cadastroShoppings
     */
  public function shoppingsAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $shoppings = $repositorioORM->getShoppingORM()->encontrarTodos();
    return new ViewModel(
      array(
      'shoppings' => $shoppings,
    )
    );
  }  

  /**
     * Tela com listagem de anuncios
     * GET /cadastroAnuncios
     */
  public function anunciosAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $anuncios = $repositorioORM->getAnuncioORM()->encontrarTodos();
    return new ViewModel(
      array(
      'anuncios' => $anuncios,
    )
    );
  }

  /**
     * Tela com listagem de anuncio
     * GET /cadastroAnuncio
     */
  public function anuncioAction() {
    $this->setLayoutAdm();

    $formulario = $this->params()->fromRoute(self::stringFormulario);

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $categorias = $repositorioORM->getCategoriaORM()->encontrarTodos();

    if($formulario){
      $cadastroAnuncioForm = $formulario;
    }else{
      $cadastroAnuncioForm = new CadastroAnuncioForm('cadastroAnuncio');
    }
    $cadastroAnuncioForm->setarCategorias($categorias);
    $cadastroAnuncioForm->setarCategorias($categorias, 2);

    return new ViewModel(
      array(self::stringFormulario => $cadastroAnuncioForm,)
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroAnuncioFinalizar
     */
  public function anuncioFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $anuncio = new Anuncio();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());  
        $categorias = $repositorioORM->getCategoriaORM()->encontrarTodos();

        $cadastrarAnuncioForm = new CadastroAnuncioForm(null, $categorias);
        $cadastrarAnuncioForm->setInputFilter($anuncio->getInputFilterCadastrarAnuncio());

        $post = array_merge_recursive(
          $request->getPost()->toArray(),
          $request->getFiles()->toArray()
        );

        $cadastrarAnuncioForm->setData($post);

        /* validação */
        if ($cadastrarAnuncioForm->isValid()) {
          $validatedData = $cadastrarAnuncioForm->getData();
          $anuncio->exchangeArray($cadastrarAnuncioForm->getData());

          $apenasAjustarEntidade = false;
          $anuncio = self::escreveDocumentos($anuncio, $apenasAjustarEntidade);

          $idResposanvelLogado = 50; // temporario
          $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($idResposanvelLogado);
          $anuncio->setResponsavel($responsavel);
          $repositorioORM->getAnuncioORM()->persistir($anuncio);

          $anuncio = self::escreveDocumentos($anuncio);
          $repositorioORM->getAnuncioORM()->persistir($anuncio);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId(1);
          $anuncioSituacao = new AnuncioSituacao();
          $anuncioSituacao->setAnuncio($anuncio);
          $anuncioSituacao->setSituacao($situacao);
          $repositorioORM->getAnuncioSituacaoORM()->persistir($anuncioSituacao);

          $categoria = $repositorioORM->getCategoriaORM()->encontrarPorId($validatedData[KleoForm::inputCategoriaId]);
          $anuncioCategoria = new AnuncioCategoria();
          $anuncioCategoria->setAnuncio($anuncio);
          $anuncioCategoria->setCategoria($categoria);
          $repositorioORM->getAnuncioCategoriaORM()->persistir($anuncioCategoria);

          $subCategoria = $repositorioORM->getCategoriaORM()->encontrarPorId($validatedData[KleoForm::inputSubCategoriaId]);
          $anuncioSubCategoria = new AnuncioCategoria();
          $anuncioSubCategoria->setAnuncio($anuncio);
          $anuncioSubCategoria->setCategoria($subCategoria);
          $repositorioORM->getAnuncioCategoriaORM()->persistir($anuncioSubCategoria);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'anuncios',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'anuncio',
            self::stringFormulario => $cadastrarAnuncioForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Tela com listagem de categorias
     * GET /cadastroCategorias
     */
  public function categoriasAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $categorias = $repositorioORM->getCategoriaORM()->encontrarTodos();
    return new ViewModel(
      array(
      'categorias' => $categorias,
    )
    );
  }

  /**
     * Tela com listagem de categoria
     * GET /cadastroCategoria
     */
  public function categoriaAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $formulario = $this->params()->fromRoute(self::stringFormulario);
    $categorias = $repositorioORM->getCategoriaORM()->encontrarTodos();
    if($formulario){
      $form = $formulario;
    }else{
      $form = new CadastroCategoriaForm('cadastroCategoria');
    }
    $form->setarCategorias($categorias);

    return new ViewModel(
      array(self::stringFormulario => $form,)
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroCategoriaFinalizar
     */
  public function categoriaFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $categoria = new Categoria();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());  
        $categorias = $repositorioORM->getCategoriaORM()->encontrarTodos();

        $cadastrarCategoriaForm = new CadastroCategoriaForm(null, $categorias);
        $cadastrarCategoriaForm->setInputFilter($categoria->getInputFilterCadastrarCategoria());
        $cadastrarCategoriaForm->setData($post_data);

        /* validação */
        if ($cadastrarCategoriaForm->isValid()) {
          $validatedData = $cadastrarCategoriaForm->getData();
          $categoria->exchangeArray($cadastrarCategoriaForm->getData());
          $repositorioORM->getCategoriaORM()->persistir($categoria);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'categorias',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'categoria',
            self::stringFormulario => $cadastrarCategoriaForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

}
