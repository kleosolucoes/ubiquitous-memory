<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Loja;
use Application\Model\Entity\LojaSituacao;
use Application\Model\Entity\Shopping;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\CadastroLojaForm;
use Application\Form\CadastroShoppingForm;
use Application\Form\ResponsavelSituacaoForm;
use Application\Form\ResponsavelAtualizacaoForm;
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

}
